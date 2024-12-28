/* eslint-disable @typescript-eslint/no-explicit-any */
import {
  Button,
  Divider,
  Form,
  FormProps,
  Input,
  message,
  Modal,
  Space,
  Table,
} from 'antd';
import './style.css';
import { Select } from 'antd';
import { useEffect, useState, useMemo } from 'react';
import * as userService from '../service/user.service';
import * as helper from '../utilities/helper';
import * as warehouseService from '../service/warehouse.service';
import { Footer } from '../components/footer/Footer';
import { showNotification } from "../utilities/notification";
import { tabTitle } from "../utilities/title";
type FieldType = {
  username: string;
  password: string;
  email: string;
  role_id: number;
  phone: number;
  warehouse_ids: number[];
};
const { Option } = Select;
export const AdminAccount = () => {
  const [userName, setUserName] = useState<string>('');
  const [email, setEmail] = useState<string>('');
  const [phone, setPhone] = useState<string>('');
  const [role, setRole] = useState<string>('');
  const [status, setStatus] = useState<number | undefined>(undefined);
  const [warehouse, setWarehouse] = useState<number[]>([]);
  const [warehousesList, setWarehousesList] = useState<any[]>([]);
  const [id, setId] = useState<number>(0);
  const [initialValues, setInitialValues] = useState<any>(null);
  const [users, setUsers] = useState<any>([]);
  const [sortBy, setSortBy] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [createLoading, setCreateLoading] = useState(false);
  const [updateLoading, setUpdateLoading] = useState(false);
  const [deleteLoading, setDeleteLoading] = useState(false);
  const columns = [
    {
      title: 'ID',
      dataIndex: 'id',
      key: 'id',
    },
    {
      title: 'Tên người dùng',
      dataIndex: 'name',
      key: 'name',
    },
    {
      title: 'Email',
      dataIndex: 'email',
      key: 'email',
    },
    {
      title: 'Số điện thoại',
      dataIndex: 'phone',
      key: 'phone',
    },
    {
      title: 'Chức vụ',
      dataIndex: 'role_name',
      key: 'role_name',
    },
    {
      title: 'Trạng thái',
      dataIndex: 'status',
      key: 'status',
      render: (status: any) => (status === 1 ? 'Kích hoạt' : 'Không kích hoạt'),
    },
    {
      title: 'Ngày tạo',
      dataIndex: 'created_at',
      key: 'created_at',
      render: (date: any) => new Date(date).toLocaleDateString(),
    },
  ];
  const showModal = () => {
    setIsModalOpen(true);
  };

  const handleOk = () => {
    setIsModalOpen(false);
  };

  const handleCancel = () => {
    setIsModalOpen(false);
  };
  const handleRowClick = (record: any) => {
    setUserName(record.name);
    setEmail(record.email);
    setPhone(record.phone);
    setRole(record.role_id);
    setStatus(record.status);
    setId(record.id);

    // Chuyển kho thành mảng chỉ chứa ID kho
    const warehouseIds = record.warehouses.map(
      (warehouse: any) => warehouse.id
    );

    setInitialValues({
      name: record.name,
      email: record.email,
      phone: record.phone,
      role_name: record.role_name,
      status: record.status,
      id: record.id,
      warehouse_ids: warehouseIds,
    });

    setWarehouse(warehouseIds);
  };

  const handleEdit = async (isUpdate: boolean) => {
    if (isUpdate) {
      setUpdateLoading(true);
      if (
        userName === initialValues?.name &&
        email === initialValues?.email &&
        phone === initialValues?.phone &&
        role === initialValues?.role_id &&
        status === initialValues?.status &&
        JSON.stringify(warehouse.sort()) ===
          JSON.stringify(initialValues?.warehouse_ids.sort())
      ) {
        message.success('Không có thay đổi nào!');
      } else {
        const response = await userService.updateUser(
          id,
          userName,
          email,
          phone,
          role,
          warehouse
        );
        showNotification(response);
        setUpdateLoading(false);
      }
    }

    if (!isUpdate) {
      setDeleteLoading(true);
      const response = await userService.deleteUser(id);
      if (response.data) {
        setUsers(users.filter((user: any) => user.id !== id));
        setEmail('');
        setPhone('');
        setRole('');
        setStatus(undefined);
        setId(0);
        setUserName('');
        setWarehouse([]);
        showNotification(response);
        setDeleteLoading(false);
      }
    }
  };

  const onFinish: FormProps<FieldType>['onFinish'] = async (values) => {
    try {
      setCreateLoading(true);
      // Kiểm tra nếu values.warehouses tồn tại và là một mảng
      const warehouse_ids = Array.isArray(values.warehouse_ids)
        ? values.warehouse_ids.map((id: number) => parseInt(id))
        : [];

      const response = await userService.createUser(
        values.username,
        values.email,
        values.password,
        values.role_id,
        values.phone,
        warehouse_ids
      );
      if (response.data) {
        setUsers([
          ...users,
          {
            name: values.username,
            email: values.email,
            phone: values.phone,
            role_name: helper.getRoleUser(values.role_id),
            status: 1,
            created_at: new Date(),
          },
        ]);
        setCreateLoading(false);
        setIsModalOpen(false);
      }
      showNotification(response);
    } catch (error) {
      setCreateLoading(false);
    } finally {
      setCreateLoading(false);
    }
    loadData();
  };

  const onFinishFailed: FormProps<FieldType>['onFinishFailed'] = (
    errorInfo
  ) => {};
  const isEditDisabled =
    !userName || !email || !phone || !role || status === undefined;
  const loadWarehousesList = async () => {
    const response = await warehouseService.getAllWareHouse();
    setWarehousesList(response.data.data);
  };
  const loadData = async () => {
    const response = await userService.getAllUser();
    setUsers(response.data);
  };

  useEffect(() => {
    loadData();
    loadWarehousesList();
  }, []);

  const handleSortChange = (e) => {
    setSortBy(e.target.value);
  };

  // Lọc và sắp xếp nvl
  const filteredUser = useMemo(() => {
    let filtered = [...users];

    // Lọc nvl dựa trên searchTerm
    if (searchTerm) {
      filtered = filtered.filter(
        (user) =>
          user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
          user.phone.toLowerCase().includes(searchTerm.toLowerCase()) ||
          user.role_name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Sắp xếp
    if (sortBy === 'name') {
      filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === 'name_desc') {
      filtered.sort((a, b) => b.name.localeCompare(a.name));
    }
    // else if (sortBy === "quantity") {
    //     filtered.sort((a, b) => a.quantity - b.quantity);
    // } else if (sortBy === "quantity_desc") {
    //   filtered.sort((a, b) => b.quantity - a.quantity);
    // }
    return filtered;
  }, [users, searchTerm, sortBy]);

  return (
    tabTitle('D2W - Quản lý tài khoản'),
    <div
      className="flex w-full justify-center"
      style={{ height: 'calc(85vh)' }}
    >
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content ">
        <div className="mb-4">
          <Button
            type="primary"
            size="large"
            className="text-white"
            onClick={showModal}
          >
            Thêm tài khoản
          </Button>
          <div style={{ marginBottom: '20px' }}>
            {/* Input tìm kiếm */}
            <Input
              placeholder="Tìm kiếm tài khoản"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              style={{
                width: '200px',
                height: '40px',
                marginTop: '30px',
                marginRight: '20px',
              }}
            />

            {/* Select sắp xếp */}
            <Select
              value={sortBy}
              onChange={setSortBy}
              placeholder="Sắp xếp theo"
              style={{ width: '200px', height: '40px', marginTop: '30px' }}
            >
              <Option value="">Sắp xếp theo</Option>
              <Option value="name">Tên từ A-Z</Option>
              <Option value="name_desc">Tên từ Z-A</Option>
            </Select>
          </div>
          <Modal
            footer={null}
            title="Thêm tài khoản"
            open={isModalOpen}
            onOk={handleOk}
            onCancel={handleCancel}
          >
            <Form
              name="basic"
              labelCol={{ span: 8 }}
              wrapperCol={{ span: 16 }}
              style={{ maxWidth: 600 }}
              initialValues={{ remember: true }}
              onFinish={onFinish}
              onFinishFailed={onFinishFailed}
              autoComplete="off"
            >
              <Form.Item<FieldType>
                label="Username"
                name="username"
                rules={[
                  { required: true, message: 'Vui lòng nhập tên!' },
                ]}
              >
                <Input />
              </Form.Item>

              <Form.Item<FieldType>
                label="Phone"
                name="phone"
                rules={[
                  { required: true, message: 'Vui lòng nhập số điện thoại!' },
                ]}
              >
                <Input />
              </Form.Item>

              <Form.Item<FieldType>
                label="Email"
                name="email"
                rules={[
                  {
                    required: true,
                    message: 'Vui lòng nhập email!',
                    type: 'email',
                  },
                ]}
              >
                <Input />
              </Form.Item>

              <Form.Item<FieldType>
                label="Password"
                name="password"
                rules={[
                  {
                    required: true,
                    message: 'Vui lòng nhập mật khẩu!',
                    min: 6,
                  },
                ]}
              >
                <Input.Password />
              </Form.Item>
              <Form.Item<FieldType>
                name={'role_id'}
                label="Role"
                rules={[{ required: true, message: 'Vui lòng chọn vai trò' }]}
              >
                <Select placeholder="Select role">
                  <Option value="1">Admin</Option>
                  <Option value="2">Quản lý kho</Option>
                  <Option value="3">Giám đốc</Option>
                  <Option value="4">Nhân viên kho</Option>
                </Select>
              </Form.Item>

              <Form.Item<FieldType>
                name={'warehouse_ids'}
                label="Kho phụ trách"
                rules={[
                  { required: true, message: 'Vui lòng chọn ít nhất một kho!' },
                ]}
              >
                <Select
                  mode="multiple"
                  placeholder="Select warehouses"
                  value={warehouse}
                  onChange={setWarehouse} // Cập nhật giá trị của mảng warehouses
                  options={warehousesList.map((warehouse) => ({
                    value: warehouse.id,
                    label: warehouse.name,
                  }))}
                />
              </Form.Item>

              <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={createLoading}
                >
                  Thêm tài khoản
                </Button>
              </Form.Item>
            </Form>
          </Modal>
        </div>
        <div className="flex flex-row justify-between responsive-container">
          <div className="flex flex-col border-2 items-center p-3 rounded-sm responsive-item">
            <p>Tên người dùng</p>
            <Divider />
            <input
              className="focus:outline-none"
              value={userName}
              onChange={(e) => setUserName(e.target.value)}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-3 rounded-sm responsive-item">
            <p>Email</p>
            <Divider />
            <input
              className="focus:outline-none"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-3 rounded-sm responsive-item">
            <p>Số điện thoại</p>
            <Divider />
            <input
              className="focus:outline-none"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-3 rounded-sm responsive-item">
            <p>Chức vụ</p>
            <Divider />
            <Select
              style={{ width: 120 }}
              value={role}
              onChange={setRole}
              options={[
                { value: '1', label: 'Admin' },
                { value: '2', label: 'Quản lý kho' },
                { value: '3', label: 'Giám đốc' },
                { value: '4', label: 'Nhân viên kho' },
              ]}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-3 rounded-sm responsive-item">
            <p>Trạng thái</p>
            <Divider />
            <Select
              style={{ width: 120 }}
              value={status}
              onChange={setStatus}
              options={[
                { value: 1, label: 'Kích hoạt' },
                { value: 0, label: 'Không kích hoạt' },
              ]}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-3 rounded-sm responsive-item">
            <p>Kho</p>
            <Divider />
            <Select
              mode="multiple" // Cho phép chọn nhiều kho
              placeholder="Chọn kho"
              value={warehouse} // Hiển thị danh sách kho hiện tại (mảng kho được chọn)
              onChange={(value: number[]) => setWarehouse(value)} // Cập nhật lại kho khi người dùng thay đổi lựa chọn
              options={warehousesList.map((warehouse) => ({
                value: warehouse.id,
                label: warehouse.name,
              }))}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-3 rounded-sm responsive-item">
            <p>Tùy chọn</p>
            <Divider />
            <Space>
              <Button
                type="primary"
                onClick={() => handleEdit(true)}
                disabled={isEditDisabled}
                loading={updateLoading}
              >
                Sửa
              </Button>
              <Button
                className="bg-red-500 text-white"
                onClick={() => handleEdit(false)}
                disabled={isEditDisabled}
                loading={deleteLoading}
              >
                Xóa
              </Button>
            </Space>
          </div>
        </div>
        <Divider />
        <div className="responsive-table">
          <Table
            dataSource={filteredUser}
            columns={columns}
            rowKey="id"
            onRow={(record) => ({
              onClick: () => handleRowClick(record),
            })}
          />
        </div>
      </div>
      <Footer />
    </div>
  );
};
