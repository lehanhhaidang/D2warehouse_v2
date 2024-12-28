import { useEffect, useState } from 'react';
import { PlusOutlined, CloseOutlined } from '@ant-design/icons';  
import { Table, Button, Modal, Tag, Form, Input, Select, Spin,   message } from 'antd';
import { createManufacturingPlan, getAllManufacturingPlan } from '../service/manufacturing-plan.service';
import { Footer } from '../components/footer/Footer';
import { getProducts } from '../service/product.service';
import { calculateMaterials, getMaterials } from '../service/material.service'; 
import { Link, useNavigate } from 'react-router-dom';
import { IUser } from '../common/interface';
import { tabTitle } from '../utilities/title';

export const ManufacturingPlanPage = () => {
  tabTitle("D2W - Kế hoạch sản xuất");
  const [manufacturingPlans, setManufacturingPlans] = useState<any[]>([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isLoadingProducts, setIsLoadingProducts] = useState(false);
  const [loading, setLoading] = useState(false);
  const [products, setProducts] = useState<any[]>([]);
  const [materials, setMaterials] = useState<any[]>([]);
  const [form] = Form.useForm();
  const navigate = useNavigate();
  const [materialCalculations, setMaterialCalculations] = useState<any[]>([]); 
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const [calLoading, setCalLoading] = useState(false);
  const [createLoading, setCreateLoading] = useState(false);
  const [insufficientMaterials, setInsufficientMaterials] = useState<any[]>([]);

  // Lấy dữ liệu kế hoạch sản xuất khi trang load
  const loadManufacturingPlans = async () => {
    setLoading(true);
    try {
      const response = await getAllManufacturingPlan();
      if (response.data.data) {
        // console.log(response.data.data);
        setManufacturingPlans(response.data.data);
      }
    } catch (error) {
      console.log(error);
    }
    setLoading(false);
  };

  // Lấy danh sách sản phẩm khi trang load
  const loadProducts = async () => {
    setIsLoadingProducts(true);
    try {
      const response = await getProducts();
      if (response.data) {
        setProducts(response.data);
      }
    } catch (error) {
      message.error('Lỗi khi tải dữ liệu sản phẩm');
    }
    setIsLoadingProducts(false);
  };

  const loadMaterials = async () => { 
    const response = await getMaterials();
    if (response.data) {
      setMaterials(response.data);
      console.log(response.data);
    }
    else {
      message.error('Lỗi khi tải dữ liệu vật liệu');
    }
  };


  useEffect(() => {
    loadManufacturingPlans();
    loadProducts();
    loadMaterials();
  }, []);

  // Hàm mở modal tạo kế hoạch sản xuất
  const handleCreatePlan = () => {
    setIsModalOpen(true);
  };

  const handleCreatePlanCancel = () => {
    setIsModalOpen(false);
  };

const handleCreatePlanSubmit = async (values: any) => {
  setCreateLoading(true);
  const formValues = form.getFieldsValue();
  const finishedProducts = formValues.finished_products || [];

  // Kết hợp dữ liệu sản phẩm và vật liệu
  const manufacturingPlanDetails = finishedProducts.map((item: any) => {

    // Lấy nguyên liệu từ kết quả tính toán (materialCalculations)
    const materialsForProduct = materialCalculations.filter(material => 
      material.product_id === item.product_id
    );

    return materialsForProduct.map((material: any) => ({
      product_id: item.product_id,
      product_quantity: item.finished_quantity,
      material_id: material.material_id,
      material_quantity: material.material_quantity_needed  // Tính toán số lượng vật liệu cần cho số lượng sản phẩm
    }));
  }).flat(); // Dùng .flat() để làm phẳng mảng kết quả

  // Dữ liệu gửi đến API
  const dataToSend = {
    name: values.name,
    description: values.description,
    manufacturing_plan_details: manufacturingPlanDetails,
  };

  setLoading(true);
  try {
    const response = await createManufacturingPlan(dataToSend);
    if (response.data) {

      loadManufacturingPlans(); // Reload manufacturing plans
      setIsModalOpen(false); // Close modal
      message.success(response.data.message);
    } else {
      message.error(response.error?.message || 'Đã xảy ra lỗi');
    }
  } catch (error) {
    message.error('Đã xảy ra lỗi');
  }
  setLoading(false);
  setCreateLoading(false);
};


const handleCalculateMaterials = async () => {
  setCalLoading(true);
  const formValues = form.getFieldsValue();
  const productsData = formValues.finished_products || [];



  try {
      // Chuyển đổi dữ liệu thành định dạng yêu cầu cho API
  const productsForCalculation = productsData.map((item: any) => ({
    product_id: item.product_id,
    product_quantity: item.finished_quantity,
  }));

    if (productsForCalculation.length === 0) {
      message.error('Vui lòng chọn ít nhất 1 thành phẩm để tính toán vật liệu');
      setCalLoading(false);
      return;
    }
    const result = await calculateMaterials(productsForCalculation);

    if (result.data) {
      const materialsWithProducts = result.data.map((material: any) => ({
        ...material,
      }));

      // So sánh với dữ liệu vật liệu hiện tại và tính chênh lệch
      const insufficientMaterials = materialsWithProducts.filter((material: any) => {
        // Kiểm tra nếu vật liệu không đủ số lượng
        const materialInStock = materials.find((m: any) => m.name === material.material_name);
        return materialInStock && materialInStock.quantity < material.material_quantity_needed;
      });

      if (insufficientMaterials.length > 0) {
        // Tính toán sự chênh lệch giữa quantity và quantity_needed
        const insufficientMaterialsWithDifference = insufficientMaterials.map((item: any) => {
          const materialInStock = materials.find((m: any) => m.name === item.material_name);
          const quantityDifference = materialInStock ? item.material_quantity_needed - materialInStock.quantity : 0;
          console.log(quantityDifference);
          return {
            ...item,
            quantity_difference: quantityDifference,
          };
        });

        setInsufficientMaterials(insufficientMaterialsWithDifference); // Lưu vào trạng thái

        const insufficientMaterialNames = insufficientMaterialsWithDifference
          .map(item => `${item.material_name} (Còn thiếu: ${item.quantity_difference})`)
          .join(', ');

        message.error(`Vật liệu thiếu: ${insufficientMaterialNames}`);
      } else {
        // Nếu không thiếu vật liệu, reset thông báo lỗi
        setInsufficientMaterials([]);
      }

      // Lưu kết quả tính toán vật liệu
      setMaterialCalculations(materialsWithProducts);
    } else {
      setMaterialCalculations([]); // Nếu không có dữ liệu, reset lại
      message.error('Không thể tính toán vật liệu');
      setCalLoading(false);
    }
  } catch (error) {
    setCalLoading(false);
    setMaterialCalculations([]); // Nếu có lỗi, reset lại
    message.error('Đã xảy ra lỗi khi tính toán vật liệu, chắc chắn rằng bạn đã chọn thành phẩm.');
  }
  setCalLoading(false);
};

  const columns = [
    {
      title: 'ID',
      dataIndex: 'id',
      key: 'id',
    },
    {
      title: 'Tên kế hoạch',
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
      },
    },
    {
      title: 'Mô tả',
      dataIndex: 'description',
      key: 'description',
    },
  ];

  // Hàm tính toán tổng số lượng vật liệu
const calculateTotalMaterials = (materials: any[]) => {
  const materialTotals: { [key: string]: { material_name: string, unit: string, total_quantity: number } } = {};

  materials.forEach(item => {
    if (materialTotals[item.material_name]) {
      materialTotals[item.material_name].total_quantity += item.material_quantity_needed;
    } else {
      materialTotals[item.material_name] = {
        material_name: item.material_name,
        unit: item.unit,
        total_quantity: item.material_quantity_needed,
      };
    }
  });

  return Object.values(materialTotals);
};

// Kết quả tính toán vật liệu và tổng kết
const totalMaterials = calculateTotalMaterials(materialCalculations);

const handleRowClick = (record: any) => {
  navigate(`/manufacturing-detail/${record.id}`);
};
  return (
    <div className="flex w-full justify-center bg-slate-300" style={{ height: 'calc(85vh)' }}>
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content">
        {/* Button Tạo Kế Hoạch */}
        {userInfor.role_id === 2 && (
          <Button type="primary" className="mb-4 max-w-60" size="large" onClick={handleCreatePlan}>
          Tạo kế hoạch
        </Button>
        )}

        <Table
          columns={columns}
          dataSource={manufacturingPlans}
          rowKey="id"
          loading={loading}
          pagination={false}
          onRow={(record) => ({
            onClick: () => handleRowClick(record),
          })}
        />

        {/* Modal tạo kế hoạch */}
        <Modal
          title="Tạo kế hoạch sản xuất"
          open={isModalOpen}
          onCancel={handleCreatePlanCancel}
          footer={null}
          width={800} // Tăng chiều rộng của modal để rộng rãi hơn
        >
          <Spin spinning={isLoadingProducts}>
            <Form
              form={form}
              onFinish={handleCreatePlanSubmit}
              labelCol={{ span: 24, sm: 6 }}
              wrapperCol={{ span: 24, sm: 18 }}
            >
              {/* Tên kế hoạch */}
              <Form.Item
                label="Tên kế hoạch"
                name="name"
                rules={[{ required: true, message: 'Vui lòng nhập tên kế hoạch' }]}
              >
                <Input className="w-full" />
              </Form.Item>

              {/* Mô tả */}
              <Form.Item
                label="Mô tả"
                name="description"
                rules={[{ required: true, message: 'Vui lòng nhập mô tả' }]}
              >
                <Input.TextArea rows={4} className="w-full" />
              </Form.Item>

              {/* Danh sách thành phẩm */}
              <Form.List
                name="finished_products"
                initialValue={[]}
                rules={[
                  {
                    validator: async (_, finishedProducts) => {
                      if (!finishedProducts || finishedProducts.length < 1) {
                        return Promise.reject(new Error('Vui lòng thêm ít nhất 1 thành phẩm'));
                      }
                    },
                  },
                ]}
              >
                {(fields, { add, remove }) => (
                  <>
                    {fields.map(({ key, fieldKey, name, fieldType }) => (
                      <div
                        key={key}
                        className="border p-4 mb-4 flex flex-wrap items-center justify-between gap-4"
                      >
                        {/* Nút Xóa */}
                        <Form.Item
                          style={{ marginBottom: 0 }}
                          className="flex justify-end w-full"
                        >
                          <Button
                            type="link"
                            icon={<CloseOutlined />}
                            onClick={() => remove(name)}
                            style={{ padding: 0 }}
                          />
                        </Form.Item>

                        {/* Chọn sản phẩm */}
                        <Form.Item
                          {...fieldType}
                          name={[name, 'product_id']}
                          fieldKey={[fieldKey, 'product_id']}
                          label="Thành phẩm"
                          rules={[{ required: true, message: 'Vui lòng chọn sản phẩm' }]}
                          className="flex-1 min-w-[200px] mr-2"
                        >
                          <Select placeholder="Chọn thành phẩm">
                            {products.map((product) => (
                              <Select.Option key={product.id} value={product.id}>
                                {product.name}
                              </Select.Option>
                            ))}
                          </Select>
                        </Form.Item>

                        {/* Số lượng thành phẩm */}
                        <Form.Item
                            {...fieldType}
                            name={[name, 'finished_quantity']}
                            fieldKey={[fieldKey, 'finished_quantity']}
                            label="Số lượng"
                            rules={[
                              { required: true, message: 'Vui lòng nhập số lượng' },
                              { 
                                validator: (_, value) => {
                                  if (value && (value % 2 !== 0 || value > 5000)) {
                                    return Promise.reject(new Error('Vui lòng nhập số lượng chẵn và nhỏ hơn 5000'));
                                  }
                                  return Promise.resolve();
                                }
                              }
                            ]}
                            className="flex-1 min-w-[150px] mr-2"
                          >
                            <Input type="number" min={1} max={5000} />
                          </Form.Item>

                      </div>
                    ))}

                    {/* Nút Thêm thành phẩm */}
                    <Form.Item className="text-center mb-0 ml-28">
                      <Button
                        className="flex items-center justify-center mx-auto"
                        type="dashed"
                        onClick={() => add()}
                        icon={<PlusOutlined />}
                      >
                        Thêm thành phẩm
                      </Button>
                    </Form.Item>
                  </>
                )}
              </Form.List>

              {/* Hiển thị kết quả tính toán vật liệu */}
              {materialCalculations.length > 0 && (
              <div className="mt-4">
                <h4>Tổng kết vật liệu:</h4>
                <Table
                  columns={[
                    { title: 'Tên vật liệu', dataIndex: 'material_name', key: 'material_name' },
                    { title: 'Đơn vị', dataIndex: 'unit', key: 'unit' },
                    { title: 'Tổng số lượng cần', dataIndex: 'total_quantity', key: 'total_quantity' },
                  ]}
                  dataSource={totalMaterials}
                  rowKey="material_name"
                  pagination={false}
                />
                {/* Hiển thị thông báo lỗi nếu có vật liệu thiếu */}
                      {insufficientMaterials.length > 0 && (
                        <div className="mt-4 text-red-500">
                          <strong>Lưu ý:</strong> Có vật liệu thiếu trong kho, vui lòng kiểm tra lại ở: <Link to={'/material'} className='text-blue-500'>Nguyên vật liệu</Link>
                          <ul>
                            {insufficientMaterials.map((item: any, index: number) => (
                              <li key={index}>
                                <strong>{item.material_name}</strong> - Số lượng thiếu: <strong>{ item.quantity_difference}</strong>
                              </li>
                            ))}
                          </ul>
                          Hoặc
                          <br />
                          <Link className="text-blue-500" to={'/manager-import'}>
                            Tạo đề xuất nhập thêm nguyên vật liệu
                          </Link>
                        </div>
                      )}

                    </div>
                  )}
              {/* Nút tính toán vật liệu và tạo kế hoạch */}
              <Form.Item wrapperCol={{ offset: 6, span: 12 }} className="mt-10">
                <div className="flex justify-center gap-4">
                  <Button
                    type="primary"
                    onClick={handleCalculateMaterials}
                    className="w-full max-w-[200px] bg-green-500 hover:bg-green-600"
                    loading={calLoading}
                  >
                    Tính toán vật liệu
                  </Button>
                  <Button
                    type="primary"
                    htmlType="submit"
                    className="w-full max-w-[200px] bg-blue-500 hover:bg-blue-600"
                    loading={createLoading}
                    disabled={insufficientMaterials.length > 0}  // Disable nút khi có vật liệu thiếu
                  >
                    Tạo kế hoạch
                  </Button>
                </div>
              </Form.Item>
            </Form>
          </Spin>
        </Modal>
      </div>
      <Footer />
    </div>
  );
};
