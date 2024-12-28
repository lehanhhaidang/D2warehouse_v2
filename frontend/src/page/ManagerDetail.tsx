import { useParams } from 'react-router-dom';
import * as propose from '../service/propose.service';
import { useEffect, useState } from 'react';
import {
  Form,
  Input,
  Select,
  Button,
  message,
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
import * as material from '../service/material.service';
import * as warehouse from '../service/warehouse.service';
import * as product from '../service/product.service';
import { IUser, MaterialEntry } from '../common/interface';
import { STATUS_PROPOSE } from '../enum/constants';
import { MaterialReceiptImport } from './MaterialReceiptImport';
import { LoadingOutlined } from '@ant-design/icons';
import { showNotification } from '../utilities/notification';
import { useNavigate } from 'react-router-dom';

const { Title } = Typography;

export const ManagerDetail = () => {
  const { id } = useParams();
  const [form] = Form.useForm();
  const [proposeDetail, setProposeDetail] = useState<any | null>(null);
  const [materialList, setMaterialList] = useState<any | null>(null);
  const [productList, setProductList] = useState<any | null>(null);
  const [warehouseList, setWarehouseList] = useState<any | null>(null);
  const [type, setType] = useState('');
  const [materialEntries, setMaterialEntries] = useState<MaterialEntry[]>([]);
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const [employeeList, setEmployeeList] = useState<any | null>(null);
  const [loading, setLoading] = useState(false);
  const [updateLoading, setUpdateLoading] = useState(false);
  const [deleteLoading, setDeleteLoading] = useState(false);
  const navigate = useNavigate();
  const loadData = async () => {
    const response = await propose.loadProposeDetail(Number(id));
    if (response.data) {
      setProposeDetail(response.data);
      form.setFieldsValue({
        name: response.data.name,
        description: response.data.description,
        warehouse_id: response.data.warehouse_id,
        type: response.data.type,
        assigned_to: response.data.assigned_to,
      });
      setType(response.data.type);
      const entries = response.data.details.map(
        (detail: any, index: number) => ({
          id: `${Date.now()}-${index}`,
          material_id: detail.material_id || detail.product_id,
          unit: detail.unit,
          quantity: detail.quantity,
          note: detail.note,
        })
      );
      setMaterialEntries(entries);
    }
  };

  const loadEmployee = async (id: number) => {
    const response = await warehouse.loadEmpoyee(id);
    if (response.data) {
      setEmployeeList(response.data);
    }
  };

  const loadMaterial = async () => {
    const response = await material.getMaterials();
    if (response.data) {
      setMaterialList(response.data);
    }
  };

  const loadWarehouse = async () => {
    const response = await warehouse.getAllWareHouse();
    if (response.data) {
      setWarehouseList(response.data.data);
    }
  };

  const loadProduct = async () => {
    const response = await product.getProducts();
    if (response.data) {
      setProductList(response.data);
    }
  };

  const addMaterialEntry = () => {
    setMaterialEntries([...materialEntries, { id: Date.now() }]);
  };

  useEffect(() => {
    loadData();
    loadMaterial();
    loadWarehouse();
    loadProduct();
  }, [id]);
  if (!proposeDetail) {
    return <div className="text-center text-5xl mt-96 text-gray-600 animate-pulse"><LoadingOutlined className='text-blue-500'/></div>;;
  }

  const sendPropose = async (id: number) => {
    setLoading(true);
    const response = await propose.sendPropose(id);
    if (response.data) {
      message.success('Gửi thành công');
      setLoading(false);
    }
  };
  const onFinish = async (values: any) => {
    setUpdateLoading(true);
    const formattedData = {
      id: proposeDetail.id,
      name: values.name,
      type: values.type,
      warehouse_id: values.warehouse_id,
      warehouse_name:
        warehouseList.find((w: any) => w.id === values.warehouse_id)?.name ||
        '',
      status: proposeDetail.status,
      description: values.description,
      created_by: proposeDetail.created_by,
      created_at: proposeDetail.created_at,
      updated_at: new Date().toISOString(),
      assigned_to:
        type === 'DXNNVL' || type === 'DXXNVL' ? values.assigned_to : null,
      details: materialEntries.map((entry, index) => ({
        propose_id: proposeDetail.id,
        product_id:
          type !== 'DXNNVL' &&
          type !== 'DXXNVL' &&
          values[`material_id_${index}`]
            ? values[`material_id_${index}`]
            : null,
        product_name:
          type !== 'DXNNVL' &&
          type !== 'DXXNVL' &&
          values[`material_id_${index}`]
            ? productList.find(
                (p: any) => p.id === values[`product_id_${index}`]
              )?.name || ''
            : null,
        material_id:
          type !== 'DXNTP' && type !== 'DXXTP' && values[`material_id_${index}`]
            ? values[`material_id_${index}`]
            : null,
        material_name:
          type !== 'DXNTP' && type !== 'DXXTP' && values[`material_id_${index}`]
            ? materialList.find(
                (m: any) => m.id === values[`material_id_${index}`]
              )?.name || ''
            : null,
        unit: values[`unit_${index}`],
        quantity: values[`quantity_${index}`],
        note: values[`note_${index}`],
      })),
    };
    const response = await propose.updateProposeDetail(
      Number(id),
      formattedData
    );
    if (response.data) {
      setUpdateLoading(false);
    }
    showNotification(response);
    setUpdateLoading(false);
  };


  const deletePropose = (id: number) => {
  Modal.confirm({
    title: 'Xác nhận xóa',
    content: 'Bạn có chắc chắn muốn xóa phiếu đề xuất này?',
    onOk: async () => {
      try {
        setDeleteLoading(true);
        const response = await propose.deletePropose(id);
        if (response.data) {
          Modal.success({
            content: 'Xóa phiếu đề xuất thành công',
          });
          
          loadData();  

          setTimeout(() => {
            navigate(-1);
          }, 3500);

        } else {
          Modal.error({
            content:  'Có lỗi xảy ra khi xóa phiếu đề xuất',
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



  const removeMaterialEntry = (idToRemove: string | number) => {
    setMaterialEntries(
      materialEntries.filter((entry) => entry.id !== idToRemove)
    );
  };

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
                onChange={(value) => {
                  loadEmployee(value);
                }}
              >
                {warehouseList?.map((w: any) => (
                  <Select.Option key={w.id} value={w.id}>
                    {w.name}
                  </Select.Option>
                ))}
              </Select>
            </Form.Item>

            {(type === 'DXNNVL' || type === 'DXXNVL') && (
              <Form.Item
                name="assigned_to"
                label="Nhân viên"
                rules={[
                  { required: true, message: 'Vui lòng chọn nhân viên!' },
                ]}
              >
                <Select className="rounded-lg">
                  {employeeList?.map((w: any) => (
                    <Select.Option key={w.id} value={w.id}>
                      {w.name}
                    </Select.Option>
                  ))}
                </Select>
              </Form.Item>
            )}
          </div>

          <Form.Item
            name="description"
            label="Mô tả"
            rules={[{ required: true, message: 'Vui lòng nhập mô tả!' }]}
          >
            <Input.TextArea rows={4} className="rounded-lg" />
          </Form.Item>

          <Form.Item
            name="type"
            label="Loại"
            rules={[{ required: true, message: 'Vui lòng chọn loại!' }]}
          >
            <Select
              onChange={(value) => setType(value)}
              disabled={true}
              className="rounded-lg"
            >
              <Select.Option value="DXNNVL">
                Đề xuất nhập nguyên vật liệu
              </Select.Option>
              <Select.Option value="DXXNVL">
                Đề xuất xuất nguyên vật liệu
              </Select.Option>
            </Select>
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
                  disabled={
                    proposeDetail?.status !== STATUS_PROPOSE.PENDING_SEND ||
                    proposeDetail?.created_by !== userInfor.id || type==='DXXTP'
                  }
                >
                  Xóa
                </Button>
              }
            >
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Form.Item
                  name={`material_id_${index}`}
                  label="Tên thành phẩm/nguyên vật liệu"
                  initialValue={entry.material_id}
                  rules={[{ required: true, message: 'Vui lòng chọn!' }]}
                >
                  <Select className="rounded-lg" disabled ={type==='DXXTP'}>
                    {type === 'DXNNVL' || type === 'DXXNVL' ? (
                      materialList?.map((material: any) => (
                        <Select.Option key={material.id} value={material.id}>
                          {material.name}
                        </Select.Option>
                      ))
                    ) : (
                      productList?.map((product: any) => (
                        <Select.Option key={product.id} value={product.id}>
                          {product.name}
                        </Select.Option>
                      ))
                    )}
                  </Select>
                </Form.Item>
                <Form.Item
                  name={`unit_${index}`}
                  label="Đơn vị"
                  initialValue={entry.unit}
                  rules={[{ required: true, message: 'Vui lòng nhập đơn vị!' }]}
                >
                  <Input className="rounded-lg" disabled ={type==='DXXTP'} />
                </Form.Item>

                <Form.Item
                  name={`quantity_${index}`}
                  label="Số lượng"
                  initialValue={entry.quantity}
                  rules={[
                    { required: true, message: 'Vui lòng nhập số lượng!' },
                  ]}
                >
                  <Input className="rounded-lg" disabled ={type==='DXXTP'}/>
                </Form.Item>
                
              </div>
              <Form.Item
                  name={`note_${index}`}
                  label="Ghi chú"
                  initialValue={entry.note}
                >
                  <Input className="rounded-lg max-w-full" disabled ={type==='DXXTP'} />
                </Form.Item>
            </Card>
          ))}

          {userInfor.role_id != 3 && (
            <div className="space-y-4">
              <Button
                type="dashed"
                icon={<PlusOutlined />}
                onClick={addMaterialEntry}
                className="w-full h-12"
                disabled={
                  proposeDetail?.status !== STATUS_PROPOSE.PENDING_SEND ||
                  proposeDetail?.created_by !== userInfor.id || type === 'DXXTP'
                }
              >
                Thêm thành phẩm/nguyên vật liệu
              </Button>

              <div className="flex gap-4">
                <Button
                  type="primary"
                  htmlType="submit"
                  className="flex-1 h-12"
                  icon={<SaveOutlined />}
                  disabled={
                    proposeDetail?.status !== STATUS_PROPOSE.PENDING_SEND ||
                    proposeDetail?.created_by !== userInfor.id || type === 'DXXTP'
                  }
                  loading={updateLoading}
                >
                  Cập nhật
                </Button>

                <Button
                  type="default"
                  className="flex-1 h-12"
                  icon={<SendOutlined />}
                  onClick={() => sendPropose(Number(id))}
                  disabled={
                    proposeDetail?.status !== STATUS_PROPOSE.PENDING_SEND ||
                    proposeDetail?.created_by !== userInfor.id
                  }
                  loading={loading}
                >
                  Gửi
                </Button>
                
              </div>
             <div className="flex justify-center mt-6">
                <Button
                  type="default"
                  className="w-full h-12 bg-red-500 text-white"
                  icon={<DeleteOutlined />}
                  onClick={() => deletePropose(Number(id))}
                  loading={deleteLoading} 
                  disabled={
                    proposeDetail?.status !== STATUS_PROPOSE.PENDING_SEND ||
                    proposeDetail?.created_by !== userInfor.id
                  }
                >
                  Xóa Phiếu
                </Button>
              </div>
            </div>
          )}
        </Form>
      </Card>
      {proposeDetail?.status === STATUS_PROPOSE.APPROVED &&
        userInfor?.role_id === 4 && (
          <Card className="mt-4">
            <Title level={4} className="mb-4">
              Tạo phiếu
            </Title>
            <MaterialReceiptImport proposeDetail={proposeDetail} />
          </Card>
        )}
    </div>
  );
};
