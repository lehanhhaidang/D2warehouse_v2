/* eslint-disable @typescript-eslint/no-explicit-any */
import {
  Button,
  Divider,
  Form,
  FormProps,
  Input,
  InputNumber,
  Modal,
  Space,
  Table,
} from 'antd';
import './style.css';
import { Select } from 'antd';
import { useEffect, useState, useMemo } from 'react';
import * as shelves from '../service/shelve.service';
import * as categories from '../service/categories.service';
import * as warehouse from '../service/warehouse.service';
import { Footer } from '../components/footer/Footer';
import { showNotification } from '../utilities/notification';
import { tabTitle } from '../utilities/title';
const { Option } = Select;
const { Search } = Input;
type FieldType = {
  name: string;
  storage_capacity: number;
  number_of_levels: number;
  warehouse_id: number;
  category_id: number;
};
export const AdminShelf = () => {
  const [name, setName] = useState<string>('');
  const [category, setCategory] = useState<string>('');
  const [warehouseId, setWarehouseId] = useState<string>('');
  const [status, setStatus] = useState<number | undefined>(undefined);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [shelf, setShelf] = useState<any[]>([]);
  const [selectedId, setSelectedId] = useState<number | 0>(0);
  const [categoriesList, setCategoriesList] = useState<any[]>([]);
  const [warehouseList, setWarehouseList] = useState<any[]>([]);
  const [numberLevels, setNumberLevels] = useState(0);
  const [storageCapacity, setStorageCapacity] = useState(0);
  const [sortBy, setSortBy] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [createLoading, setCreateLoading] = useState(false);
  const [updateLoading, setUpdateLoading] = useState(false);
  const [deleteLoading, setDeleteLoading] = useState(false);
  const columns = [
    { title: 'ID', dataIndex: 'id', key: 'id' },
    { title: 'Tên kệ', dataIndex: 'name', key: 'name' },
    { title: 'Danh mục', dataIndex: 'category_name', key: 'category_name' },
    { title: 'Kho', dataIndex: 'warehouse_name', key: 'warehouse_name' },
    {
      title: 'Số tầng',
      dataIndex: 'number_of_levels',
      key: 'number_of_levels',
    },
    {
      title: 'Dung tích',
      dataIndex: 'storage_capacity',
      key: 'storage_capacity',
    },

    {
      title: 'Trạng thái',
      dataIndex: 'status',
      key: 'status',
      render: (status: any) => (status === 1 ?'Không kích hoạt'  : 'Kích hoạt'),
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

  const isEditDisabled =
    !name || !warehouseId || !category || status === undefined;
  const loadCategory = async () => {
    const response = await categories.getAllCategory();
    if (response.data) {
      setCategoriesList(response.data);
    }
  };
  const loadWareHouse = async () => {
    const response = await warehouse.getAllWareHouse();
    if (response.data) {
      setWarehouseList(response.data.data);
    }
  };
  const loadData = async () => {
    try {
      const response = await shelves.getShevles();
      setShelf(response.data);
    } catch (error) {
      console.error('Failed to load shelves data', error);
    }
  };

  useEffect(() => {
    loadWareHouse();
    loadCategory();
    loadData();
  }, []);
  const onRowClick = (record: any) => {
    setName(record.name);
    setWarehouseId(record.warehouse_id);
    setCategory(record.category_id);
    setStatus(record.status === 'Kích hoạt' ? 1 : 0);
    setSelectedId(record.id);
    setStorageCapacity(record.storage_capacity);
    setNumberLevels(record.number_of_levels);
  };
  const handleEdit = async () => {
    setUpdateLoading(true);
    const response = await shelves.updateShelve(
      selectedId,
      name,
      numberLevels,
      storageCapacity,
      warehouseId,
      category
    );
    if (response.data) {
      loadData();
      setName('');
      setWarehouseId('');
      setCategory('');
      setStatus(undefined);
      setSelectedId(0);
      setNumberLevels(0);
      setStorageCapacity(0);
    }
    setUpdateLoading(false);
    showNotification(response);
  };

  const handleDelete = async () => {
    setDeleteLoading(true);
    const reponse = await shelves.deletShelve(selectedId);
    if (reponse.data) {
      setShelf(shelf.filter((item) => item.id !== selectedId));
      setName('');
      setWarehouseId('');
      setCategory('');
      setStatus(undefined);
      setSelectedId(0);
      setNumberLevels(0);
      setStorageCapacity(0);
    }
    setDeleteLoading(false);
    showNotification(reponse);
  };
  const onFinish: FormProps<FieldType>['onFinish'] = async (values) => {
    setCreateLoading(true);
    const response = await shelves.createShelve(
      values.name,
      values.warehouse_id,
      values.number_of_levels,
      values.storage_capacity,
      values.category_id
    );
    showNotification(response);
    if (response.data) {
      loadData();
      setIsModalOpen(false);
    }
    setCreateLoading(false);
  };
  const onFinishFailed: FormProps<FieldType>['onFinishFailed'] = (
    errorInfo
  ) => {};
  const handleSortChange = (e) => {
    setSortBy(e.target.value);
  };

  // Lọc và sắp xếp sản phẩm
  const filteredShelf = useMemo(() => {
    let filtered = [...shelf];

    // Lọc sản phẩm dựa trên searchTerm
    if (searchTerm) {
      filtered = filtered.filter(
        (shelf) =>
          shelf.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
          shelf.category_name
            .toLowerCase()
            .includes(searchTerm.toLowerCase()) ||
          shelf.warehouse_name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Sắp xếp sản phẩm
    if (sortBy === 'name') {
      filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === 'name_desc') {
      filtered.sort((a, b) => b.name.localeCompare(a.name));
    }
    if (sortBy === 'id') {
      filtered.sort((a, b) => a.id - b.id);
    }
    if (sortBy === 'id_desc') {
      filtered.sort((a, b) => b.id - a.id);
    }

    return filtered;
  }, [shelf, searchTerm, sortBy]);
  return (
    tabTitle('D2W - Quản lý kệ'),
    <div
      className="flex w-full justify-center"
      style={{ height: 'calc(85vh)' }}
    >
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content">
        <div className="mb-4">
          <Button
            type="primary"
            size="large"
            className="text-white"
            onClick={showModal}
          >
            Thêm kệ
          </Button>
          <div className="mt-4 flex items-center space-x-5">
            {/* Input tìm kiếm */}
            <Search
              placeholder="Tìm kiếm sản phẩm"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              allowClear
              enterButton
              style={{ width: '300px', height: '40px', marginTop: '12px' }}
            />

            {/* Select sắp xếp */}
            <Select
              value={sortBy}
              onChange={setSortBy}
              placeholder="Sắp xếp theo"
              style={{ width: '200px', height: '40px', marginTop: '10px' }}
            >
              <Option value="">Sắp xếp theo</Option>
              <Option value="name">Tên từ A-Z</Option>
              <Option value="name_desc">Tên từ Z-A</Option>
              <Option value="id">ID tăng dần</Option>
              <Option value="id_desc">ID giảm dần</Option>
            </Select>
          </div>
          <Modal
            footer={null}
            title="Thêm kệ"
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
              autoComplete="off"
              onFinish={onFinish}
              onFinishFailed={onFinishFailed}
            >
              <Form.Item
                label="Tên kệ"
                name="name"
                rules={[{ required: true, message: 'Hãy nhập tên kệ!' }]}
              >
                <Input />
              </Form.Item>
              <Form.Item
                name={'category_id'}
                label="Danh mục"
                rules={[{ required: true, message: 'Hãy chọn danh mục' }]}
              >
                <Select
                  placeholder="Chọn danh mục"
                  value={category}
                  onChange={setCategory}
                >
                  {categoriesList.map((category) => (
                    <Option key={category.id} value={category.id}>
                      {category.name}
                    </Option>
                  ))}
                </Select>
              </Form.Item>
              <Form.Item
                name={'warehouse_id'}
                label="Nhà kho"
                rules={[{ required: true, message: 'Hãy chọn nhà kho' }]}
              >
                <Select
                  placeholder="Chọn kho hàng"
                  value={warehouseId}
                  onChange={setWarehouseId}
                >
                  {warehouseList.map((w) => (
                    <Option
                      key={w.id || `warehouse_${Math.random()}`}
                      value={w.id}
                    >
                      {w.name}
                    </Option>
                  ))}
                </Select>
              </Form.Item>
              <Form.Item
                name={'number_of_levels'}
                label="Số tầng"
                rules={[{ required: true, message: 'Tầng là bắt buộc' }]}
              >
                <InputNumber min={1} />
              </Form.Item>

              <Form.Item
                label="Dung lượng"
                name="storage_capacity"
                rules={[{ required: true, message: 'Hãy nhập dung lượng!' }]}
              >
                <InputNumber min={1} />
              </Form.Item>
              <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={createLoading}
                >
                  Thêm kệ
                </Button>
              </Form.Item>
            </Form>
          </Modal>
        </div>

        <div className="flex flex-row justify-between responsive-container">
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Tên kệ</p>
            <Divider />
            <input
              className="focus:outline-none"
              value={name}
              onChange={(e) => setName(e.target.value)}
            />
          </div>

          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Kho</p>
            <Divider />
            <Select
              style={{ width: 160 }}
              placeholder="Chọn kho hàng"
              value={warehouseId}
              onChange={setWarehouseId}
            >
              {warehouseList.map((w) => (
                <Option key={w.id || `warehouse_${Math.random()}`} value={w.id}>
                  {w.name}
                </Option>
              ))}
            </Select>
          </div>

          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Danh mục</p>
            <Divider />

            <Select
              style={{ width: 140 }}
              value={category}
              onChange={(value) => setCategory(value)}
            >
              {categoriesList.map((category) => (
                <Option key={category.id} value={category.id}>
                  {category.name}
                </Option>
              ))}
            </Select>
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Số tầng</p>
            <Divider />
            <input
              min={1}
              type="number"
              className="focus:outline-none"
              value={numberLevels}
              onChange={(e) => setNumberLevels(Number(e.target.value))}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Dung tích</p>
            <Divider />
            <input
              min={1}
              type="number"
              className="focus:outline-none"
              value={storageCapacity}
              onChange={(e) => setStorageCapacity(Number(e.target.value))}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Trạng thái</p>
            <Divider />
            <Select
              style={{ width: 140 }}
              value={status}
              onChange={setStatus}
              options={[
                { value: 1, label: 'Kích hoạt'},
                { value: 0, label: 'Không kích hoạt' },
              ]}
            />
          </div>

          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Tùy chọn</p>
            <Divider />
            <Space>
              <Button
                type="primary"
                disabled={isEditDisabled}
                onClick={handleEdit}
                loading={updateLoading}
              >
                Sửa
              </Button>
              <Button
                className="bg-red-500 text-white"
                disabled={isEditDisabled}
                onClick={handleDelete}
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
            dataSource={filteredShelf}
            columns={columns}
            rowKey="id"
            pagination={{ pageSize: 5 }}
            onRow={(record) => ({
              onClick: () => onRowClick(record),
            })}
          />
        </div>
      </div>
      <Footer />
    </div>
  );
};
