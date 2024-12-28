import { Input, Select, Form, Button, Modal, Table } from 'antd';
import { useEffect, useState } from 'react';
import * as warehouse from '../service/warehouse.service';
import * as material from '../service/material.service';
import * as inventory from '../service/inventory-report.service';
import * as shelf from '../service/shelve.service';
import { IUser, MaterialEntry } from '../common/interface';
import { useNavigate } from 'react-router-dom';
import { Footer } from '../components/footer/Footer';
import { showNotification } from '../utilities/notification';
import { tabTitle } from '../utilities/title';

export const InventoryReport: React.FC = () => {
  tabTitle("D2W - Kiểm kê")
  const [form] = Form.useForm();
  const [materialEntries, setMaterialEntries] = useState<MaterialEntry[]>([
    { id: `${Date.now()}-${Math.random().toString(36).substring(2, 9)}` },
  ]);
  const [productList, setProductList] = useState<any[]>([]);
  const [warehouseList, setWarehouseList] = useState<any[]>([]);
  const [materialList, setMaterialList] = useState<any[]>([]);
  const [proposeList, setProposeList] = useState<any[]>([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [shelfDetail, setShelfDetail] = useState<any[]>([]);
  const navigate = useNavigate();
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const [loading, setLoading] = useState(false);

  const addMaterialEntry = () => {
    setMaterialEntries([
      ...materialEntries,
      { id: `${Date.now()}-${Math.random().toString(36).substring(2, 9)}` },
    ]);
  };

 const onFinish = async (values: any) => {
  setLoading(true);

  // Determine warehouse type
  const warehouseType = values.warehouse_id; // Assuming warehouse_id is either 1 or 2

  const details = materialEntries.map((entry, index) => {
    let materialOrProduct = null;

    if (warehouseType === 1) {  // 
      materialOrProduct =  values[`material_id_${index}`];
    } else if (warehouseType === 2) { 
      materialOrProduct = values[`material_id_${index}`];
    }

    return {
      product_id: warehouseType === 2 ? materialOrProduct : null,  
      material_id: warehouseType === 1 ? materialOrProduct : null, 
      shelf_id: values[`shelf_id_${index}`],
      expected_quantity: values[`expected_quantity_${index}`],
      actual_quantity: values[`actual_quantity_${index}`],
      note: values[`note_${index}`],
    };
  });

  const newPropose = {
    name: values.name,
    description: values.description,
    warehouse_id: values.warehouse_id,
    status: 0,
    details,
  };

  const response = await inventory.createInventoryReport(newPropose);
  if (response.data) {
    setIsModalOpen(false);
    loadInventory();
    setLoading(false);
  }
  showNotification(response);
};



  const loadInventory = async () => {
    const response = await inventory.getAllInventoryReport();
    if (response.data) {
      setProposeList(response.data.data);
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

  const loadShelfByWarehouse = async (id: number) => {
    const response = await shelf.shelfDetailFilter(id);
    if (response.data) {
      setShelfDetail(response.data);
    }
  };

  const filterProduct = (value: any) => {
  const selectedShelf = shelfDetail.find(item => item.id === value);

  // Kiểm tra kho và lấy thông tin chi tiết phù hợp (product_name hoặc material_name)
  if (selectedShelf) {
    if (selectedShelf.category_name.includes('Nhựa HDPE') || selectedShelf.category_name.includes('Nhựa PET')) {
      // Nếu là kho nguyên vật liệu, hiển thị danh sách nguyên vật liệu
      setProductList(selectedShelf.details.map(item => ({
        id: item.material_id,  // Lưu ý: Đây là material_id
        name: item.material_name,
        quantity: item.quantity,
      })));
    } else {
      // Nếu là kho thành phẩm, hiển thị danh sách sản phẩm
      setProductList(selectedShelf.details.map(item => ({
        id: item.product_id,  // Đây là product_id
        name: item.product_name,
        quantity: item.quantity,
      })));
    }
  }
};




const filterExpect = (value: any, index: number) => {
  // Lọc theo product_id hoặc material_id tuỳ kho
  const selectedItem = productList.find(item => item.id === value);
  const filteredExpect = selectedItem ? selectedItem.quantity : 0;

  form.setFieldsValue({
    [`expected_quantity_${index}`]: filteredExpect,
  });
};


  useEffect(() => {
    loadDataWareHouse();
    loadMaterial();
    loadInventory();
  }, []);

  const handleRowClick = (record: any) => {
  if (record.status > 1) {
    navigate(`/inventory-table/${record.id}`);
  } else {
    userInfor?.role_id === 2 || userInfor?.role_id === 3 || userInfor?.id !== record.created_by
      ? navigate(`/inventory-table/${record.id}`)
      : navigate(`/inventory-detail/${record.id}`);
  }
};

  const columns = [
    {
      title: 'ID',
      dataIndex: 'id',
      key: 'id',
    },
    {
      title: 'Tên',
      dataIndex: 'name',
      key: 'name',
    },
    {
      title: 'Nhà kho',
      dataIndex: 'warehouse_name',
      key: 'warehouse_name',
    },
    {
      title: 'Trạng thái',
      dataIndex: 'status',
      key: 'status',
      render: (status: number) => {
        switch (status) {
          case 0:
            return 'Chờ gửi';
          case 1:
            return 'Chờ duyệt';
          case 2:
            return 'Đã duyệt';
          case 3:
            return 'Đã từ chối';
          case 4:
            return 'Đã thông qua';
          case 5:
            return 'Đã hủy';
          default:
            return 'Không xác định'; 
        }
      },
    },
    {
      title: 'Mô tả',
      dataIndex: 'description',
      key: 'description',
    },
    {
      title: 'Người tạo',
      dataIndex: 'created_by_name',
      key: 'created_by_name',
    },
    {
      title: 'Ngày tạo',
      dataIndex: 'created_at',
      key: 'created_at',
    },
  ];

  return (

    <div
      className="flex w-full justify-center bg-slate-300"
      style={{ height: 'calc(85vh)' }}
    >
      <div className="flex flex-col w-4/5 pt-6 pb-16 px-4 bg-slate-100 scrollable-content ">
        {userInfor?.role_id !== 3 && userInfor?.role_id !== 2  &&(
          <div className="mb-4">
            <Button
              type="primary"
              size="large"
              className="text-white"
              onClick={() => {
                setIsModalOpen(true);
                setMaterialEntries([{ id: Date.now() }]);
              }}
            >
              Tạo Báo cáo
            </Button>
            <Modal
              footer={null}
              title={<div style={{ textAlign: 'center', fontSize: '20px' }}>Tạo Báo Cáo</div>}
              open={isModalOpen}
              onCancel={() => setIsModalOpen(false)}
            >
              <Form form={form} name="product_form" onFinish={onFinish}>
                <Form.Item
                  name="name"
                  label="Tên báo cáo"
                  rules={[{ required: true, message: 'Hãy nhập tên phiếu!' }]}
                >
                  <Input />
                </Form.Item>

                <Form.Item
                  name="warehouse_id"
                  label="Nhà kho"
                  rules={[{ required: true, message: 'Hãy chọn kho!' }]}
                >
                  <Select onChange={(value) => loadShelfByWarehouse(value)}>
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
                  rules={[{ required: true, message: 'Hãy nhập mô tả!' }]}
                >
                  <Input />
                </Form.Item>
                {materialEntries.map((entry, index) => (
                  <div key={entry.id} className="border p-4 mb-4">
                    <Form.Item
                      name={`shelf_id_${index}`}
                      label="Kệ"
                      rules={[{ required: true, message: 'Hãy chọn kệ!' }]}
                    >
                      <Select onChange={(value) => filterProduct(value)}>
                        {shelfDetail.map((shelf) => (
                          <Select.Option key={shelf.id} value={shelf.id}>
                            {shelf.name}
                          </Select.Option>
                        ))}
                      </Select>
                    </Form.Item>

                    <Form.Item
                      name={`material_id_${index}`}
                      label="Thành phẩm / Nguyên vật liệu"
                      rules={[{ required: true, message: 'Hãy chọn thành phẩm/nguyên vật liệu!' }]}
                    >
                      <Select onChange={(value) => filterExpect(value, index)}>
                        {productList.map((product) => (
                          <Select.Option key={product.id} value={product.id}>
                            {product.name}
                          </Select.Option>
                        ))}
                      </Select>
                    </Form.Item>

                    <Form.Item
                      name={`expected_quantity_${index}`}
                      label="Số lượng hệ thống"
                      rules={[{ required: true, message: 'Hãy nhập số lượng!' }]}
                    >
                      <Input disabled />
                    </Form.Item>

                    <Form.Item
                      name={`actual_quantity_${index}`}
                      label="Số lượng thực tế"
                      rules={[{ required: true, message: 'Hãy nhập số lượng!' }]}
                    >
                      <Input />
                    </Form.Item>

                    <Form.Item 
                      name={`note_${index}`}
                      label="Ghi chú"
                      rules={[{ required: true, message: 'Hãy nhập ghi chú!' }]}
                      initialValue={'Không có thay đổi'}
                    >
                      <Input />
                    </Form.Item>
                  </div>
                ))}

                <Button type="dashed" onClick={addMaterialEntry} className='ml-28' >
                  + Thêm sản phẩm/nguyên vật liệu
                </Button>

                <Form.Item className='text-center'>
                  <Button type="primary" htmlType="submit" loading={loading} className='mt-4'> 
                    Tạo Báo Cáo
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
