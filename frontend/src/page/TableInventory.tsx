import { useParams } from 'react-router-dom';
import { Table, Card, Descriptions, Button, Space, Modal } from 'antd';
import type { ColumnsType } from 'antd/es/table';
import {
  CheckOutlined,
  CloseOutlined,
  LoadingOutlined,
} from '@ant-design/icons';
import * as propose from '../service/inventory-report.service';
import { useEffect, useState } from 'react';
import { IUser } from '../common/interface';
import { Footer } from '../components/footer/Footer';

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
  details: DetailItem[];
}

export const TableInventory = () => {
  const { id } = useParams();
  // Khai báo kiểu cho state proposeDetail là ProposeDetail | null
  const [proposeDetail, setProposeDetail] = useState<ProposeDetail | null>(null);
  const [loading, setLoading] = useState(false);
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');

  const loadData = async () => {
    const response = await propose.getInventoryDetail(Number(id));
    if (response.data) {
      setProposeDetail(response.data.data);
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
      title: 'Tên kệ',
      dataIndex: 'shelf_name',
      key: 'shelf_name',
      render: (text, record) => text || record.material_name,
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
      title: 'Số lượng mong đợi',
      dataIndex: 'expected_quantity',
      key: 'expected_quantity',
      width: 100,
    },
    {
      title: 'Số lượng thực tế',
      dataIndex: 'actual_quantity',
      key: 'actual_quantity',
      width: 100,
    },
    {
      title: 'Ghi chú',
      dataIndex: 'note',
      key: 'note',
      width: 200,
    }
  ];

  const getStatusText = (status: number) => {
    switch (status) {
      case 0:
        return 'Chờ gửi';
      case 1:
        return 'Chờ duyệt';
      case 2:
        return 'Đã duyệt';
      case 3:
        return 'Đã từ chối';
      case 4:
        return 'Đã thông qua';
      case 5:
        return 'Đã hủy';
      default:
        return 'Không xác định';
    }
  };

  const handleApprove = () => {
    Modal.confirm({
      title: 'Xác nhận duyệt',
      content: 'Bạn có chắc chắn muốn duyệt báo cáo kiểm kê này?',
      onOk: async () => {
        try {
          setLoading(true);
          const response = await propose.acceptInventory(Number(id));
          if (response.data) {
            loadData();
            Modal.success({
              content: 'Duyệt báo cáo kiểm kê thành công',
            });
          }
        } catch (error) {
          Modal.error({
            content: 'Có lỗi xảy ra khi duyệt báo cáo kiểm kê',
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
      content: 'Bạn có chắc chắn muốn từ chối báo cáo kiểm kê này?',
      onOk: async () => {
        try {
          setLoading(true);
          const response = await propose.rejectInventory(Number(id));
          if (response.data) {
            loadData();
            Modal.success({
              content: 'Từ chối báo cáo kiểm kê thành công',
            });
          }
        } catch (error) {
          Modal.error({
            content: 'Có lỗi xảy ra khi từ chối báo cáo kiểm kê',
          });
        } finally {
          setLoading(false);
        }
      },
    });
  };

  const handleConfirmUpdate = () => {
    Modal.confirm({
      title: 'Xác nhận thông qua và cập nhật',
      content: 'Bạn có chắc chắn muốn xác nhận thông qua báo cáo kiểm kê này?. Sau khi xác nhận, số lượng chênh lệch được ghi nhận sẽ được cập nhật vào cơ sở dữ liệu. Thao tác này không thể hoàn tác.',
      onOk: async () => {
        try {
          setLoading(true);
          const response = await propose.confirmUpdate(Number(id));
          if (response.data) {
            loadData();
            Modal.success({
              content: 'Xác nhận thông qua và cập nhật thành công',
            });
          }
        } catch (error) {
          Modal.error({
            content: 'Có lỗi xảy ra khi xác nhận thông qua và cập nhật',
          });
        } finally {
          setLoading(false);
        }
      },
    });
  };

    const handleCancel = () => {
    Modal.confirm({
      title: 'Xác nhận từ chối và đóng báo cáo',
      content: 'Bạn có chắc chắn muốn từ chối báo cáo kiểm kê này?. Sau khi xác nhận, báo cáo kiểm kê sẽ bị đóng và không thể khôi phục.',
      onOk: async () => {
        try {
          setLoading(true);
          const response = await propose.cancelInventoryReport(Number(id));
          if (response.data) {
            loadData();
            Modal.success({
              content: 'Từ chối báo cáo kiểm kê thành công',
            });
          }
        } catch (error) {
          Modal.error({
            content: 'Có lỗi xảy ra khi từ chối báo cáo kiểm kê',
          });
        } finally {
          setLoading(false);
        }
      },
    });
  };
  if (!proposeDetail) {
    return (
      <div className="text-center text-5xl mt-96 text-gray-600 animate-pulse">
        <LoadingOutlined className="text-blue-500" />
      </div>
    );
  }

  return (
     <div className="flex w-full justify-center bg-slate-300" style={{ height: 'calc(85vh)' }}>
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content">
          <div className="space-y-4">
      <Card
        title="Thông tin báo cáo"
        extra={
        <>
          {proposeDetail.status === 1 && userInfor.role_id === 2 && (
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
          )}
          {proposeDetail.status === 2 && userInfor.role_id === 3 && (
            <Space>
              <Button
                type="primary"
                icon={<CheckOutlined />}
                onClick={handleConfirmUpdate}
                loading={loading}
              >
                Xác nhận
                </Button>
                <Button
                danger
                icon={<CloseOutlined />}
                onClick={handleCancel}
                loading={loading}
              >
                Từ chối
              </Button>
            </Space>
          )}
        </>
      }

      >
        <Descriptions column={2}>
          <Descriptions.Item label="Mã báo cáo">
            {proposeDetail.id}
          </Descriptions.Item>
          <Descriptions.Item label="Tên báo cáo kiểm kê">
            {proposeDetail.name}
          </Descriptions.Item>
          <Descriptions.Item label="Kho">
            {proposeDetail.warehouse_name}
          </Descriptions.Item>
          <Descriptions.Item label="Trạng thái">
            <span
              className={`
                  ${proposeDetail.status === 0 ? 'text-red-500' : ''}
                  ${proposeDetail.status === 1 ? 'text-yellow-500' : ''}
                  ${proposeDetail.status === 2 ? 'text-green-500' : ''}
                `}
            >
              {getStatusText(proposeDetail.status)}
            </span>
          </Descriptions.Item>
          <Descriptions.Item label="Mô tả" span={2}>
            {proposeDetail.description}
          </Descriptions.Item>
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
