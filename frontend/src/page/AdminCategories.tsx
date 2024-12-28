import {
  Button,
  Divider,
  Form,
  Input,
  Modal,
  Space,
  Table,
  FormProps,
  message,
} from 'antd';
import './style.css';
import { Select } from 'antd';
import { useEffect, useState, useMemo } from 'react';
import * as category from '../service/categories.service';
import { Footer } from '../components/footer/Footer';
import { showNotification } from '../utilities/notification';
import { tabTitle } from '../utilities/title';
const { Option } = Select;

type FieldType = {
  name: string;
  categoryId: string;
  parentId: number;
};
export const AdminCategories = () => {
  const [name, setName] = useState<string>('');
  const [type, setType] = useState<string>('');
  const [parentId, setParentId] = useState<string | null>(null);
  const [categoryId, setCategoryId] = useState<string | null>(null);
  const [categoriesList, setCategoriesList] = useState<any[]>([]);
  const [categoriesParentList, setCategoriesParentList] = useState<any[]>([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [initialValues, setInitialValues] = useState<any>(null);
  const [sortBy, setSortBy] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [createLoading, setCreateLoading] = useState(false);
  const [updateLoading, setUpdateLoading] = useState(false);
  const [deleteLoading, setDeleteLoading] = useState(false);
const columns = [
  { title: 'ID', dataIndex: 'id', key: 'id' },
  { title: 'Tên danh mục', dataIndex: 'name', key: 'name' },
  {
    title: 'Danh mục cha',
    dataIndex: 'parent',
    key: 'parent',
    //@ts-ignore
    render: (parent) => (parent === 'Material' ? 'Nguyên vật liệu' : parent === 'Product' ? 'Thành phẩm' : parent),
  },
  {
    title: 'Loại danh mục',
    dataIndex: 'type',
    key: 'type',
    //@ts-ignore
    render: (type) => (type === 'material' ? 'Nguyên vật liệu' : type === 'product' ? 'Thành phẩm' : type),
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
    setName(record.name);
    setType(record.type);
    setParentId(record.parent_id);
    setCategoryId(record.id);
    setInitialValues(record);
  };

  const isEditDisabled = !name || !type || !parentId || categoryId === null;

  const handleEdit = async (isUpdate: boolean) => {
    try {
      if (isUpdate) {
        setUpdateLoading(true);

        // Check if there's no change in the data
        if (
          name === initialValues.name &&
          type === initialValues.type &&
          parentId === initialValues.parent_id
        ) {
          message.info('Không có thay đổi nào!');
          setUpdateLoading(false);
          return;
        } else {
          // Prepare the updated data
          const data = {
            name,
            type,
            parent_id: parentId,
          };

          // Make the update API call
          const response = await category.updateCategory(
            initialValues.id,
            data
          );

          // Check if the response is successful
          if (response.data) {
            loadData(); // Reload the data
            setName('');
            setType('');
            setParentId(null);
            setCategoryId(null);
            setInitialValues(null);
          }

          showNotification(response);
        }
      } else {
        setDeleteLoading(true);

        // Make the delete API call
        const response = await category.deleteCategory(initialValues.id);

        if (response.data) {
          loadData();
          setName('');
          setType('');
          setParentId(null);
          setCategoryId(null);
          setInitialValues(null);
        }
        showNotification(response);
      }
    } catch (error) {
      // Handle any error that occurs during the update or delete process
      console.error('Error during update or delete operation:', error);
      message.error('Đã xảy ra lỗi. Vui lòng thử lại!');
    } finally {
      // Ensure loading states are reset regardless of success or failure
      setUpdateLoading(false);
      setDeleteLoading(false);
    }
  };

  const onFinish: FormProps<FieldType>['onFinish'] = async (values) => {
    setCreateLoading(true);
    const response = await category.createCategory(
      values.name,
      values.categoryId,
      values.parentId
    );
    if (response.data) {
      loadData();
      setIsModalOpen(false);
    }
    showNotification(response);
    setCreateLoading(false);
  };
  const onFinishFailed: FormProps<FieldType>['onFinishFailed'] = (
    //@ts-ignore
    errorInfo
  ) => {};

  const loadCategoriesParent = async () => {
    const response = await category.getCategoriesParent();
    setCategoriesParentList(response.data);
  };

  const loadData = async () => {
    const response = await category.getAllCategory();
    const categoriesData = response.data;

    const updatedCategories = categoriesData.map((category: any) => {
      const parentCategory = categoriesParentList.find(
        (parent: any) => parent.id === category.parent_id
      );
      return {
        ...category,
        parent: parentCategory ? parentCategory.name : 'Unknown',
      };
    });
    setCategoriesList(updatedCategories);
  };

  useEffect(() => {
    loadCategoriesParent();
  }, []);

  useEffect(() => {
    if (categoriesParentList.length > 0) {
      loadData();
    }
  }, [categoriesParentList]);
  //@ts-ignore
  const handleSortChange = (e) => {
    setSortBy(e.target.value);
  };

  // Lọc và sắp xếp sản phẩm
  const filteredCategories = useMemo(() => {
    let filtered = [...categoriesList];

    // Lọc sản phẩm dựa trên searchTerm
    if (searchTerm) {
      filtered = filtered.filter(
        (categories) =>
          categories.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
          categories.parent.toLowerCase().includes(searchTerm.toLowerCase()) ||
          categories.type.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Sắp xếp sản phẩm
    if (sortBy === 'name') {
      filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === 'name_desc') {
      filtered.sort((a, b) => b.name.localeCompare(a.name));
    } else if (sortBy === 'quantity') {
      filtered.sort((a, b) => a.quantity - b.quantity);
    } else if (sortBy === 'quantity_desc') {
      filtered.sort((a, b) => b.quantity - a.quantity);
    }

    return filtered;
  }, [categoriesList, searchTerm, sortBy]);
  return (
    tabTitle("D2W - Quản lý danh mục"),
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
            Thêm danh mục
          </Button>
          <div style={{ marginBottom: '20px' }}>
            {/* Input tìm kiếm */}
            <Input
              placeholder="Tìm kiếm danh mục"
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
            title="Thêm danh mục"
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
              <Form.Item
                label="Tên danh mục"
                name="name"
                rules={[
                  { required: true, message: 'Please input category name!' },
                ]}
              >
                <Input value={name} onChange={(e) => setName(e.target.value)} />
              </Form.Item>

              <Form.Item
                name="parentId"
                label="Danh mục cha"
                rules={[
                  { required: true, message: 'Parent Category is required' },
                ]}
              >
                <Select
                  placeholder="Chọn danh mục cha"
                  value={parentId}
                  onChange={setParentId}
                >
                  <Option value="1">Nguyên vật liệu</Option>
                  <Option value="2">Thành phẩm</Option>
                </Select>
              </Form.Item>

              <Form.Item
                name="categoryId"
                label="Loại danh mục"
                rules={[{ required: true, message: 'Category is required' }]}
              >
                <Select
                  placeholder="Chọn loại danh mục"
                  value={type}
                  onChange={setType}
                  options={[
                    { value: 'material', label: 'Nguyên vật liệu' },
                    { value: 'product', label: 'Thành Phẩm' },
                  ]}
                />
              </Form.Item>

              <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={createLoading}
                >
                  Thêm danh mục
                </Button>
              </Form.Item>
            </Form>
          </Modal>
        </div>

        <div className="flex flex-row justify-between responsive-container">
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Tên danh mục</p>
            <Divider />
            <input
              className="focus:outline-none"
              value={name}
              onChange={(e) => setName(e.target.value)}
            />
          </div>

          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Danh mục cha</p>
            <Divider />
            <Select
              style={{ width: 140 }}
              value={parentId}
              onChange={setParentId}
              options={categoriesParentList.map((parent: any) => ({
                value: parent.id,
                label: parent.id === 1 ? "Nguyên vật liệu" : parent.id === 2 ? "Thành phẩm" : parent.name,
              }))}
            />
          </div>

          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Loại danh mục</p>
            <Divider />
            <Select
              style={{ width: 140 }}
              value={type}
              onChange={setType}
              options={[
                { value: 'material', label: 'Nguyên vật liệu' },
                { value: 'product', label: 'Thành phẩm' },
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
            dataSource={filteredCategories}
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
