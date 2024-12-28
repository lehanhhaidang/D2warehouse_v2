import { useEffect, useState } from "react";
import { Table, Modal, Button, Pagination, message, Tag } from "antd";
import { getAllOrder, getOrderDetail, confirmOrder, startProcessOrder, cancelOrder } from "../service/order.service"; // Import API functions
import { Footer } from "../components/footer/Footer";
import { Link, useNavigate } from "react-router-dom";
import { IUser } from "../common/interface";
import { tabTitle } from "../utilities/title";
import { getProducts } from "../service/product.service";

export const OrderPage = () => {
  tabTitle("D2W - Đơn hàng");
  const userInfor: IUser = JSON.parse(localStorage.getItem('user') || '{}');
  const [orders, setOrders] = useState<any[]>([]); 
  const [products, setProducts] = useState<any[]>([]);
  const [selectedOrder, setSelectedOrder] = useState<any | null>(null); 
  const [orderDetails, setOrderDetails] = useState<any[]>([]);
  const [modalOpen, setModalOpen] = useState(false); 
  const [currentPage, setCurrentPage] = useState(1); 
  const [rowsPerPage, setRowsPerPage] = useState(10);
  const [confirmLoading, setConfirmLoading] = useState(false);
  const [cancelLoading, setCancelLoading] = useState(false);
  const [insufficientProducts, setInsufficientProducts] = useState<any[]>([]);

  const navigate = useNavigate();


const loadProducts = async () => { 
    const response = await getProducts();
    if (response.data) {
      setProducts(response.data);
      console.log(response.data);
    }
    else {
      message.error('Lỗi khi tải dữ liệu vật liệu');
    }
  };

  useEffect(() => {
    const fetchOrders = async () => {
      const ordersData = await getAllOrder();
      setOrders(ordersData.data.data);
    };
    fetchOrders();
    loadProducts();
  }, []);

  const handleRowClick = async (id: number) => {
    const response = await getOrderDetail(id);
    if (response.data && response.data.data.details) {
      const details = response.data.data.details;
      setOrderDetails(details); 
    } else {
      setOrderDetails([]);
    }
    const order = orders.find(order => order.id === id);
    setSelectedOrder(order);
    setModalOpen(true);
  };

  const handleCloseModal = () => {
    setModalOpen(false);
    setSelectedOrder(null);
  };

const handleConfirmOrder = async () => {
  Modal.confirm({
    title: "Xác nhận đơn hàng",
    content: "Bạn có chắc chắn muốn xác nhận đơn hàng này?",
    onOk: async () => {
      setConfirmLoading(true);
      if (selectedOrder) {
        // Check for insufficient products
        const insufficient: any[] = [];
        selectedOrder.details.forEach((orderItem: any) => {
          const product = products.find((product: any) => product.id === orderItem.product_id);
          if (product && product.quantity < orderItem.quantity) {
            insufficient.push({
              product_name: product.name,
              quantity_difference: orderItem.quantity - product.quantity,
            });
          }
        });

        if (insufficient.length > 0) {
          // Set the insufficient products to show the warning
          setInsufficientProducts(insufficient);
          setConfirmLoading(false);
          return; // Don't proceed with confirming the order if there are insufficient products
        }

        const response = await confirmOrder(selectedOrder.id);
        if (response) {
          message.success({ content: "Đơn hàng đã được xác nhận" });
          setModalOpen(false);
          setConfirmLoading(false);
          setOrders(orders.map(order => 
            order.id === selectedOrder.id ? { ...order, status: 1 } : order
          ));
        } else {
          message.error({ content: "Xác nhận đơn hàng thất bại" });
        }
      }
    },
    onCancel: () => {},
  });
};



  const handleCancelOrder = async () => {
      Modal.confirm({
            title: "Hủy đơn hàng",
            content: "Bạn có chắc chắn muốn hủy đơn hàng này?",
            onOk: async () => {
                setCancelLoading(true);
                if (selectedOrder) {
                    const response = await cancelOrder(selectedOrder.id);
                    if (response) {
                        message.success({ content: "Đơn hàng đã bị hủy" });
                        setModalOpen(false);
                        setCancelLoading(false);
                        setOrders(orders.map(order => 
                            order.id === selectedOrder.id ? { ...order, status: 4 } : order
                        ));
                    } else {
                        message.error({ content: "Hủy đơn hàng thất bại" });
                    }
                }
            },

            onCancel: () => {},
    })
  };

  const handleStartProcessOrder = async () => {
      Modal.confirm({  
        title: "Bắt đầu xử lý đơn hàng",
        content: "Bạn có chắc chắn muốn bắt đầu xử lý đơn hàng này? Nhấn OK để đi đến trang tạo đề xuất xuất thành phẩm.",
        onOk: async () => {
          if (selectedOrder) {
            const response = await startProcessOrder(selectedOrder.id);
            if (response) {
              message.success({ content: "Đơn hàng đã được bắt đầu xử lý" });
              setModalOpen(false);
              setOrders(orders.map(order => 
                order.id === selectedOrder.id ? { ...order, status: 2 } : order
              ));
              navigate("/manager-product-export");
            } else {
              message.error({ content: "Bắt đầu xử lý đơn hàng thất bại" });
            }
          }
          
        },
        onCancel: () => {},
    })
  };

//   const handleCompleteOrder = async () => {
//     if (selectedOrder) {
//       const response = await completeOrder(selectedOrder.id);
//       if (response) {
//         message.success({ content: "Đơn hàng đã hoàn thành" });
//         setModalOpen(false);
//         setOrders(orders.map(order => 
//           order.id === selectedOrder.id ? { ...order, status: 3 } : order
//         ));
//       } else {
//         message.error({ content: "Hoàn thành đơn hàng thất bại" });
//       }
//     }
//   };

  // Cột bảng đơn hàng
  const columns = [
    { title: "STT", dataIndex: "id", key: "id" },
    { title: "Tên đơn hàng", dataIndex: "name", key: "name" },
    { title: "Khách hàng", dataIndex: "customer_name", key: "customer_name" },
    { title: "Ngày đặt", dataIndex: "order_date", key: "order_date" },
    { title: "Ngày giao dự kiến", dataIndex: "delivery_date", key: "delivery_date" },
    { title: "Trạng thái", dataIndex: "status", key: "status", render: (status: number) => {
      switch (status) {
        case 0: return <Tag color="blue">Chưa xác nhận</Tag>;
        case 1: return <Tag color="orange">Đã xác nhận</Tag>;
        case 2: return <Tag color="green">Đang xử lý</Tag>;
        case 3: return <Tag color="green-inverse">Đã hoàn thành</Tag>;
        case 4: return <Tag color="red">Đã hủy</Tag>;
        default: return "Chưa xác định";
      }
    }},
    { title: "Tổng giá trị", dataIndex: "total_price", key: "total_price", render: (text: number) => `${text.toLocaleString()} VND` },
  ];

  // Cột chi tiết đơn hàng
  const detailColumns = [
    { title: "Sản phẩm", dataIndex: "product_name", key: "product_name" },
    { title: "Đơn vị", dataIndex: "unit", key: "unit" },
    { title: "Số lượng", dataIndex: "quantity", key: "quantity" },
    { title: "Giá", dataIndex: "price", key: "price", render: (text: number) => `${text.toLocaleString()} VND` },
    { title: "Tổng giá", dataIndex: "total_price", key: "total_price", render: (text: number) => `${text.toLocaleString()} VND` },
  ];

  return (
    <>
     
      <div className="flex w-full justify-center bg-slate-300" style={{ height: "calc(85vh)" }}>
        <div className="flex flex-col w-4/5 pt-6 pb-10 px-4 bg-slate-100 scrollable-content">
          {/* Table for Orders */}
          <div className="responsive-table">
            <Table
              columns={columns}
              dataSource={orders.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage)}
              rowKey="id"
              onRow={(record) => ({
                onClick: () => handleRowClick(record.id),
              })}
              pagination={false}
            />
          </div>

          {/* Pagination */}
          <div className="flex justify-end mt-4">
            <Pagination
              current={currentPage}
              pageSize={rowsPerPage}
              total={orders.length}
              onChange={(page, pageSize) => {
                setCurrentPage(page);
                setRowsPerPage(pageSize);
              }}
              showSizeChanger
              showTotal={(total) => `Tổng cộng ${total} đơn hàng`}
            />
          </div>
        </div>
      </div>

      {/* Modal for Order Details */}
      <Modal
        title={`Chi tiết ${selectedOrder?.name}`}
        open={modalOpen}
        onCancel={handleCloseModal}
        footer={[
          selectedOrder?.status === 0 && userInfor?.role_id === 2 &&(
            <Button key="cancel" onClick={handleCancelOrder} type="primary" className="bg-red-500" loading={cancelLoading}>
              Hủy đơn hàng
            </Button>
          ),
          selectedOrder?.status === 0 && userInfor?.role_id === 2 && (
            <Button key="confirm" onClick={handleConfirmOrder} type="primary" loading={confirmLoading}>
              Xác nhận đơn hàng
            </Button>
          ),
          selectedOrder?.status === 1 && userInfor?.role_id === 4 && (
            <Button key="startProcess" onClick={handleStartProcessOrder} type="primary">
              Bắt đầu xử lý đơn hàng
            </Button>
          ),
        //   selectedOrder?.status === 2 && (
        //     <Button key="complete" onClick={handleCompleteOrder} type="primary">
        //       Hoàn thành đơn hàng
        //     </Button>
        //     ),
            <Button key="close" onClick={handleCloseModal} className="bg-gray-500 text-white hover:bg-gray-600 rounded px-4 py-2">
            Đóng
          </Button>,
        ]}
        width={800}
        className="p-6"
      >
        {/* Check if orderDetails is not empty before rendering */}
        {orderDetails.length === 0 ? (
          <p className="text-center text-gray-500">Không có chi tiết cho đơn hàng này.</p>
        ) : (
          <div>
            <div className="mb-6 space-y-4">
              <div><strong className="font-semibold text-gray-700">Tên đơn hàng:</strong> {selectedOrder?.name}</div>
              <div><strong className="font-semibold text-gray-700">Khách hàng:</strong> {selectedOrder?.customer_name}</div>
              <div><strong className="font-semibold text-gray-700">Email:</strong> {selectedOrder?.customer_email}</div>
              <div><strong className="font-semibold text-gray-700">Số điện thoại:</strong> {selectedOrder?.customer_phone}</div>
              <div><strong className="font-semibold text-gray-700">Địa chỉ:</strong> {selectedOrder?.customer_address}</div>
              <div><strong className="font-semibold text-gray-700">Ngày đặt:</strong> {selectedOrder?.order_date}</div>
              <div><strong className="font-semibold text-gray-700">Ngày giao:</strong> {selectedOrder?.delivery_date}</div>
              <div><strong className="font-semibold text-gray-700">Ghi chú:</strong> {selectedOrder?.note}</div>
              <div><strong className="font-semibold text-gray-700">Tổng giá trị:</strong> {selectedOrder?.total_price.toLocaleString()} VND</div>
            </div>

            <h3 className="text-sm font-semibold text-gray-800 mb-4">Chi Tiết Sản Phẩm</h3>
            <Table
              className="w-full text-sm text-left border-collapse"
              columns={detailColumns}
              dataSource={orderDetails}
              rowKey="product_id"
              pagination={false}
              />
              {insufficientProducts.length > 0 && (
              <div className="mt-4 text-red-500">
                <strong>Lưu ý:</strong> Có thành phẩm thiếu trong kho, vui lòng kiểm tra lại ở: 
                <Link to={'/product'} className='text-blue-500'> Thành phẩm</Link>
                <ul>
                  {insufficientProducts.map((item: any, index: number) => (
                    <li key={index}>
                      <strong>{item.product_name}</strong> - Số lượng thiếu: <strong>{ item.quantity_difference}</strong>
                    </li>
                  ))}
                </ul>
                Hoặc
                <br />
                <Link className="text-blue-500" to={'/manufacturing-plan'}>
                  Tạo kế hoạch sản xuất thêm thành phẩm
                </Link>
              </div>
            )}

          </div>
        )}
      </Modal>

      <Footer />
    </>
  );
};
