import { useParams } from 'react-router-dom';
import * as inventory from '../service/inventory-report.service';
import * as warehouse from '../service/warehouse.service';
import * as shelf from '../service/shelve.service';
import { useEffect, useState } from 'react';
import {
  Form,
  Input,
  Select,
  Button,
  Card,
  Divider,
  Typography,
  Modal,
} from 'antd';
import {
  PlusOutlined,
  SendOutlined,
  SaveOutlined,
  DeleteOutlined,
} from '@ant-design/icons';
import { IUser, MaterialEntry } from '../common/interface';
import { showNotification } from '../utilities/notification';
import { useNavigate } from 'react-router-dom';
import { STATUS_INVENTORY_REPORT } from '../enum/constants';
import { tabTitle } from '../utilities/title';
const { Title } = Typography;

export const InventoryDetail: React.FC = () => {
  tabTitle("D2W - Chi tiết kiểm kê")
  const { id } = useParams();
  const [form] = Form.useForm();
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const [inventoryDetail, setInventoryDetail] = useState<any>({});
  const [warehouseList, setWarehouseList] = useState<any | null>(null);
  const [shelfList, setShelfList] = useState<any[]>([]); 
    const [productList, setProductList] = useState<any[]>([]); 
    const [materialList, setMaterialList] = useState<any[]>([]); 
  const [materialEntries, setMaterialEntries] = useState<MaterialEntry[]>([]);
  const [sendLoading, setSendLoading] = useState(false);
  const [updateLoading, setUpdateLoading] = useState(false);
  const [deleteLoading, setDeleteLoading] = useState(false);
  const navigate = useNavigate();

  const loadInventoryDetail = async () => {
  const response = await inventory.getInventoryDetail(Number(id)); 
  if (response.data) {
    setInventoryDetail(response.data.data);
      const inventoryData = response.data.data;
      console.log(inventoryData);
    form.setFieldsValue({
      name: inventoryData.name,
      description: inventoryData.description,
      warehouse_id: inventoryData.warehouse_id,  
      status: inventoryData.status,
    });

    if (inventoryData.warehouse_id) {
      loadShelfByWarehouse(inventoryData.warehouse_id);
      }
      

const entries = inventoryData.details.map((detail: any, index: number) => ({
    id: `${Date.now()}-${index}`,
    material_id: detail.material_id || detail.product_id,
    product_name: detail.product_name,
    material_name: detail.material_name,
    unit: detail.unit,
    expected_quantity: detail.expected_quantity,
    actual_quantity: detail.actual_quantity,
    note: detail.note,
    shelf_id: detail.shelf_id,
}));

    setMaterialEntries(entries);
  }
};
  const loadWarehouse = async () => { 
    const response = await warehouse.getAllWareHouse(); 
    if (response.data) {
      setWarehouseList(response.data.data);
    }
  };
  const loadShelfByWarehouse = async (warehouseId: number) => {
    const response = await shelf.shelfDetailFilter(warehouseId);
    if (response.data) {
      setShelfList(response.data);
      
    }
  };

    
  
  const sendInventory = async (id: number) => {
      setSendLoading(true);
      const response = await inventory.sendInventory(id); 
      if (response.data) {
        showNotification(response);
      }
    setSendLoading(false);

    };

  const handleWarehouseChange = (value: number) => {
  form.setFieldsValue({
    warehouse_id: value,
  });
  loadShelfByWarehouse(value);  
};


  const filterProduct = (shelfId: any, index: number) => {
  const selectedShelf = shelfList.find((shelf) => shelf.id === shelfId);
  if (selectedShelf) {
    const productData = selectedShelf.warehouse_id === 1
      ? selectedShelf.details.map((item) => ({
          id: item.material_id,
          name: item.material_name,
          quantity: item.quantity,
        }))
      : selectedShelf.details.map((item) => ({
          id: item.product_id,
          name: item.product_name, 
          quantity: item.quantity,
        }));
    setProductList(productData);
    
    form.setFieldsValue({
      [`product_id_${index}`]: productData[0]?.id,
      [`expected_quantity_${index}`]: productData[0]?.quantity || 0,
    });
  }
};

const onFinish = async (values: any) => {
  setUpdateLoading(true);
  const formatData = {
    name: values.name,
    description: values.description,
    warehouse_id: values.warehouse_id,
    status: 0,
    details: materialEntries.map((entry, index) => {
      if (values.warehouse_id === 1) {
        return {
          material_id: values[`product_id_${index}`],  
          product_id: undefined, 
          shelf_id: values[`shelf_id_${index}`],
          expected_quantity: values[`expected_quantity_${index}`],
          actual_quantity: values[`actual_quantity_${index}`],
          note: values[`note_${index}`],
        };
      } else if (values.warehouse_id === 2) {
        return {
          product_id: values[`product_id_${index}`],  
          material_id: undefined, 
          shelf_id: values[`shelf_id_${index}`],
          expected_quantity: values[`expected_quantity_${index}`],
          actual_quantity: values[`actual_quantity_${index}`],
          note: values[`note_${index}`],
        };
      }
    }),
  };


  const response = await inventory.updateInventoryReport(Number(id), formatData);
  
  if (response.data) {
    setUpdateLoading(false);
  }
  showNotification(response);
  setUpdateLoading(false);
};


  const deleteInventoryReport = (id: number) => {
    Modal.confirm({
      title: 'Xác nhận xóa',
      content: 'Bạn có chắc chắn muốn xóa phiếu đề xuất này?',
      onOk: async () => {
        try {
          setDeleteLoading(true);
          const response = await inventory.deleteInventoryReport(id);
          if (response.data) {
            Modal.success({
              content: 'Xóa phiếu đề xuất thành công',
            });
            loadInventoryDetail();
            setTimeout(() => {
              navigate(-1);
            }, 3500);
          } else {
            Modal.error({
              content: 'Có lỗi xảy ra khi xóa phiếu đề xuất',
            });
          }
        } catch (error) {
          Modal.error({
            content: 'Có lỗi xảy ra khi xóa phiếu đề xuất',
          });
        } finally {
          setDeleteLoading(false);
        }
      },
    });
  };

  const addMaterialEntry = () => {
    setMaterialEntries([...materialEntries, { id: Date.now() }]);
  };

  const removeMaterialEntry = (idToRemove: string | number) => {
    setMaterialEntries(materialEntries.filter((entry) => entry.id !== idToRemove));
    };


  useEffect(() => {
    loadInventoryDetail();
      loadWarehouse();
  }, [id]);

  return (
    <div className="max-w-6xl mx-auto p-6">
      <Card className="shadow-lg">
        <Title level={2} className="mb-6 text-center">
          Chi tiết phiếu đề xuất
        </Title>

        <Form
          form={form}
          name="product_form"
          onFinish={onFinish}
          layout="vertical"
          className="space-y-4"
        >
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <Form.Item
              name="name"
              label="Tên phiếu"
              rules={[{ required: true, message: 'Vui lòng nhập tên phiếu!' }]}
            >
              <Input className="rounded-lg" />
            </Form.Item>

            <Form.Item
              name="warehouse_id"
              label="Nhà kho"
              rules={[{ required: true, message: 'Vui lòng chọn nhà kho!' }]}
            >
              <Select
                className="rounded-lg"
                onChange={handleWarehouseChange} 
              >
                {warehouseList?.map((w: any) => (
                  <Select.Option key={w.id} value={w.id}>
                    {w.name}
                  </Select.Option>
                ))}
              </Select>
            </Form.Item>

            <Form.Item name="status" label="Trạng thái">
              <Input className="rounded-lg" disabled />
            </Form.Item>
          </div>

          <Form.Item
            name="description"
            label="Mô tả"
            rules={[{ required: true, message: 'Vui lòng nhập mô tả!' }]}
          >
            <Input.TextArea rows={4} className="rounded-lg" />
          </Form.Item>
          <Divider>Chi tiết</Divider>

          {materialEntries.map((entry, index) => (
  <Card
    key={entry.id}
    className="mb-4 border border-gray-200"
    extra={
      <Button
        danger
        icon={<DeleteOutlined />}
        onClick={() => removeMaterialEntry(entry.id)}
      >
        Xóa
      </Button>
    }
  >
    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
      <Form.Item
        name={`shelf_id_${index}`}
        label="Kệ"
        initialValue={entry.shelf_id}
        rules={[{ required: true, message: 'Vui lòng chọn kệ!' }]}>
        <Select
          onChange={(value) => filterProduct(value, index)}>
          {shelfList?.map((shelf) => (
            <Select.Option key={shelf.id} value={shelf.id}>
              {shelf.name}
            </Select.Option>
          ))}
        </Select>
      </Form.Item>

      {/* Show Product or Material field based on the data */}
                <Form.Item
                name={`product_id_${index}`}
                label={'Thành phẩm/Nguyên vật liệu'}
                initialValue={entry.product_id || entry.material_id}
                rules={[{ required: true, message: 'Vui lòng chọn' }]}>
                <Select
                    onChange={(value) => {
                    const selectedProduct = (entry.material_id ? materialList : productList).find(item => item.id === value);
                    form.setFieldsValue({
                        [`expected_quantity_${index}`]: selectedProduct?.quantity || 0,
                    });
                    }}
                >
                    {(entry.material_id ? materialList : productList)?.map((item) => (
                    <Select.Option key={item.id} value={item.id}>
                        {item.name}  
                    </Select.Option>
                    ))}
            </Select>
            </Form.Item>
      {/* Additional Fields */}
            <Form.Item
                name={`unit_${index}`}
                label="Đơn vị"
                initialValue={entry.unit}
                rules={[{ required: true, message: 'Vui lòng nhập đơn vị!' }]}>
                <Input className="rounded-lg" />
            </Form.Item>
            <Form.Item
                name={`expected_quantity_${index}`}
                  label="Số lượng hệ thống"
                initialValue={entry.expected_quantity}
                rules={[{ required: true, message: 'Vui lòng nhập số lượng!' }]}>
                <Input className="rounded-lg" disabled />
            </Form.Item>
            <Form.Item
                name={`actual_quantity_${index}`}
                label="Số lượng thực tế"
                initialValue={entry.actual_quantity}
                rules={[{ required: true, message: 'Vui lòng nhập số lượng!' }]}>
                <Input className="rounded-lg" />
            </Form.Item>
            <Form.Item
                name={`note_${index}`}
                label="Ghi chú"
                initialValue={entry.note}
                rules={[{ required: true, message: 'Vui lòng nhập ghi chú!' }]}>
                <Input className="rounded-lg" />
            </Form.Item>
            </div>
        </Card>
        ))}
          {userInfor.role_id != 3 && (
            <div className="space-y-4">
              <Button
                type="dashed"
                icon={<PlusOutlined />}
                onClick={addMaterialEntry}
                className="w-full h-12"
              >
                Thêm Nguyên Vật Liệu
              </Button>

              <div className="flex gap-4">
                <Button
                  type="primary"
                  htmlType="submit"
                  className="flex-1 h-12"
                  icon={<SaveOutlined />}
                  loading={updateLoading}
                  disabled={
                    inventoryDetail?.status !== STATUS_INVENTORY_REPORT.PENDING_SEND ||
                    inventoryDetail?.created_by !== userInfor.id
                  }

                >
                  Cập nhật
                </Button>

                <Button
                  type="default"
                  className="flex-1 h-12"
                  icon={<SendOutlined />}
                  onClick={() => sendInventory(Number(id))}
                  disabled={
                    inventoryDetail?.status !== STATUS_INVENTORY_REPORT.PENDING_SEND ||
                    inventoryDetail?.created_by !== userInfor.id
                  }
                  loading={sendLoading}
                >
                  Gửi
                </Button>

              </div>
              <div className="flex justify-center mt-6">
                  <Button
                    type="default"
                    className="w-full h-12 bg-red-500 text-white"
                    icon={<DeleteOutlined />}
                    onClick={() => deleteInventoryReport(Number(id))}
                    loading={deleteLoading} 
                    disabled={
                      inventoryDetail?.status !== STATUS_INVENTORY_REPORT.PENDING_SEND ||
                      inventoryDetail?.created_by !== userInfor.id
                    }
                  >
                    Xóa Phiếu
                  </Button>
              </div>
            </div>
          )}
        </Form>
      </Card>
    </div>
  );
};
