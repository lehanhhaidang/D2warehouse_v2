import { useState, useEffect } from 'react';
import {
  Card,
  Typography,
  Table,
  Descriptions,
  Select,
  Button,
  message,
  Input,
} from 'antd';
import { PlusOutlined } from '@ant-design/icons';
import * as shelf from '../service/shelve.service';
import * as material from '../service/material.service';
import * as product from '../service/product.service';
import moment from 'moment';
import { showNotification } from '../utilities/notification';
const { Title } = Typography;

export const MaterialReceiptImport = ({
  proposeDetail,
}: {
  proposeDetail: any;
}) => {
  const [shelfSelections, setShelfSelections] = useState<{
    [key: string]: any;
  }>({});
  const [shelves, setShelves] = useState<{ [key: string]: any[] }>({});

  useEffect(() => {
    if (proposeDetail) {
      loadFilterShelves();
    }
  }, [proposeDetail]);
  const handleShelfChange = (value: any, record: any) => {
    const key =
      proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
        ? record.material_id
        : record.product_id;

    setShelfSelections((prev) => ({
      ...prev,
      [key]: value,
    }));
  };
  const loadFilterShelves = async () => {
  try {
    const shelvesData: { [key: string]: any[] } = {};
    for (const item of proposeDetail.details) {
      const key =
        proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
          ? item.material_id
          : item.product_id;

      // Kiểm tra điều kiện 'DXXTP' hoặc 'DXXNVL' để gọi filterShelvesExport
      const filterFunction = 
        proposeDetail.type === 'DXXTP' || proposeDetail.type === 'DXXNVL'
          ? shelf.filterShelvesExport
          : shelf.filterShelves;

      const result = await filterFunction(proposeDetail.warehouse_id, key);
      shelvesData[key] = result.data ? result.data.data : [];
    }
    setShelves(shelvesData);
  } catch (error) {
    console.error('Error loading shelves:', error);
    message.error('Không tải được dữ liệu kệ');
  }
};
const [loading,setLoading] = useState(false);
  const columns = [
    {
      title:
        proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
          ? 'Mã nguyên vật liệu'
          : 'Mã thành phẩm',
      dataIndex:
        proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
          ? 'material_id'
          : 'product_id',
      key:
        proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
          ? 'material_id'
          : 'product_id',
    },
    {
      title: 'Kệ',
      dataIndex: 'shelf_id',
      key: 'shelf_id',
      render: (_: any, record: any) => {
        const key =
          proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
            ? record.material_id
            : record.product_id;

        return (
          <Select
            onChange={(value) => handleShelfChange(value, record)}
            style={{ width: 120 }}
            value={shelfSelections[key] || record.shelf_id || undefined}
            placeholder="Chọn kệ"
          >
            {shelves[key]?.length > 0 ? (
              shelves[key].map((shelfItem) => (
                <Select.Option key={shelfItem.id} value={shelfItem.id}>
                  {shelfItem.name}
                </Select.Option>
              ))
            ) : (
              <Select.Option disabled>Không có kệ</Select.Option>
            )}
          </Select>
        );
      },
    },
    {
      title:
        proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
          ? 'Nguyên vật liệu'
          : 'Thành phẩm',
      dataIndex:
        proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
          ? 'material_name'
          : 'product_name',
      key:
        proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
          ? 'material_name'
          : 'product_name',
    },
    {
      title: 'Đơn vị',
      dataIndex: 'unit',
      key: 'unit',
    },
    {
      title: 'Số lượng',
      dataIndex: 'quantity',
      key: 'quantity',
    },
  ];

const handleCreate = async () => {
  try {
    setLoading(true);
    // Cập nhật ngày tạo (Ngày giờ hiện tại)
    const createdAt = moment().format('YYYY-MM-DD HH:mm:ss');

    const details = proposeDetail.details.map((item: any) => ({
      ...item,
      shelf_id:
        shelfSelections[
          proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
            ? item.material_id
            : item.product_id
        ] || item.shelf_id,
    }));

    const proposeId = proposeDetail.details[0]?.propose_id;
    const updatedDetails = details.map(
      ({ propose_id, ...rest }: any) => rest
    );

    // Cập nhật thông tin vào đối tượng 'result'
    const result = {
      name: String(proposeDetail.name.replace("Đề xuất", "Phiếu")),
      propose_id: proposeId,
      [proposeDetail.type === 'DXNNVL'
        ? 'receive_date'
        : proposeDetail.type === 'DXXNVL'
          ? 'export_date'
          : proposeDetail.type === 'DXNTP'
            ? 'receive_date'
            : 'export_date']:
        proposeDetail.type === 'DXNNVL'
          ? moment(proposeDetail.receive_date).format('YYYY-MM-DD HH:mm:ss')
          : proposeDetail.type === 'DXXNVL'
            ? moment(proposeDetail.export_date).format('YYYY-MM-DD HH:mm:ss')
            : createdAt,  // Thay 'created_at' bằng thời gian hiện tại
      warehouse_id: proposeDetail.warehouse_id,
      note: null,
      details: updatedDetails,
    };

    // Gọi API để lưu dữ liệu
    const response =
      proposeDetail.type === 'DXNNVL'
        ? await material.createMaterialReceipt(result)
        : proposeDetail.type === 'DXXNVL'
          ? await material.createMaterialExport(result)
          : proposeDetail.type === 'DXNTP'
            ? await product.createProductReceipt(result)
            : await product.createProductExport(result);

    if (response.data) {
      console.log('Tạo thành công');
    }
    showNotification(response);
  } catch (error) {
    showNotification(error);
  }
  setLoading(false);
};


  return (
    <div className="max-w-4xl mx-auto p-6">
      <Card className="shadow-lg">
        <Title level={3} className="mb-4 text-center">
          Chi tiết Phiếu: {proposeDetail.name}
        </Title>
        <Descriptions
          bordered
          column={1}
          labelStyle={{ fontWeight: 'bold', width: '30%' }}
        >
          <Descriptions.Item label="Tên phiếu">
            {proposeDetail.name.replace("Đề xuất", "Phiếu")}
          </Descriptions.Item>
          <Descriptions.Item label="Ngày tạo">
            {moment().format('HH:mm:ss DD/MM/YYYY')}
          </Descriptions.Item>
          <Descriptions.Item label="Mã nhà kho">
            {proposeDetail.warehouse_name}
          </Descriptions.Item>
          <Descriptions.Item label="Người tạo">
            {proposeDetail.type === 'DXNTP' || proposeDetail.type === 'DXXTP' 
              ? proposeDetail.created_by_name
              : (proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL' 
                ? proposeDetail.assigned_to_name 
                : null)}
          </Descriptions.Item>
        </Descriptions>
        <Title level={4} className="mt-6">
          Chi tiết
        </Title>
        <Table
          columns={columns}
          dataSource={proposeDetail.details}
          pagination={false}
          rowKey={(record) =>
            proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'
              ? record.material_id
              : record.product_id
          }
          className="mt-4"
        />
        <div className="mt-6 text-center">
          <Button type="primary" onClick={handleCreate} icon={<PlusOutlined />} loading={loading}>
            Tạo mới
          </Button>
        </div>
      </Card>
    </div>
  );
};
