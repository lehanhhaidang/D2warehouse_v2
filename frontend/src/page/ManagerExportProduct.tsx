import { Button, Form, Input, Modal, Select, Table, Tag } from 'antd';
import { Footer } from '../components/footer/Footer';
import { IUser, MaterialEntry } from '../common/interface';
import { useEffect, useState } from 'react';
import { STATUS_PROPOSE } from '../enum/constants';
import * as warehouse from '../service/warehouse.service';
import * as product from '../service/product.service';
import * as propose from '../service/propose.service';
import * as orderService from '../service/order.service'; // Giả sử bạn có service order
import { useNavigate } from 'react-router-dom';
import { showNotification } from '../utilities/notification';
import { tabTitle } from '../utilities/title';

export const ManagerExportProduct = () => {
  const [form] = Form.useForm();
  const navigate = useNavigate();
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const [materialEntries, setMaterialEntries] = useState<MaterialEntry[]>([
    { id: `${Date.now()}-${Math.random().toString(36).substring(2, 9)}` },
  ]);
  const [type, setType] = useState('');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [warehouseList, setWarehouseList] = useState<any[]>([]);
  const [materialList, setMaterialList] = useState<any[]>([]);
  const [proposeList, setProposeList] = useState<any[]>([]);
  const [orderList, setOrderList] = useState<any[]>([]); // Danh sách đơn hàng
  const [loading, setLoading] = useState(false);

  const addMaterialEntry = () => {
    setMaterialEntries([...materialEntries, { id: Date.now() }]);
  };

  const onFinish = async (values: any) => {
    setLoading(true);
    const details = materialEntries.map((entry, index) => ({
      product_id: values[`material_id_${index}`],
      unit: values[`unit_${index}`],
      quantity: values[`quantity_${index}`],
      note: values[`note_${index}`],
    }));

    const newPropose = {
      name: values.name,
      description: values.description,
      warehouse_id: values.warehouse_id,
      status: STATUS_PROPOSE.PENDING_SEND,
      type: values.type,
      details: details,
      order_id: values.order_id,
    };
    console.log(newPropose);
    const response = await propose.createPropose(newPropose);
    if (response.data) {
      loadPropose();
      form.resetFields();
      setMaterialEntries([{ id: Date.now() }]);
    }
    showNotification(response);
    setLoading(false);
  };

  const loadPropose = async () => {
    const response = await propose.getPropose();
    if (response.data) {
      setProposeList(
        userInfor?.role_id === 3
          ? response.data.data.filter(
              (propose: any) =>
                propose.type === 'DXXTP' &&
                propose.status !== STATUS_PROPOSE.PENDING_SEND
            )
          : response.data.data.filter(
              (propose: any) => propose.type === 'DXXTP'
            )
      );
    }
  };

  const loadDataWareHouse = async () => {
    const response = await warehouse.getAllWareHouse();
    if (response.data) {
      setWarehouseList(response.data.data);
    }
  };

  const loadMaterial = async () => {
    const response = await product.getProducts();
    if (response.data) {
      setMaterialList(response.data);
    }
  };

  const loadOrder = async () => {
    const response = await orderService.getAllOrder(); // Lấy danh sách đơn hàng
    if (response.data.data) {

      // Lọc chỉ lấy những đơn hàng có trạng thái = 2 (Đã duyệt)
      setOrderList(response.data.data.filter((order: any) => order.status === 2));
    }
  };

  useEffect(() => {
    loadDataWareHouse();
    loadMaterial();
    loadPropose();
    loadOrder(); // Lấy đơn hàng khi page load
  }, []);

  const handleOrderChange = (value: any) => {
    const selectedOrder = orderList.find((order) => order.id === value);
    if (selectedOrder && selectedOrder.details) {
      // Nếu có chi tiết đơn hàng, điền thông tin vào materialEntries
      const newMaterialEntries = selectedOrder.details.map((detail, index) => ({
        id: `${Date.now()}-${Math.random().toString(36).substring(2, 9)}`,
        material_id: detail.product_id,
        material_name: detail.product_name,
        unit: detail.unit,
        quantity: detail.quantity,
        note: `Thuộc ${selectedOrder.name}`,
      }));
      setMaterialEntries(newMaterialEntries);
    }
  };

  const handleRowClick = (record: any) => {
    userInfor?.role_id === 2
      ? navigate(`/detail-propose/${record.id}`)
      : navigate(`/manager-detail/${record.id}`);
  };

  const columns = [
    {
      title: 'ID',
      dataIndex: 'id',
      key: 'id',
    },
    {
      title: 'Tên phiếu',
      dataIndex: 'name',
      key: 'name',
    },
    {
      title: 'Trạng thái',
      dataIndex: 'status',
      key: 'status',
      render: (status: number) => {
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
      },
    },
    {
      title: 'Loại',
      dataIndex: 'type',
      key: 'type',
    },
    {
      title: 'Nhà kho',
      dataIndex: 'warehouse_name',
      key: 'warehouse_name',
    },
    {
      title: 'Mô tả',
      dataIndex: 'description',
      key: 'description',
    },
  ];

  return (
    tabTitle('D2W - Xuất Thành Phẩm'),
    <div
      className="flex w-full justify-center bg-slate-300"
      style={{ height: 'calc(85vh)' }}
    >
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content ">
        {userInfor?.role_id === 4 && (
          <div className="mb-4">
            <Button
              type="primary"
              size="large"
              className="text-white"
              onClick={() => {
                setMaterialEntries([{ id: Date.now() }]);
                form.resetFields();
                setIsModalOpen(true);
              }}
            >
              Tạo Đề Xuất
            </Button>
            <Modal
              footer={null}
              title={
                <div
                  style={{
                    textAlign: 'center',
                    color: 'red',
                    fontSize: '30px',
                  }}
                >
                  Thêm Đề Xuất
                </div>
              }
              open={isModalOpen}
              onCancel={() => setIsModalOpen(false)}
            >
              <Form
                form={form}
                name="product_form"
                onFinish={onFinish}
                initialValues={{ type: 'DXXTP' }}
                layout="vertical"
                className="w-full max-w-md"
              >
                <Form.Item
                  name="name"
                  label="Tên phiếu"
                  rules={[
                    { required: true, message: 'Hãy nhập tên phiếu!' },
                  ]}
                  initialValue={`Đề xuất xuất thành phẩm ${new Date().toLocaleDateString()}`}
                >
                  <Input />
                </Form.Item>

                <Form.Item
                  name="description"
                  label="Mô tả"
                  rules={[
                    { required: true, message: 'Hãy nhập mô tả!' },
                  ]}
                >
                  <Input.TextArea rows={4} />
                </Form.Item>

                <Form.Item
                  name="warehouse_id"
                  label="Nhà kho"
                  rules={[{ required: true, message: 'Hãy chọn kho!' }]}
                >
                  <Select>
                    {warehouseList.map((warehouse) => (
                      <Select.Option key={warehouse.id} value={warehouse.id}>
                        {warehouse.name}
                      </Select.Option>
                    ))}
                  </Select>
                </Form.Item>

                <Form.Item
                  name="type"
                  label="Loại"
                  rules={[{ required: true, message: 'Hãy chọn loại đề xuất!' }]}
                >
                  <Select onChange={(value) => setType(value)}>
                    <Select.Option value="DXXTP">
                      Đề xuất xuất thành phẩm
                    </Select.Option>
                  </Select>
                </Form.Item>

                {/* Dropdown chọn đơn hàng */}
                <Form.Item
                  name="order_id"
                  label="Đơn hàng"
                  rules={[{ required: true, message: 'Hãy chọn đơn hàng!' }]}
                >
                  <Select onChange={handleOrderChange}>
                    {orderList.map((order) => (
                      <Select.Option key={order.id} value={order.id}>
                        {order.name}
                      </Select.Option>
                    ))}
                  </Select>
                </Form.Item>

                {materialEntries.map((entry, index) => (
                  <div key={entry.id} className="border p-4 mb-4">
                    <Form.Item
                      name={`material_id_${index}`}
                      label="Thành phẩm"
                      initialValue={entry.material_id}
                      rules={[
                        { required: true, message: 'Hãy chọn thành phẩm!' },
                      ]}
                    >
                      <Select>
                        {materialList.map((material) => (
                          <Select.Option key={material.id} value={material.id} disabled>
                            {material.name}
                          </Select.Option>
                        ))}
                      </Select>
                    </Form.Item>

                    <Form.Item
                      name={`unit_${index}`}
                      label="Đơn vị"
                      initialValue={entry.unit}
                      rules={[{ required: true, message: 'Hãy nhập đơn vị tính!' }]}
                    >
                      <Input disabled />
                    </Form.Item>

                    <Form.Item
                      name={`quantity_${index}`}
                      label="Số lượng"
                      initialValue={entry.quantity}
                      rules={[{ required: true, message: 'Hãy nhập số lượng!' }]}

                    >
                      <Input disabled/>
                    </Form.Item>
                    <Form.Item name={`note_${index}`} label="Ghi chú" initialValue={entry.note}>

                      <Input disabled/>
                    </Form.Item>
                  </div>
                ))}

                {/* <Button
                  type="dashed"
                  onClick={addMaterialEntry}
                  className="w-full mb-4"
                >
                  + Thêm Thành phẩm
                </Button> */}

                <Form.Item>
                  <Button
                    type="primary"
                    htmlType="submit"
                    className="w-full"
                    size="large"
                    loading={loading}
                  >
                    Tạo đề xuất
                  </Button>
                </Form.Item>
              </Form>
            </Modal>
          </div>
        )}

        <Table
          columns={columns}
          dataSource={proposeList}
          rowKey="id"
          onRow={(record) => ({
            onClick: () => handleRowClick(record),
          })}
        />
      </div>
      <Footer />
    </div>
  );
};
