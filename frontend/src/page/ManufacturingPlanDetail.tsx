import { useParams } from 'react-router-dom';
import { Table, Card, Descriptions, Button, Space, Modal, Tag } from 'antd';
import { CheckOutlined, CloseOutlined, LoadingOutlined, SendOutlined } from '@ant-design/icons';
import * as manufacturingPlanService from '../service/manufacturing-plan.service';
import { useEffect, useState } from 'react';
import { IUser } from '../common/interface';
import { useNavigate } from 'react-router-dom';
import { Footer } from '../components/footer/Footer';
import { tabTitle } from '../utilities/title';

interface ManufacturingPlanDetail {
  id: number;
  name: string;
  product_id: number;
  product_name: string;
  material_id: number | null;
  material_name: string | null;
  product_quantity: number;
  material_quantity: number;
}

interface ManufacturingPlan {
  id: number;
  name: string;
  description: string;
  status: number;
  created_by: number;
  created_by_name: string;
  start_date: string;
  end_date: string;
  begin_manufacturing_by_name: string;
  finish_manufacturing_by_name: string;
  manufacturing_plan_details: ManufacturingPlanDetail[];
}

export const ManufacturingPlanDetail = () => {
  tabTitle("D2W - Chi tiết kế hoạch")
  const { id } = useParams();
  const [manufacturingPlan, setManufacturingPlan] = useState<ManufacturingPlan | null>(null);
  const [sendLoading, setSendLoading] = useState(false);
  const [deleteLoading, setDeleteLoading] = useState(false);
  const [acceptLoading, setAcceptLoading] = useState(false);
  const [rejectLoading, setRejectLoading] = useState(false);
  const [loading, setLoading] = useState(false);
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const navigate = useNavigate();

  const loadData = async () => {
    setLoading(true);
    try {
      const response = await manufacturingPlanService.getManufacturingDetail(Number(id));
      if (response.data) {
        setManufacturingPlan(response.data.data);
      }
    } catch (error) {
      Modal.error({ content: 'Có lỗi xảy ra khi tải dữ liệu kế hoạch sản xuất.' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();

  }, [id]);

  const columns = [
    {
      title: 'STT',
      key: 'index',
      render: (_text, _record, index) => index + 1,
    },
    {
      title: 'Tên thành phẩm cần sản xuất',
      dataIndex: 'product_name',
      key: 'product_name',
    },
    {
      title: 'Đơn vị thành phẩm',
      dataIndex: 'product_unit',
      key: 'product_unit',
    },
    {
      title: 'Số lượng thành phẩm',
      dataIndex: 'product_quantity',
      key: 'product_quantity',
    },
    {
      title: 'Tên nguyên vật liệu cần thiết',
      dataIndex: 'material_name',
      key: 'material_name',
    },
    {
      title: 'Đơn vị nguyên vật liệu',
      dataIndex: 'material_unit',
      key: 'material_unit',
    },
    {
      title: 'Số lượng nguyên vật liệu',
      dataIndex: 'material_quantity',
      key: 'material_quantity',
    }
  ];

  const getStatusText = (status: number) => {
    switch (status) {
          case 0:
            return <Tag color="orange">Chờ gửi</Tag>;
          case 1:
            return <Tag color="yellow">Chờ duyệt</Tag>;
          case 2:
            return <Tag color="green">Đã duyệt</Tag>;
          case 3:
            return <Tag color="blue">Chuẩn bị xuất</Tag>;
          case 4:
            return <Tag color="geekblue">Đã xuất nguyên vật liệu</Tag>;
          case 5:
            return <Tag color="purple">Đang sản xuất</Tag>;
          case 6:
            return <Tag color="cyan">Đợi nhập kho</Tag>;
          case 7:
            return <Tag color="blue-inverse">Hoàn thành</Tag>;
          case 8:
            return <Tag color="red">Đã hủy</Tag>;
          default:
            return <Tag color="default">Không xác định</Tag>;
        }
  };

  const handleSend = async () => {
    Modal.confirm({
      title: 'Xác nhận gửi kế hoạch sản xuất',
      content: 'Bạn có chắc chắn muốn gửi kế hoạch sản xuất này?',
      onOk: async () => {
        setSendLoading(true);
        try {
          const response = await manufacturingPlanService.sendManufacturingPlan(Number(id));
          if (response.data) {
            Modal.success({ content: 'Gửi kế hoạch sản xuất thành công' });
            loadData();
          }
        } catch (error) {
          Modal.error({ content: 'Có lỗi xảy ra khi gửi kế hoạch sản xuất.' });
        } finally {
          setSendLoading(false);
        }
      },
    })
};
    
  const handleDelete = async () => {
      
    Modal.confirm({
      title: 'Xóa kế hoạch sản xuất',
      content: 'Bạn có chắc chắn muốn xóa kế hoạch sản xuất này?',
      onOk: async () => {
        setDeleteLoading(true);
        try {
          const response = await manufacturingPlanService.deleteManufacturingPlan(Number(id));
          if (response.data) {
            Modal.success({ content: 'Xóa kế hoạch sản xuất thành công' });
            navigate(-1); // Navigate back to the previous page
          }
        } catch (error) {
          Modal.error({ content: 'Có lỗi xảy ra khi xóa kế hoạch sản xuất.' });
        } finally {
          setDeleteLoading(false);
        }
      },
    });
  };

  const handleApprove = async () => {
    Modal.confirm({
      title: 'Xét duyệt kế hoạch sản xuất',
      content: 'Bạn có chắc chắn muốn duyệt kế hoạch sản xuất này?',
      onOk: async () => {
        try {
          setAcceptLoading(true);
          const response = await manufacturingPlanService.acceptManufacturingPlan(Number(id));
          if (response.data) {
            loadData();
            Modal.success({ content: 'Duyệt kế hoạch sản xuất thành công' });
          }
        } catch (error) {
          Modal.error({ content: 'Có lỗi xảy ra khi duyệt kế hoạch sản xuất.' });
        } finally {
          setAcceptLoading(false);
        }
      },
    });
  };

  const handleReject = async () => {
    Modal.confirm({
      title: 'Từ chối kế hoạch sản xuất',
      content: 'Bạn có chắc chắn muốn từ chối kế hoạch sản xuất này?',
      onOk: async () => {
        try {
          setRejectLoading(true);
          const response = await manufacturingPlanService.rejectManufacturingPlan(Number(id));
          if (response.data) {
            loadData();
            Modal.success({ content: 'Từ chối kế hoạch sản xuất thành công' });
          }
        } catch (error) {
          Modal.error({ content: 'Có lỗi xảy ra khi từ chối kế hoạch sản xuất.' });
        } finally {
          setRejectLoading(false);
        }
      },
    });
  };

  const handleBeginManufacturing = async () => {
    Modal.confirm({
      title: 'Bắt đầu sản xuất',
      content: 'Bạn có chắc chắn muốn bắt đầu sản xuất kế hoạch này? Khi đã đồng ý, bạn sẽ chịu trách nhiệm cho quá trình sản xuất.',
      onOk: async () => {
        setLoading(true);
        try {
          const response = await manufacturingPlanService.beginManufacturing(Number(id));
          if (response.data) {
            loadData();
            Modal.success({ content: 'Bắt đầu sản xuất thành công' });
          }
        } catch (error) {
          Modal.error({ content: 'Có lỗi xảy ra khi bắt đầu sản xuất.' });
        } finally {
          setLoading(false);
        }
      },
    })
  }

  const handleFishnishManufacturing = async () => { 
    Modal.confirm({
      title: 'Hoàn thành sản xuất',
      content: 'Bạn có chắc chắn muốn xác nhận hoàn thành sản xuất cho kế hoạch này? Xác nhận hoàn thành sẽ kết thúc quá trình sản xuất và không thể thay đổi.',
      onOk: async () => {
        setLoading(true);
        try {
          const response = await manufacturingPlanService.finishManufacturing(Number(id));
          if (response.data) {
            loadData();
            Modal.success({ content: 'Hoàn thành sản xuất thành công' });
          }
        } catch (error) {
          Modal.error({ content: 'Có lỗi xảy ra khi hoàn thành sản xuất.' });
        } finally {
          setLoading(false);
        }
      },
    })
  };

  if (!manufacturingPlan) {
    return (
      <div className="text-center text-5xl mt-96 text-gray-600 animate-pulse">
        <LoadingOutlined className="text-blue-500" />
      </div>
    );
  }

    const handleGoCreate = () => {
        Modal.confirm({
          title: 'Xác nhận chuyển hướng',
          content: (
            <>
                Bạn có chắc chắn muốn tạo đề xuất xuất kho nguyên vật liệu từ kế hoạch sản xuất này? Vui lòng chọn đúng <b>{manufacturingPlan.name}</b> ở trang tạo phiếu xuất.
            </>
        ),
            onOk: async () => {
                setLoading(true);
                try {
                    navigate(`/manager-export`);
                } catch (error) {
                    Modal.error({ content: 'Có lỗi xảy ra khi tạo đề xuất xuất nguyên vật liệu.' });
                } finally {
                    setLoading(false);
                }
            },
        })
  }
      const handleGoCreateImport = () => {
        Modal.confirm({
          title: 'Xác nhận chuyển hướng',
          content: (
            <>
                Bạn có chắc chắn muốn tạo đề xuất nhập kho thành phẩm từ kế hoạch sản xuất này? Vui lòng chọn đúng <b>{manufacturingPlan.name}</b> ở trang tạo phiếu nhập.
            </>
          ),
            onOk: async () => {
                setLoading(true);
                try {
                    navigate(`/manager-product-import`);
                } catch (error) {
                    Modal.error({ content: 'Có lỗi xảy ra khi tạo phiếu nhập kho thành phẩm.' });
                } finally {
                    setLoading(false);
                }
            },
        })
    }
    const getMaterialSummary = () => {
  // Tạo một object để lưu trữ tổng số lượng nguyên vật liệu cho mỗi loại
  const materialSummary: Record<string, { material_name: string, total_quantity: number }> = {};

  manufacturingPlan?.manufacturing_plan_details.forEach(detail => {
    if (detail.material_name) {
      // Nếu nguyên vật liệu đã có trong object, cộng dồn số lượng
      if (materialSummary[detail.material_name]) {
        materialSummary[detail.material_name].total_quantity += detail.material_quantity;
      } else {
        // Nếu chưa có, tạo mới một entry cho nguyên vật liệu đó
        materialSummary[detail.material_name] = {
          material_name: detail.material_name,
          total_quantity: detail.material_quantity,
        };
      }
    }
  });

  // Chuyển object thành một array để render
  return Object.values(materialSummary);
};

  return (

    <div className="flex w-full justify-center bg-slate-300" style={{ height: 'calc(85vh)' }}>
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content">
        <div className="space-y-4">
        <Card
            title="Thông tin kế hoạch sản xuất"
            extra={
            <>
                {manufacturingPlan.status === 1 && userInfor.role_id === 3 && (
                <Space>
                    <Button
                    type="primary"
                    icon={<CheckOutlined />}
                    onClick={handleApprove}
                    loading={acceptLoading}
                    >
                    Duyệt
                    </Button>
                    <Button
                    danger
                    icon={<CloseOutlined />}
                    onClick={handleReject}
                    loading={rejectLoading}
                    >
                    Từ chối
                    </Button>
                </Space>
                )}
            </>
            }
        >
            <Descriptions column={1}>
              <Descriptions.Item label="Mã kế hoạch">{manufacturingPlan.id}</Descriptions.Item>
              <Descriptions.Item label="Tên kế hoạch sản xuất">{manufacturingPlan.name}</Descriptions.Item>
              <Descriptions.Item label="Trạng thái">
                  <span
                  className={`${
                      manufacturingPlan.status === 0
                      ? 'text-red-500'
                      : manufacturingPlan.status === 1
                      ? 'text-yellow-500'
                      : manufacturingPlan.status === 2
                      ? 'text-green-500'
                      : ''
                  }`}
                  >
                  {getStatusText(manufacturingPlan.status)}
                  </span>
              </Descriptions.Item>
              <Descriptions.Item label="Mô tả" span={2}>
                  {manufacturingPlan.description}
              </Descriptions.Item>
              <Descriptions.Item label="Người tạo">{manufacturingPlan.created_by_name}</Descriptions.Item>

              {manufacturingPlan.status > 4 && (
              <>
                <Descriptions.Item label="Người bắt đầu sản xuất">
                  {manufacturingPlan.begin_manufacturing_by_name}
                </Descriptions.Item>
                <Descriptions.Item label="Ngày bắt đầu sản xuất">
                  {manufacturingPlan.start_date}
                </Descriptions.Item>
              </>
            )}

            {manufacturingPlan.status > 5 && (
              <>
                <Descriptions.Item label="Người hoàn thành sản xuất">
                  {manufacturingPlan.finish_manufacturing_by_name} {/* Có thể cần thay đổi tên nếu người khác kết thúc */}
                </Descriptions.Item>
                <Descriptions.Item label="Ngày hoàn thành sản xuất">
                  {manufacturingPlan.end_date}
                </Descriptions.Item>
              </>
            )}

          </Descriptions>
        </Card>

        <Card title="Chi tiết thành phẩm và nguyên vật liệu">
            <Table
            columns={columns}
            dataSource={manufacturingPlan.manufacturing_plan_details}
            rowKey={(record) => `${record.id}`}
            pagination={false}
            />
            </Card>
            <Card title="Tổng kết nguyên vật liệu cần xuất">
    <Table
        columns={[
        {
            title: 'Tên nguyên vật liệu',
            dataIndex: 'material_name',
            key: 'material_name',
        },
        {
            title: 'Tổng số lượng',
            dataIndex: 'total_quantity',
            key: 'total_quantity',
        },
        ]}
        dataSource={getMaterialSummary()}
        rowKey="material_name"
        pagination={false}
    />
    </Card>

                                {/* Nút Gửi ở dưới cùng */}
        {manufacturingPlan.status === 0 && userInfor.id === manufacturingPlan.created_by && (
            <>
            <Button
                type="primary"
                size='large'
                icon={<SendOutlined />}
                onClick={handleSend}
                loading={sendLoading}
                block
            >
                Gửi kế hoạch sản xuất
            </Button>
            <Button
                  danger
                  size='large'
                icon={<CloseOutlined />}
                onClick={handleDelete} // Add the delete button logic
                loading={deleteLoading}
                block
            >
                Xóa kế hoạch sản xuất
            </Button>
            </>
            )}
            
            {manufacturingPlan.status === 2 && userInfor.id === manufacturingPlan.created_by && (
                <Button
                type="primary"
                size='large'
                icon={<SendOutlined />}
                onClick={handleGoCreate}
                loading={loading}
                block
            >
                Tạo đề xuất xuất nguyên vật liệu
            </Button>
            )}

            {manufacturingPlan.status === 4 && (userInfor.role_id === 2 || userInfor.role_id === 4) && (
                <Button
                type="primary"
                size='large'
                icon={<SendOutlined />}
                onClick={handleBeginManufacturing}
                loading={loading}
                block
              >
                Bắt đầu sản xuất
              </Button>
            )}
            {manufacturingPlan.status === 5 && (userInfor.role_id === 2||userInfor.role_id === 4) && (
                <Button
                type="primary"
                size='large'
                icon={<SendOutlined />}
                onClick={handleFishnishManufacturing}
                loading={loading}
                block
              >
                Hoàn thành sản xuất
              </Button>
            )}
            {manufacturingPlan.status === 6 && userInfor.role_id === 4 && (
                <Button
                type="primary"
                size='large'
                icon={<SendOutlined />}
                onClick={handleGoCreateImport}
                loading={loading}
                block
              >
                Tạo đề xuất nhập kho thành phẩm
              </Button>
            )}
            </div>
            </div>    
        <Footer />
    </div>
    
  );
};
