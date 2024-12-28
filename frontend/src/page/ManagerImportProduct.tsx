import { Button, Form, Input, Modal, Select, Table, Tag } from 'antd';
import { IUser, MaterialEntry } from '../common/interface';
import { useEffect, useState } from 'react';
import { STATUS_PROPOSE } from '../enum/constants';
import * as propose from '../service/propose.service';
import * as warehouse from '../service/warehouse.service';
import * as material from '../service/product.service';
import { Footer } from '../components/footer/Footer';
import { useNavigate } from 'react-router-dom';
import { showNotification } from '../utilities/notification';
import { tabTitle } from '../utilities/title';
import * as manufacturingPlanService from '../service/manufacturing-plan.service';
export const ManagerImportProduct = () => {
  const [form] = Form.useForm();
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const [materialEntries, setMaterialEntries] = useState<MaterialEntry[]>([
    { id: `${Date.now()}-${Math.random().toString(36).substring(2, 9)}` },
  ]);
  const navigate = useNavigate();
  const [type, setType] = useState('');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [proposeList, setProposeList] = useState<any[]>([]);
  const [warehouseList, setWarehouseList] = useState<any[]>([]);
  const [materialList, setMaterialList] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const [manufacturingPlans, setManufacturingPlans] = useState<any[]>([]);
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
      manufacturing_plan_id: values.manufacturing_plan_id,
    };
    const response = await propose.createPropose(newPropose);
    if (response.data) {
      form.resetFields();
      setMaterialEntries([{ id: Date.now() }]);
      loadPropose();
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
                propose.type === 'DXNTP' &&
                propose.status !== STATUS_PROPOSE.PENDING_SEND
            )
          : response.data.data.filter(
              (propose: any) => propose.type === 'DXNTP'
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
    const response = await material.getProducts();
    if (response.data) {
      setMaterialList(response.data);
    }
  };
  const loadManufacturingPlans = async () => {
    const response = await manufacturingPlanService.getAllManufacturingPlan(); // Giả sử bạn có một service như vậy
    if (response.data.data) {
      // Lọc kế hoạch có status = 2 (Đã duyệt)
      setManufacturingPlans(response.data.data.filter((plan: any) => plan.status === 6));
    }
  };

  const handleManufacturingPlanChange = (value) => {
  // Tìm kế hoạch sản xuất đã chọn trong danh sách manufacturingPlans
  const selectedPlan = manufacturingPlans.find((plan) => plan.id === value);

  if (selectedPlan && selectedPlan.manufacturing_plan_details) {
    // Lấy thông tin nguyên vật liệu từ manufacturing_plan_details
    const newMaterialEntries = selectedPlan.manufacturing_plan_details.map((detail, index) => ({
      id: `${Date.now()}-${Math.random().toString(36).substring(2, 9)}`,  // tạo id mới cho mỗi entry
      material_id: detail.product_id,
      material_name: detail.product_name,
      unit: detail.product_unit,
      quantity: detail.product_quantity,
      note: `Thuộc ${selectedPlan.name}`,
    }));

    // Cập nhật lại state materialEntries
    setMaterialEntries(newMaterialEntries);
  }
};
  const addMaterialEntry = () => {
    setMaterialEntries([...materialEntries, { id: Date.now() }]);
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
  const handleRowClick = (record: any) => {
    userInfor?.role_id === 2 
      ? navigate(`/detail-propose/${record.id}`)
      : navigate(`/manager-detail/${record.id}`);
  };
  useEffect(() => {
    loadDataWareHouse();
    loadMaterial();
    loadPropose();
    loadManufacturingPlans();
  }, []);
  return (
    tabTitle("D2W - Nhập thành phẩm"),
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
                initialValues={{ type: 'DXNTP' }}
                layout="vertical"
                className="w-full max-w-md"
              >
                <Form.Item
                  name="name"
                  label="Tên phiếu"
                  rules={[
                    { required: true, message: 'Hãy nhập tên phiếu!' },
                    ]}
                    initialValue={`Đề xuất nhập thành phẩm ${new Date().toLocaleDateString()}`}
                >
                  <Input />
                </Form.Item>

                <Form.Item
                  name="description"
                  label="Mô tả"
                  rules={[
                    {
                      required: true,
                      message: 'Hãy nhập mô tả!',
                    },
                  ]}
                >
                  <Input.TextArea rows={4} />
                </Form.Item>

                <Form.Item
                  name="warehouse_id"
                  label="Nhà kho"
                  rules={[
                    { required: true, message: 'Hãy chọn kho!' },
                  ]}
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
                  rules={[
                    { required: true, message: 'Hãy chọn loại đề xuất!' },
                  ]}
                >
                  <Select onChange={(value) => setType(value)}>
                    <Select.Option value="DXNTP">
                      Đề xuất nhập thành phẩm
                    </Select.Option>
                  </Select>
                </Form.Item>
                  <Form.Item
                  name="manufacturing_plan_id"
                  label="Chọn kế hoạch sản xuất"
                  rules={[{ required: true, message: 'Hãy chọn kế hoạch sản xuất!' }]}>
                  <Select onChange={handleManufacturingPlanChange}>
                    {manufacturingPlans.map((plan) => (
                      <Select.Option key={plan.id} value={plan.id}>
                        {plan.name}
                      </Select.Option>
                    ))}
                  </Select>
                </Form.Item>
                {materialEntries.map((entry, index) => (
                  <div key={entry.id} className="border p-4 mb-4">
                    <Form.Item
                      name={`material_id_${index}`}
                      label="Nguyên vật liệu"
                      initialValue={entry.material_id}  // Điền vào value mặc định từ material_id
                      rules={[{ required: true, message: 'Hãy chọn nguyên vật liệu!' }]}
                    >
                      <Select>
                        {materialList.map((material) => (
                          <Select.Option key={material.id} value={material.id}>
                            {material.name}
                          </Select.Option>
                        ))}
                      </Select>
                    </Form.Item>

                    <Form.Item
                      name={`unit_${index}`}
                      label="Đơn vị"
                      initialValue={entry.unit}  // Điền vào value mặc định từ unit
                      rules={[{ required: true, message: 'Hãy nhập đơn vị tính!' }]}
                    >
                      <Input />
                    </Form.Item>

                    <Form.Item
                      name={`quantity_${index}`}
                      label="Số lượng"
                      initialValue={entry.quantity}  // Điền vào value mặc định từ quantity
                      rules={[{ required: true, message: 'Hãy nhập số lượng!' }]}
                    >
                      <Input />
                    </Form.Item>
                    <Form.Item
                      name={`note_${index}`}
                      label="Ghi chú"
                      initialValue={entry.note}  // Điền vào value mặc định từ note
                    >
                      <Input />
                    </Form.Item>
                  </div>
                ))}

                <Button
                  type="dashed"
                  onClick={addMaterialEntry}
                  className="w-full mb-4"
                >
                  + Thêm Thành phẩm
                </Button>

                <Form.Item>
                  <Button
                    type="primary"
                    htmlType="submit"
                    className="w-full"
                    size="large"
                    style={{ fontWeight: 'bold', fontSize: '20px' }}
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
