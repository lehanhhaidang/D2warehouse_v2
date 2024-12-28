import { Input, Select, Form, Button, Modal, Table, Tag } from 'antd';
import { useEffect, useState } from 'react';
import { STATUS_PROPOSE } from '../enum/constants';
import * as warehouse from '../service/warehouse.service';
import * as material from '../service/material.service';
import * as propose from '../service/propose.service';
import { IUser, MaterialEntry } from '../common/interface';
import { useNavigate } from 'react-router-dom';
import { Footer } from '../components/footer/Footer';
import { showNotification } from '../utilities/notification';
import { tabTitle } from '../utilities/title';

export const ManagerImportMaterial = () => {
  const [form] = Form.useForm();
  const [type, setType] = useState('');
  const [materialEntries, setMaterialEntries] = useState<MaterialEntry[]>([
    { id: `${Date.now()}-${Math.random().toString(36).substring(2, 9)}` },
  ]);
  const [warehouseList, setWarehouseList] = useState<any[]>([]);
  const [materialList, setMaterialList] = useState<any[]>([]);
  const [proposeList, setProposeList] = useState<any[]>([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [employee, setEmployee] = useState<any[]>([]);
  const [selectedPropose, setSelectedPropose] = useState<any | null>(null);
  const navigate = useNavigate();
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const addMaterialEntry = () => {
    setMaterialEntries([...materialEntries, { id: Date.now() }]);
  };
  const [loading, setLoading] = useState(false);

  const onFinish = async (values: any) => {
    setLoading(true);
    const details = materialEntries.map((entry, index) => ({
      material_id: values[`material_id_${index}`],
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
      assigned_to: values.assigned_to,
    };
    const response = await propose.createPropose(newPropose);
    if (response.data) {
      form.resetFields();
      setMaterialEntries([{ id: Date.now() }]);
      loadPropose();
      setIsModalOpen(false);
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
                propose.type === 'DXNNVL' &&
                propose.status !== STATUS_PROPOSE.PENDING_SEND
            )
          : response.data.data.filter(
              (propose: any) => propose.type === 'DXNNVL'
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
    const response = await material.getMaterials();
    if (response.data) {
      setMaterialList(response.data);
    }
  };

  const loadEmployee = async (id: number) => {
    const response = await warehouse.loadEmpoyee(id);
    if (response.data) {
      setEmployee(response.data);
    }
  };
  useEffect(() => {
    loadDataWareHouse();
    loadMaterial();
    loadPropose();
  }, []);

  const handleRowClick = (record: any) => {
    userInfor?.role_id === 3
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
    tabTitle("D2W - Nhập nguyên vật liệu"),
    <div
      className="flex w-full justify-center bg-slate-300"
      style={{ height: 'calc(85vh)' }}
    >
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content ">
        {userInfor?.role_id != 3 && (
          <div className="mb-4">
            <Button
              type="primary"
              size="large"
              className="text-white"
              onClick={() => {
                setSelectedPropose(null);
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
                  {selectedPropose ? 'Gửi Đề Xuất' : 'Thêm Đề Xuất'}
                </div>
              }
              open={isModalOpen}
              onCancel={() => setIsModalOpen(false)}
            >
              <Form
                form={form}
                name="product_form"
                onFinish={onFinish}
                initialValues={selectedPropose || { type: 'DXNNVL' }}
                layout="vertical"
                className="w-full max-w-md"
              >
                <Form.Item
                  name="name"
                  label="Tên phiếu"
                  rules={[
                    { required: true, message: 'Hãy nhập tên phiếu!' },
                    ]}
                  initialValue={`Đề xuất nhập nguyên vật liệu ${new Date().toLocaleDateString()}`}
                >
                  <Input />
                </Form.Item>
                <Form.Item
                  name="warehouse_id"
                  label="Nhà kho"
                  rules={[
                    { required: true, message: 'Hãy chọn kho!' },
                  ]}
                >
                  <Select
                    onChange={(value) => {
                      loadEmployee(value);
                    }}
                  >
                    {warehouseList.map((warehouse) => (
                      <Select.Option key={warehouse.id} value={warehouse.id}>
                        {warehouse.name}
                      </Select.Option>
                    ))}
                  </Select>
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
                  name="assigned_to"
                  label="Nhân viên"
                  rules={[
                    {
                      required: true,
                      message: 'Hãy chọn nhân viên phụ trách!',
                    },
                  ]}
                >
                  <Select>
                    {employee?.map((warehouse) => (
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
                    <Select.Option value="DXNNVL">
                      Đề xuất nhập nguyên vật liệu
                    </Select.Option>
                  </Select>
                </Form.Item>

                {materialEntries.map((entry, index) => (
                  <div key={entry.id} className="border p-4 mb-4">
                    <Form.Item
                      name={`material_id_${index}`}
                      label="Nguyên vật liệu"
                      rules={[
                        {
                          required: true,
                          message: 'Hãy chọn tên nguyên vật liệu!',
                        },
                      ]}
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
                      rules={[
                        { required: true, message: 'Hãy nhập đơn vị tính!' },
                      ]}
                    >
                      <Input />
                    </Form.Item>

                    <Form.Item
                      name={`quantity_${index}`}
                      label="Số lượng"
                      rules={[
                        { required: true, message: 'Hãy nhập số lượng!' },
                      ]}
                    >
                      <Input />
                    </Form.Item>
                    <Form.Item
                      name={`note_${index}`}
                      label="Ghi chú"
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
                  + Thêm Nguyên Vật Liệu
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
