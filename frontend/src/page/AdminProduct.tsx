import React, { useEffect, useState, useMemo } from 'react';
import {
  Button,
  Divider,
  Form,
  FormProps,
  Image,
  Input,
  message,
  Modal,
  Space,
  Table,
} from 'antd';
import './style.css';
import * as productService from '../service/product.service';
import * as categories from '../service/categories.service';
import * as uploadImage from '../service/image.service';
import { Footer } from '../components/footer/Footer';
import { showNotification } from '../utilities/notification';
import { Select} from 'antd';
import { tabTitle } from '../utilities/title';


type FieldType = {
  name: string;
  image: string;
  unit: string;
  quantity: string;
  category_id: number;
  color_id: number;
};

const { Option } = Select;

export const AdminProduct: React.FC = () => {
  const [name, setName] = useState<string>('');
  const [image, setImage] = useState<File | null>(null);
  const [unit, setUnit] = useState<string>('');
  const [quantity, setQuantity] = useState<string>('');
  const [category, setCategory] = useState<string>('');
  const [color, setColor] = useState<string>('');
  const [status, setStatus] = useState<number | undefined>(undefined);
  const [id, setId] = useState<number>(0);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [products, setProducts] = useState<any[]>([]);
  const [initialValues, setInitialValues] = useState<any>(null);
  const [categoriesList, setCategoriesList] = useState<any[]>([]);
  const [sortBy, setSortBy] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [createLoading, setCreateLoading] = useState(false);
  const [updateLoading, setUpdateLoading] = useState(false);
  const [deleteLoading, setDeleteLoading] = useState(false);
  const columns = [
    { title: 'ID', dataIndex: 'id', key: 'id' },
    {
      title: 'Hình ảnh',
      dataIndex: 'product_img',
      key: 'product_img',
      render: (text: string) => (
        <Image src={text} alt="Product" width={70} height={70} />
      ),
    },
    { title: 'Tên thành phẩm', dataIndex: 'name', key: 'name' },
    { title: 'Đơn vị', dataIndex: 'unit', key: 'unit' },
    { title: 'Số lượng', dataIndex: 'quantity', key: 'quantity' },
    { title: 'Danh mục', dataIndex: 'category_name', key: 'category_name' },
    { title: 'Màu sắc', dataIndex: 'color_name', key: 'color_name' },
    {
      title: 'Trạng thái',
      dataIndex: 'status',
      key: 'status',
      render: (status: number) =>
        status === 1 ? 'Kích hoạt' : 'Không kích hoạt',
    },
    {
      title: 'Ngày tạo',
      dataIndex: 'created_at',
      key: 'created_at',
      render: (date: string) => new Date(date).toLocaleDateString(),
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
    setImage(record.product_img);
    setUnit(record.unit);
    setQuantity(record.quantity);
    setCategory(record.category_id);
    setColor(record.color_id);
    setStatus(record.status);
    setId(record.id);
    setInitialValues({
      name: record.name,
      image: record.product_img,
      unit: record.unit,
      quantity: record.quantity,
      category_id: record.category_id,
      category_name: record.category_name,
      color_id: record.color_id,
      color_name: record.color_name,
      status: record.status,
      id: record.id,
    });
  };

  const handleEdit = async (isUpdate: boolean) => {
    try {
      if (isUpdate) {
        setUpdateLoading(true);
        if (
          name === initialValues?.name &&
          image === initialValues?.image &&
          unit === initialValues?.unit &&
          quantity === initialValues?.quantity &&
          category === initialValues?.category_id &&
          color === initialValues?.color_id &&
          status === initialValues?.status
        ) {
          message.info('Không có thay đổi nào!');
          setUpdateLoading(false); // End loading if no changes
          return;
        }

        let imageUrl = initialValues?.image || ''; // If no new image, use the existing image URL.

        // If a new image is selected, upload it to Cloudinary
        if (image) {
          imageUrl = await uploadImage.uploadImageToCloudinary(image);
        } else if (!initialValues?.image) {
          message.error('Cần có hình ảnh');
          setUpdateLoading(false); // End loading if no image is provided and no initial image exists
          return;
        }

        const response = await productService.updateProduct(
          id,
          name,
          imageUrl,
          unit,
          quantity,
          category,
          color,
          status
        );

        if (response.data) {
          setProducts(
            products.map((product) =>
              product.id === id ? response.data : product
            )
          );
          loadData();
        }
        showNotification(response);
      } else {
        setDeleteLoading(true);
        const response = await productService.deleteProduct(id);

        if (response.data) {
          setProducts(products.filter((product: any) => product.id !== id));
          setName('');
          setUnit('');
          setQuantity('');
          setCategory('');
          setColor('');
          setStatus(undefined);
          setId(0);
          loadData();
        }

        showNotification(response);
      }
    } catch (error) {
      console.error('Error occurred:', error);
    } finally {
      setUpdateLoading(false);
      setDeleteLoading(false);
    }
  };

  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      const isJpgOrPng =
        file.type === 'image/jpeg' || file.type === 'image/png';
      if (!isJpgOrPng) {
        alert('Chỉ cho phép upload file JPG hoặc PNG!');
        return;
      }

      setImage(file);
    }
  };

  const onFinish: FormProps<FieldType>['onFinish'] = async (values) => {
    setCreateLoading(true); // Step 2: Set loading to true when form is submitted

    let imageUrl = '';
    const formData = new FormData();
    formData.append('name', values.name);
    formData.append('unit', values.unit);
    formData.append('quantity', values.quantity);
    formData.append('category_id', values.category_id.toString());
    formData.append('color_id', values.color_id.toString());
    formData.append('status', '1');

    if (image) {
      imageUrl = await uploadImage.uploadImageToCloudinary(image);
    }

    if (!image) {
      message.error('Cần có hình ảnh');
      setLoading(false); // Step 3: Set loading to false if no image
      return;
    }

    const response = await productService.createProduct(
      values.name,
      imageUrl,
      values.unit,
      Number.parseInt(values.quantity),
      values.category_id,
      values.color_id
    );

    if (response.data) {
      setIsModalOpen(false);
      loadData();
    }
    showNotification(response);

    setCreateLoading(false);
  };

  const onFinishFailed: FormProps<FieldType>['onFinishFailed'] = (
    errorInfo
  ) => {};

  const isEditDisabled =
    !name || !unit || !category || !color || status === undefined;
  const loadCategory = async () => {
    const response = await categories.getAllCategory();
    if (response.data) {
      setCategoriesList(response.data);
    }
  };
  const loadData = async () => {
    const response = await productService.getProducts();
    setProducts(response.data);
  };

  useEffect(() => {
    loadCategory();
    loadData();
  }, []);
  const handleSortChange = (e) => {
    setSortBy(e.target.value);
  };

  // Lọc và sắp xếp sản phẩm
  const filteredProducts = useMemo(() => {
    let filtered = [...products];

    // Lọc sản phẩm dựa trên searchTerm
    if (searchTerm) {
        filtered = filtered.filter(
          (product) =>
            (product.name?.toLowerCase() ?? '').includes(searchTerm.toLowerCase()) ||
            (product.category_name?.toLowerCase() ?? '').includes(searchTerm.toLowerCase()) ||
            (product.color_name?.toLowerCase() ?? '').includes(searchTerm.toLowerCase())
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
  }, [products, searchTerm, sortBy]);
  return (
    tabTitle("D2W - Quản lý thành phẩm"),
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
            Thêm thành phẩm
          </Button>
          <div style={{ marginBottom: '20px' }}>
            {/* Input tìm kiếm */}
            <Input
              placeholder="Tìm kiếm thành phẩm"
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
              <Option value="quantity">Số lượng tăng dần</Option>
              <Option value="quantity_desc">Số lượng giảm dần</Option>
            </Select>
          </div>
          <Modal
            footer={null}
            title="Thêm thành phẩm"
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
                label="Tên thành phẩm"
                name="name"
                rules={[
                  { required: true, message: 'Hãy nhập tên thành phẩm!' },
                ]}
              >
                <Input />
              </Form.Item>
              <Form.Item
                label="Hình ảnh"
                name="image"
                rules={[{ required: true, message: 'Hãy chọn hình ảnh!' }]}
              >
                <input
                  type="file"
                  accept="image/jpeg, image/png"
                  onChange={handleFileChange}
                />
              </Form.Item>
              <Form.Item<FieldType>
                label="Đơn vị"
                name="unit"
                rules={[
                  {
                    required: true,
                    message: 'Nhập đơn vị tính cho thành phẩm!',
                  },
                ]}
              >
                <Input />
              </Form.Item>
              <Form.Item<FieldType>
                label="Số lượng"
                name="quantity"
                rules={[{ required: true, message: 'Nhập số lượng!' }]}
                initialValue={0}
              >
                <Input placeholder="Số lượng mặc định là 0" disabled />
              </Form.Item>
              <Form.Item<FieldType>
                name={'category_id'}
                label="Danh mục"
                rules={[
                  {
                    required: true,
                    message: 'Hãy chọn danh mục cho thành phẩm!',
                  },
                ]}
              >
                <Select
                  placeholder="Chọn danh mục"
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
              </Form.Item>
              <Form.Item<FieldType>
                name={'color_id'}
                label="Màu sắc"
                rules={[
                  { required: true, message: 'Chọn màu cho thành phẩm!' },
                ]}
              >
                <Select placeholder="Chọn màu">
                  <Option value="1">Đỏ</Option>
                  <Option value="2">Xanh</Option>
                  <Option value="3">Vàng</Option>
                  <Option value="4">Trắng</Option>
                  <Option value="5">Đen</Option>
                </Select>
              </Form.Item>
              <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={createLoading}
                >
                  Thêm thành phẩm
                </Button>
              </Form.Item>
            </Form>
          </Modal>
        </div>
        <div className="flex flex-row justify-between responsive-container">
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Tên thành phẩm</p>
            <Divider />
            <input
              style={{ width: 150 }}
              className="focus:outline-none"
              value={name}
              onChange={(e) => setName(e.target.value)}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Hình ảnh</p>
            <Divider />
            <input
              type="file"
              accept="image/jpeg, image/png"
              onChange={handleFileChange}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Đơn vị</p>
            <Divider />
            <input
              style={{ width: 100 }}
              className="focus:outline-none"
              value={unit}
              onChange={(e) => setUnit(e.target.value)}
            />
          </div>
          <div className="flex flex-col border-2 items-center p-2 rounded-sm responsive-item">
            <p>Số lượng</p>
            <Divider />
            <input
              style={{ width: 100 }}
              className="focus:outline-none"
              value={quantity}
              onChange={(e) => setQuantity(e.target.value)}
            />
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
            <p>Màu sắc</p>
            <Divider />
            <Select
              style={{ width: 100 }}
              value={color}
              onChange={(value) => setColor(value)}
              options={[
                { value: 1, label: 'Đỏ' },
                { value: 2, label: 'Xanh' },
                { value: 3, label: 'Vàng' },
                { value: 4, label: 'Trắng' },
                { value: 5, label: 'Đen' },
              ]}
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
                { value: 1, label: 'Kích hoạt' },
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
            dataSource={filteredProducts}
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
