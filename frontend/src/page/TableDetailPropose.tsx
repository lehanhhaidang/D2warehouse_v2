import { Link, useParams } from 'react-router-dom';
import { Table, Card, Descriptions, Button, Space, Modal, Tag } from 'antd';
import type { ColumnsType } from 'antd/es/table';
import { CheckOutlined, CloseOutlined, LoadingOutlined } from '@ant-design/icons';
import * as propose from '../service/propose.service';
import { useEffect, useState } from 'react';
import { tabTitle } from '../utilities/title';
import { IUser } from '../common/interface';
import { Footer } from 'antd/es/layout/layout';

interface DetailItem {
  propose_id: number;
  product_id: number;
  product_name: string;
  material_id: number | null;
  material_name: string | null;
  unit: string;
  quantity: number;
}

interface ProposeDetail {
  id: number;
  name: string;
  type: string;
  warehouse_id: number;
  warehouse_name: string;
  status: number;
  description: string;
  created_by: number;
  created_at: string;
  updated_at: string | null;
  assigned_to_name: string;
  manufacturing_plan_id: number;
  manufacturing_plan_name: string;
  order_id: number;
  order_name: string;
  details: DetailItem[];
}

export const TableDetailPropose = () => {
  const { id } = useParams();
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const [proposeDetail, setProposeDetail] = useState<ProposeDetail | null>(null);
  const [loading, setLoading] = useState(false);

  const loadData = async () => {
    const response = await propose.loadProposeDetail(Number(id));
    if (response.data) {
      setProposeDetail(response.data);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const columns: ColumnsType<DetailItem> = [
    {
      title: 'STT',
      key: 'index',
      width: 60,
      render: (_text, _record, index) => index + 1,
    },
    {
      title: 'Tên sản phẩm',
      dataIndex: 'product_name',
      key: 'product_name',
      render: (text, record) => text || record.material_name,
    },
    {
      title: 'Đơn vị',
      dataIndex: 'unit',
      key: 'unit',
      width: 100,
    },
    {
      title: 'Số lượng',
      dataIndex: 'quantity',
      key: 'quantity',
      width: 100,
    },
    {
      title: 'Ghi chú',
      dataIndex: 'note',
      key: 'note',
      width: 200,
    },
  ];

  const getStatusText = (status: number) => {
    switch (status) {
          case 0:
            return <Tag color="blue">Chờ gửi</Tag>;
          case 1:
            return <Tag color="orange">Chờ duyệt</Tag>;
          case 2:
            return <Tag color="green-inverse">Đã duyệt</Tag>;
          case 3:
            return <Tag color="red-inverse">Đã từ chối</Tag>;
          case 4:
            return <Tag color="blue-inverse">Đã lập phiếu</Tag>;
          default:
            return 'Không xác định';
        }
  };

  const handleApprove = () => {
    Modal.confirm({
      title: 'Xác nhận duyệt',
      content: 'Bạn có chắc chắn muốn duyệt phiếu đề xuất này?',
      onOk: async () => {
        try {
          setLoading(true);
          await propose.acceptPropose(Number(id));
          loadData();
          Modal.success({
            content: 'Duyệt phiếu đề xuất thành công',
          });
        } catch (error) {
          Modal.error({
            content: 'Có lỗi xảy ra khi duyệt phiếu đề xuất',
          });
        } finally {
          setLoading(false);
        }
      },
    });
  };

  const handleReject = () => {
    Modal.confirm({
      title: 'Xác nhận từ chối',
      content: 'Bạn có chắc chắn muốn từ chối phiếu đề xuất này?',
      onOk: async () => {
        try {
          setLoading(true);
          await propose.rejectPropose(Number(id));
          loadData();
          Modal.success({
            content: 'Từ chối phiếu đề xuất thành công',
          });
        } catch (error) {
          Modal.error({
            content: 'Có lỗi xảy ra khi từ chối phiếu đề xuất',
          });
        } finally {
          setLoading(false);
        }
      },
    });
  };

  if (!proposeDetail) {
    return <div className="text-center text-5xl mt-96 text-gray-600 animate-pulse"><LoadingOutlined className='text-blue-500'/></div>;
  }

  // Determine if the user has permission to approve
  const canApprove =
    (userInfor.role_id === 2 &&
      (proposeDetail.type === 'DXNTP' || proposeDetail.type === 'DXXTP')) ||
    (userInfor.role_id === 3 &&
      (proposeDetail.type === 'DXNNVL' || proposeDetail.type === 'DXXNVL'));

  return (
    tabTitle('Chi tiết đề xuất'),
    <div className="flex w-full justify-center bg-slate-300" style={{ height: 'calc(85vh)' }}>
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content">
          <div className="space-y-4">
            <Card
              title="Thông tin phiếu đề xuất"
              extra={
                proposeDetail.status === 1 && canApprove && (
                  <Space>
                    <Button
                      type="primary"
                      icon={<CheckOutlined />}
                      onClick={handleApprove}
                      loading={loading}
                    >
                      Duyệt
                    </Button>
                    <Button
                      danger
                      icon={<CloseOutlined />}
                      onClick={handleReject}
                      loading={loading}
                    >
                      Từ chối
                    </Button>
                  </Space>
                )
              }
            >
              <Descriptions column={2}>
                <Descriptions.Item label="Mã phiếu">
                  {proposeDetail.id}
                </Descriptions.Item>
                <Descriptions.Item label="Tên phiếu">
                  {proposeDetail.name}
                </Descriptions.Item>
                <Descriptions.Item label="Loại phiếu">
                  {proposeDetail.type}
                </Descriptions.Item>
                <Descriptions.Item label="Kho">
                  {proposeDetail.warehouse_name}
                </Descriptions.Item>
                <Descriptions.Item label="Trạng thái">
                  <span
                    className={`${
                      proposeDetail.status === 0 ? 'text-red-500' : ''
                    } ${proposeDetail.status === 1 ? 'text-yellow-500' : ''} ${
                      proposeDetail.status === 2 ? 'text-green-500' : ''
                    }`}
                  >
                    {getStatusText(proposeDetail.status)}
                  </span>
                </Descriptions.Item>
                <Descriptions.Item label="Mô tả" span={2}>
                  {proposeDetail.description}
                </Descriptions.Item>
                <Descriptions.Item label="Phụ trách">
                  {proposeDetail.assigned_to_name}
                </Descriptions.Item>
                  {(proposeDetail.type === 'DXXNVL' || proposeDetail.type === 'DXNTP') && (
                    <Descriptions.Item label="Kế hoạch">
                      <Link to={`/manufacturing-detail/${proposeDetail.manufacturing_plan_id}`} className="font-semibold text-blue-700"> 
                            { proposeDetail.manufacturing_plan_name}
                          </Link>
                    </Descriptions.Item>
                )}
                {(proposeDetail.type === 'DXXTP') && (
                <Descriptions.Item label="Đơn hàng">
                  <Link to={`/orders`} className="font-semibold text-blue-700">
                    {proposeDetail.order_name}
                  </Link>
                </Descriptions.Item>
                )}
              </Descriptions>
            </Card>

            <Card title="Chi tiết sản phẩm">
              <Table
                columns={columns}
                dataSource={proposeDetail.details}
                rowKey={(record) => `${record.propose_id}-${record.product_id}`}
                pagination={false}
              />
            </Card>
          </div>
      </div>    
        <Footer />
    </div>
  );
};
