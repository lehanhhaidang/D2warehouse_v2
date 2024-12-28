/* eslint-disable @typescript-eslint/no-explicit-any */
import {
  Button,
  Divider,
  Form,
  FormProps,
  Input,
  InputNumber,
  message,
  Modal,
  Space,
  Table,
} from 'antd';
import './style.css';
import { useEffect, useState, useMemo} from 'react';
import * as warehouse from '../service/warehouse.service';
import * as category from '../service/categories.service';
import { Select} from 'antd';
import { Footer } from '../components/footer/Footer';
import { showNotification } from '../utilities/notification';
import { tabTitle } from '../utilities/title';
const { Option } = Select;


type FieldType = {
  name: string;
  location: string;
  acreage: number;
  number_of_shelves: number;
  category_id: number;
};
export const AdminStorage = () => {
  const [name, setName] = useState<string>('');
  const [location, setLocation] = useState<string>('');
  const [acreage, setAcreage] = useState<string>('');
  const [shelf, setShelf] = useState<string>('');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [storages, setStorages] = useState<any[]>([]);
  const [categoryName, setCategoryName] = useState<string>('');
  const [categoryId, setCategoryId] = useState<number>(0);
  const [initialValues, setInitialValues] = useState<any>(null);
  const [categoriesList, setCategoriesList] = useState<any[]>([]);
  const [sortBy, setSortBy] = useState("");
  const [searchTerm, setSearchTerm] = useState("");
  const [createLoading, setCreateLoading] = useState(false);
  const [updateLoading, setUpdateLoading] = useState(false);
  const [deleteLoading, setDeleteLoading] = useState(false);
  const columns = [
    { title: 'ID', dataIndex: 'id', key: 'id' },
    { title: 'Tên kho', dataIndex: 'name', key: 'name' },
    { title: 'Vị trí', dataIndex: 'location', key: 'location' },
    { title: 'Diện tích', dataIndex: 'acreage', key: 'acreage' },
    {
      title: 'Số kệ',
      dataIndex: 'number_of_shelves',
      key: 'number_of_shelves',
    },
    { title: 'Tên loại', dataIndex: 'category_name', key: 'category_name' },
    {
      title: 'Ngày tạo',
      dataIndex: 'created_at',
      key: 'created_at',
      render: (date: any) => new Date(date).toLocaleDateString(),
    },
  ];
  const handleRowClick = (record: any) => {
      setName(record.name);
      setLocation(record.location);
      setAcreage(record.acreage);
      setShelf(record.number_of_shelves);
      setCategoryName(record.category_name);
      setCategoryId(record.category_id);  // Ensure this is set correctly
      setInitialValues(record);  // Ensure record has all the fields required
  };

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
    !name || !location || !acreage || !shelf || status === undefined;
  const loadData = async () => {
    const response = await warehouse.getAllWareHouse();
    setStorages(response.data.data);
  };
const handleEdit = async (isEdit: boolean) => {
    if (isEdit) {
        setUpdateLoading(true);
        // Compare all fields, including categoryId
        if (
            name === initialValues?.name &&
            location === initialValues?.location &&
            acreage === initialValues?.acreage &&
            shelf === initialValues?.number_of_shelves &&
            categoryId === initialValues?.category_id // Compare categoryId, not categoryName
        ) {
            message.info('Không có thay đổi nào!');
            setUpdateLoading(false);
            return;
        } else {
            try {
                const response = await warehouse.updateWarehouse(
                    initialValues.id,
                    name,
                  location,
                    acreage,
                    shelf,
                    categoryId
                );
                if (response.data) {
                    setStorages(
                        storages.map((storage) =>
                            storage.id === initialValues.id ? response.data : storage
                        )
                    );

              }
              showNotification(response);
              loadData();
            } catch (error) {
                console.error(error);
                message.error('Lỗi khi cập nhật dữ liệu!');
            } finally {
                setUpdateLoading(false);
            }
        }
    } else {
        setDeleteLoading(true);
        try {
            const response = await warehouse.deleteWareHouse(initialValues.id);
            if (response.data) {
                setStorages(
                    storages.filter((storage) => storage.id !== initialValues.id)
                );
                setName('');
                setLocation('');
                setAcreage('');
                setShelf('');
                setCategoryName('');
                setInitialValues(null);
                showNotification(response);
            }
        } catch (error) {
            console.error(error);
            message.error('Lỗi khi xóa dữ liệu!');
        } finally {
            setDeleteLoading(false);
        }
    }
    setUpdateLoading(false);
    setDeleteLoading(false);
};

  const onFinish: FormProps<FieldType>['onFinish'] = async (values) => {
    setCreateLoading(true);
    const response = await warehouse.createWareHouse(values);
    if (response.data) {
      showNotification(response);
      setIsModalOpen(false);
      loadData();
    }
    setCreateLoading(false);
  };
  const loadCategory = async () => {
    const response = await category.getCategoriesParent();
    if (response.data) {
      setCategoriesList(response.data);
    }
  };

  useEffect(() => {
    loadData();
    loadCategory();
  }, []);
  const handleSortChange = (e) => {
    setSortBy(e.target.value);
  };

  // Lọc và sắp xếp nvl
  const filteredStorages = useMemo(() => {
    let filtered = [...storages];

    // Lọc nvl dựa trên searchTerm
    if (searchTerm) {
      filtered = filtered.filter((storage) =>
        storage.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      storage.location.toLowerCase().includes(searchTerm.toLowerCase()) ||
      storage.category_name.toLowerCase().includes(searchTerm.toLowerCase()) 
      );
    }

    // Sắp xếp 
    if (sortBy === "name") {
      filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === "name_desc") {
      filtered.sort((a, b) => b.name.localeCompare(a.name));
    }
    else if (sortBy === "acreage") {
      filtered.sort((a, b) => a.acreage- b.acreage);
    } else if (sortBy === "acreage_desc") {
      filtered.sort((a, b) => b.acreage - a.acreage);
    } else if (sortBy === "number_of_shelf") {
      filtered.sort((a, b) => a.number_of_shelves - b.number_of_shelves);
    } else if (sortBy === "number_of_shelf_desc") {
      filtered.sort((a, b) => b.number_of_shelves - a.number_of_shelves);
    }

    return filtered;
  }, [storages, searchTerm, sortBy]);
  return (
    tabTitle("D2W - Quản lý kho"),
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
            Thêm kho
          </Button>
          <div style={{ marginBottom: "20px" }}>
            {/* Input tìm kiếm */}
            <Input
              placeholder="Tìm kiếm kho"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              style={{
                width: "200px",
                height: "40px",
                marginTop: "30px",
                marginRight: "20px",
              }}
            />

            {/* Select sắp xếp */}
            <Select
              value={sortBy}
              onChange={setSortBy}
              placeholder="Sắp xếp theo"
              style={{ width: "200px", height: "40px", marginTop: "30px" }}
            >
              <Option value="">Sắp xếp theo</Option>
              <Option value="name">Tên từ A-Z</Option>
              <Option value="name_desc">Tên từ Z-A</Option>
              <Option value="acreage">Diện tích tăng dần</Option>
              <Option value="acreage_desc">Diện tích giảm dần</Option>
              <Option value="number_of_shelf">Số kệ tăng dần</Option>
              <Option value="number_of_shelf_desc">Số kệ giảm dần</Option>
            </Select>
          </div>
          <Modal
            footer={null}
            title="Thêm kho"
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
            >
              <Form.Item
                label="Tên kho"
                name="name"
                rules={[
                  { required: true, message: 'Please input storage name!' },
                ]}
              >
                <Input />
              </Form.Item>
              <Form.Item
                label="Vị trí"
                name="location"
                rules={[{ required: true, message: 'Please input location!' }]}
              >
                <Input />
              </Form.Item>
              <Form.Item
                label="Diện tích"
                name="acreage"
                rules={[{ required: true, message: 'Please input acreage!' }]}
              >
                <InputNumber min={1}/>
              </Form.Item>

              <Form.Item
                label="Số lượng kệ"
                name="number_of_shelves"
                rules={[
                  { required: true, message: 'Please input number of shelf!'} 
                ]}
              >
                <InputNumber min={1}/>
              </Form.Item>
              <Form.Item<FieldType>
                name={'category_id'}
                label="Tên loại"
                rules={[{ required: true, message: 'Category is required' }]}
              >
                <Select
                  style={{ width: 140 }}
                  value={categoryId}
                  onChange={(value) => setCategoryId(value)}
                >
                  {categoriesList.map((category) => (
                    <Option key={category.id} value={category.id}>
                      {category.name}
                    </Option>
                  ))}
                </Select>
              </Form.Item>
              <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                <Button type="primary" htmlType="submit" loading={createLoading}>
                  Thêm kho
                </Button>
              </Form.Item>
            </Form>
          </Modal>
        </div>
        <div className="flex flex-row justify-between responsive-container">
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Tên kho</p>
            <Divider />
            <input
              className="focus:outline-none"
              value={name}
              onChange={(e) => setName(e.target.value)}
            />
          </div>

          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Vị trí</p>
            <Divider />
            <input
              className="focus:outline-none"
              value={location}
              onChange={(e) => setLocation(e.target.value)}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Diện tích</p>
            <Divider />
            <input
              className="focus:outline-none"
              value={acreage}
              onChange={(e) => setAcreage(e.target.value)}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Số kệ</p>
            <Divider />
            <input
              style={{ width: 100 }}
              className="focus:outline-none"
              value={shelf}
              onChange={(e) => setShelf(e.target.value)}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Tên loại</p>
            <Divider />
            <Select
                style={{ width: 140 }}
                value={categoryId} 
                onChange={(value) => setCategoryId(value)}  
            >
                {categoriesList.map((category) => (
                    <Option key={category.id} value={category.id}>
                        {category.name}
                    </Option>
                ))}
            </Select>
        </div>

          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Tùy chọn</p>
            <Divider />
            <Space>
              <Button
                type="primary"
                disabled={isEditDisabled}
                onClick={() => handleEdit(true)}
                loading={updateLoading}
              >
                Sửa
              </Button>
              <Button
                className="bg-red-500 text-white"
                disabled={isEditDisabled}
                onClick={() => handleEdit(false)}
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
            dataSource={filteredStorages}
            columns={columns}
            rowKey="id"
            onRow={(record) => ({ onClick: () => handleRowClick(record) })}
          />
        </div>
      </div>
      <Footer />
    </div>
  );
};
