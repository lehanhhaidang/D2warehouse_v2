import React, { useEffect, useMemo, useState } from 'react';
import {
  Button,
  Divider,
  Form,
  FormProps,
  Input,
  Image,
  Modal,
  Space,
  Table,
  message,
} from 'antd';
import './style.css';
import { Select } from 'antd';
import * as materialService from '../service/material.service';
import * as categories from '../service/categories.service';
import * as uploadImage from '../service/image.service';
import { Footer } from '../components/footer/Footer';
import { showNotification } from '../utilities/notification';
import { Select as AntSelect } from 'antd';
import { tabTitle } from '../utilities/title';

type FieldType = {
  name: string;
  image: string;
  unit: string;
  quantity: string;
  category_id: number;
};

const { Option } = Select;

export const AdminMaterial = () => {
  const [name, setName] = useState<string>('');
  const [image, setImage] = useState<File | null>(null);
  const [unit, setUnit] = useState<string>('');
  const [quantity, setQuantity] = useState<string>('');
  const [category, setCategory] = useState<string>('');
  const [status, setStatus] = useState<number | undefined>(undefined);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [materials, setMaterials] = useState<any>([]);
  const [initialValues, setInitialValues] = useState<any>(null);
  const [id, setId] = useState<number>(0);
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
      dataIndex: 'material_img',
      key: 'material_img',
      render: (text: string) => (
        <Image src={text} alt="Material" width={70} height={70} />
      ),
    },
    { title: 'Tên nguyên vật liệu', dataIndex: 'name', key: 'name' },
    { title: 'Đơn vị', dataIndex: 'unit', key: 'unit' },
    { title: 'Số lượng', dataIndex: 'quantity', key: 'quantity' },
    { title: 'Danh mục', dataIndex: 'category_name', key: 'category_name' },
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
    setName(record.name);
    setImage(record.material_img);
    setUnit(record.unit);
    setQuantity(record.quantity);
    setCategory(record.category_id);
    setStatus(record.status);
    setId(record.id);
    setInitialValues({
      name: record.name,
      image: record.material_img,
      unit: record.unit,
      quantity: record.quantity,
      category_id: record.category_id,
      category_name: record.category_name,
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
          status === initialValues?.status
        ) {
          message.info('Không có thay đổi nào!');
          setUpdateLoading(false);
          return;
        } else {
          let imageUrl = initialValues?.image || ''; // If no new image, use the existing image URL.

          if (image) {
            imageUrl = await uploadImage.uploadImageToCloudinary(image);
          } else if (!initialValues?.image) {
            message.error('Cần có hình ảnh');
            setUpdateLoading(false);
            return;
          }
          const response = await materialService.updateMaterial(
            id,
            name,
            imageUrl,
            unit,
            quantity,
            category,
            status
          );

          if (response.data) {
            setMaterials(
              materials.map((material) =>
                material.id === id ? response.data : material
              )
            );
            loadData();
          }
          showNotification(response);
        }
      } else {
        setDeleteLoading(true);
        const response = await materialService.deleteMaterial(id);
        if (response.data) {
          setMaterials(materials.filter((material: any) => material.id !== id));
          setName('');
          // setImage('');
          setUnit('');
          setQuantity('');
          setCategory('');
          setStatus(undefined);
          setId(0);
          loadData();
        }
        showNotification(response);
      }
    } catch (error) {
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
    setCreateLoading(true);
    let imageUrl = '';
    const formData = new FormData();
    formData.append('name', values.name);
    formData.append('unit', values.unit);
    formData.append('quantity', values.quantity);
    formData.append('category_id', values.category_id.toString());
    formData.append('status', '1');
    if (image) {
      imageUrl = await uploadImage.uploadImageToCloudinary(image);
    }
    if (!image) {
      message.error('Cần có hình ảnh');
      setCreateLoading(false);
      return;
    }
    const response = await materialService.createMaterial(
      values.name,
      imageUrl,
      values.unit,
      Number.parseInt(values.quantity),
      values.category_id
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

  const isEditDisabled = !name || !unit || !category || status === undefined;
  const loadCategory = async () => {
    const response = await categories.getAllCategory();
    if (response.data) {
      setCategoriesList(response.data);
    }
  };
  const loadData = async () => {
    const response = await materialService.getMaterials();
    setMaterials(response.data);
  };

  useEffect(() => {
    loadCategory();
    loadData();
  }, []);
  const handleSortChange = (e) => {
    setSortBy(e.target.value);
  };

  // Lọc và sắp xếp nvl
  const filteredMaterials = useMemo(() => {
    let filtered = [...materials];

    // Lọc nvl dựa trên searchTerm
    if (searchTerm) {
      filtered = filtered.filter((material) =>
        material.name.toLowerCase().includes(searchTerm.toLowerCase())
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
  }, [materials, searchTerm, sortBy]);
  return (
    tabTitle("D2W - Quản lý nguyên vật liệu"),
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
            Thêm nguyên vật liệu
          </Button>
          <div style={{ marginBottom: '20px' }}>
            {/* Input tìm kiếm */}
            <Input
              placeholder="Tìm kiếm nguyên vật liệu"
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
            title="Thêm nguyên vật liệu"
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
                label="Tên nguyên vật liệu"
                name="name"
                rules={[
                  { required: true, message: 'Hãy nhập tên nguyên vật liệu!' },
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
                    message: 'Nhập đơn vị tính cho nguyên vật liệu!',
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
                    message: 'Hãy chọn danh mục cho nguyên vật liệu',
                  },
                ]}
              >
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
              </Form.Item>
              <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={createLoading}
                >
                  Thêm nguyên vật liệu
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
            <p>Trạng thái</p>
            <Divider />
            <AntSelect
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
            dataSource={filteredMaterials}
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
