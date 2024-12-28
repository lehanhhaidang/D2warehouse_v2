
import { fetchAPI } from "../utilities/fetchAPI";

// Định nghĩa Notification
export interface Notification {
  message: string;
  created_at: string;
  url?: string;
}

const handleWarehouseCreated = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/warehouses`,
});
const handleWarehouseUpdated = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/warehouses`,
});
const handleWarehouseDeleted = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/warehouses`,
});

const handleShelfCreated = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/shelves`,
});
const handleShelfUpdated = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/shelves`,
});
const handleShelfDeleted = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/shelves`,
});


// Các hàm xử lý riêng biệt cho từng loại sự kiện
const handleProductCreated = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/product`,
});
const handleProductUpdated = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/product`,
});
const handleProductDeleted = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/product`,
});

// Các hàm xử lý tương tự cho Material
const handleMaterialCreated = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/material`,
});
const handleMaterialUpdated = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/material`,
});
const handleMaterialDeleted = (data: any): Notification => ({
  message: data.message,
  created_at: new Date().toISOString(),
  url: `/material`,
});

// Các hàm xử lý cho Propose
const handleProposeCreated = (data: any, userId: string): Notification => ({
  message: data.propose_created_by === userId ? data.message : null,
  created_at: new Date().toISOString(),
  url: `/manager-detail/${data.propose_id}`,
});

const handleProposeDeleted = (data: any, userId: string): Notification => ({
  message: data.propose_created_by === userId ? data.message : null,
  created_at: new Date().toISOString(),
  url: (() => {
    switch (data.propose_type) {
      case 'DXNTP':
        return `/manager-product-import`;
      case 'DXXTP':
        return `/manager-product-export`;
      case 'DXNNVL':
        return `/manager-import`;
      case 'DXXNVL':
        return `/manager-export`;
      default:
        return null; // Hoặc đường dẫn mặc định nếu cần
    }
  })(),
});

const handleProposeSent = (data: any, userId: string, userRole: any): Notification | null => {
  let message: string | null = null;
  if (data.propose_created_by === userId) message = data.owner_message;
  else if (userRole === 2 || userRole === 3)
    message = data.other_message;

  if (!message) return null;
  return {
    message,
    created_at: new Date().toISOString(),
    url: `/detail-propose/${data.propose_id}`
  };

};
const handleProposeUpdated = (data: any, userId: string): Notification => ({
  message: data.propose_created_by === userId ? data.message : null,
  created_at: new Date().toISOString(),
  url: `/manager-detail/${data.propose_id}`,
});

const handleProposeAccepted = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if (data.propose_created_by === userId) message = data.owner_message;
  else if (data.reviewer_id === userId) message = data.reviewer_message;
  else if (data.assigned_to === userId) message = data.employee_message;

  if (!message) return null;

  const url = (() => { 
    if (data.propose_created_by === userId) {
      return `/manager-detail/${data.propose_id}`;
    } 
    if (data.reviewer_id === userId) {
      return `/detail-propose/${data.propose_id}`;
    } 
    if (data.assigned_to === userId) {
      return `/manager-detail/${data.propose_id}`;
    } 
    return null;
  })();

  return {
    message,
    created_at: new Date().toISOString(),
    url,
  };
};


const handleProposeRejected = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if (data.propose_created_by === userId) message = data.owner_message;
  else if (data.reviewer_id === userId) message = data.reviewer_message;
  if (!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/detail-propose/${data.propose_id}` };
};


const handleProductReceiptCreated = (data: any, userId: string): Notification | null => {
  const message:string = data.product_receipt_created_by === userId 
    ? data.employee_message 
    : data.manager_message;
  return { message, created_at: new Date().toISOString(), url: `/notes?filter=nhập thành phẩm` };
};
const handleMaterialReceiptCreated = (data: any, userId: string): Notification | null => {
  const message = data.product_receipt_created_by === userId 
    ? data.employee_message 
    : data.manager_message;
  return { message, created_at: new Date().toISOString(), url: `/notes?filter=nhập nguyên vật liệu`};
};
const handleProductExportCreated = (data: any, userId: string): Notification | null => {
  const message = data.product_receipt_created_by === userId 
    ? data.employee_message 
    : data.manager_message;
  return { message, created_at: new Date().toISOString(), url: `/notes?filter=xuất thành phẩm` };
};
const handleMaterialExportCreated = (data: any, userId: string): Notification | null => {
  const message = data.product_receipt_created_by === userId 
    ? data.employee_message 
    : data.manager_message;
  return { message, created_at: new Date().toISOString(), url: `/notes?filter=xuất nguyên vật liệu` };
};

const handleInventoryReportCreated = (data: any, userId: string): Notification | null => { 
  let message: string | null = null;
  if (data.inventoryReport_created_by === userId) message = data.owner_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(),  url: `/inventory-detail/${data.inventoryReport_id}` };
};
const handleInventoryReportUpdated = (data: any, userId: string): Notification | null => { 
  let message: string | null = null;
  if (data.inventoryReport_created_by === userId) message = data.owner_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(),  url: `/inventory-detail/${data.inventoryReport_id}` };
};
const handleInventoryReportDeleted = (data: any, userId: string): Notification | null => { 
  let message: string | null = null;
  if (data.inventoryReport_created_by === userId) message = data.owner_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(),url: `/inventory-reports` };
};
const handleInventoryReportSent = (data: any, userId: string, userRole: any): Notification | null => {
  let message: string | null = null;
  if (data.inventoryReport_created_by === userId) message = data.owner_message;
  else if(userRole === 2)
    message = data.other_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/inventory-table/${data.inventoryReport_id}` };
};
const handleInventoryReportConfirmed = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if (data.inventoryReport_created_by === userId) message = data.owner_message;
  else if (data.reviewer_id === userId) message = data.reviewer_message;
  else message = data.public_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/inventory-table/${data.inventoryReport}` };
};

const handleInventoryReportRejected = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if (data.inventoryReport_created_by === userId) message = data.owner_message;
  else if(data.reviewer_id === userId) message = data.reviewer_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/inventory-table/${data.inventoryReport}` };
};

const handleInventoryReportPassed = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if (data.inventoryReport_created_by === userId) message = data.owner_message;
  else if (data.reviewer_id === userId) message = data.reviewer_message;
  else message = data.public_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/inventory-table/${data.inventoryReport}` };
};

const handleInventoryReportCancelled = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if (data.inventoryReport_created_by === userId) message = data.owner_message;
  else if(data.reviewer_id === userId) message = data.reviewer_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/inventory-table/${data.inventoryReport}` };
};

//Manufacturing Plan
const handleManufacturingPlanCreated = (data: any, userId: string): Notification | null => { 
  let message: string | null = null;
  if (data.manufacturingPlan_created_by === userId) message = data.owner_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(),  url: `/manufacturing-detail/${data.manufacturingPlan_id}` };
};

const handleManufacturingPlanDeleted = (data: any, userId: string): Notification | null => { 
  let message: string | null = null;
  if (data.manufacturingPlan_created_by === userId) message = data.owner_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(),url: `/manufacturing-plan` };
};

const handleManufacturingPlanSent = (data: any, userId: string, userRole: any): Notification | null => {
  let message: string | null = null;
  if (data.manufacturingPlan_created_by === userId) message = data.owner_message;
  else if(userRole === 3)
    message = data.other_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/manufacturing-detail/${data.manufacturingPlan_id}` };
};

const handleManufacturingPlanConfirmed = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if (data.manufacturingPlan_created_by === userId) message = data.owner_message;
  else if (data.reviewer_id === userId) message = data.reviewer_message;
  else message = data.public_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/manufacturing-detail/${data.manufacturingPlan}` };
};

const handleManufacturingPlanRejected = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if (data.manufacturingPlan_created_by === userId) message = data.owner_message;
  else if(data.reviewer_id === userId) message = data.reviewer_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/manufacturing-detail/${data.manufacturingPlan}` };
};

const handleManufacturingPlanBegin = (data: any, userId: string, userRole: any): Notification | null => {
  let message: string | null = null;
  if (userId === data.begin_by) message = data.owner_message;
  else if(userRole === 3 || userRole === 2 || userRole ===4 ) message = data.other_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/manufacturing-detail/${data.manufacturingPlan_id}` };
};

const handleManufacturingPlanFinish = (data: any, userId: string, userRole: any): Notification | null => {
  let message: string | null = null;
  if (userId === data.finish_by) message = data.owner_message;
  else if(userRole===2||userRole===3||userRole===4)message = data.other_message;
  if(!message) return null;
  return { message, created_at: new Date().toISOString(), url: `/manufacturing-detail/${data.manufacturingPlan_id}` };
};


const handleOrderConfirmed = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if(data.reviewer_id === userId) message = data.reviewer_message;
  else message = data.public_message;
  if (!message) return null;
  
  return { message, created_at: new Date().toISOString(), url: `/orders` };
};

const handleOrderCancelled = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if(data.reviewer_id === userId) message = data.reviewer_message;
  else message = data.public_message;
  if (!message) return null;
  
  return { message, created_at: new Date().toISOString(), url: `/orders` };
};

const handleOrderInProcess = (data: any, userId: string): Notification | null => {
  let message: string | null = null;
  if(data.reviewer_id === userId) message = data.reviewer_message;
  else message = data.public_message;
  if (!message) return null;
  
  return { message, created_at: new Date().toISOString(), url: `/orders` };
};








// Đặt các hàm này vào một đối tượng ánh xạ để sử dụng chung
const notificationHandlers: Record<string, Function> = {
  'warehouse.created': handleWarehouseCreated,
  'warehouse.updated': handleWarehouseUpdated,
  'warehouse.deleted': handleWarehouseDeleted,

  'shelf.created': handleShelfCreated,
  'shelf.updated': handleShelfUpdated,
  'shelf.deleted': handleShelfDeleted,

  'product.created': handleProductCreated,
  'product.updated': handleProductUpdated,
  'product.deleted': handleProductDeleted,

  'material.created': handleMaterialCreated,
  'material.updated': handleMaterialUpdated,
  'material.deleted': handleMaterialDeleted,

  'propose.created': handleProposeCreated,
  'propose.deleted': handleProposeDeleted,
  'propose.sent': handleProposeSent,
  'propose.updated': handleProposeUpdated,
  'propose.accepted': handleProposeAccepted,
  'propose.rejected': handleProposeRejected,

  'product-receipt.created': handleProductReceiptCreated,

  'material-receipt.created': handleMaterialReceiptCreated,

  'material-export.created': handleMaterialExportCreated,

  'product-export.created': handleProductExportCreated,

  'inventory-report.created': handleInventoryReportCreated,
  'inventory-report.updated': handleInventoryReportUpdated,
  'inventory-report.deleted': handleInventoryReportDeleted,
  'inventory-report.sent': handleInventoryReportSent,
  'inventory-report.confirmed': handleInventoryReportConfirmed,
  'inventory-report.rejected': handleInventoryReportRejected,
  'inventory-report.passed': handleInventoryReportPassed,
  'inventory-report.cancelled': handleInventoryReportCancelled,

  'manufacturing-plan.created': handleManufacturingPlanCreated,
  'manufacturing-plan.deleted': handleManufacturingPlanDeleted,
  'manufacturing-plan.sent': handleManufacturingPlanSent,
  'manufacturing-plan.confirmed': handleManufacturingPlanConfirmed,
  'manufacturing-plan.rejected': handleManufacturingPlanRejected,
  'manufacturing-plan.begin': handleManufacturingPlanBegin,
  'manufacturing-plan.finish': handleManufacturingPlanFinish,

  'order.confirmed': handleOrderConfirmed,
  'order.cancelled': handleOrderCancelled,
  'order.in-process': handleOrderInProcess,
};

export const handleNewNotification = (data: any, userId: string, userRole: any): Notification | null => {
  const handler = notificationHandlers[data.event];
  if (handler) {
    return handler(data, userId, userRole);  // Gọi hàm xử lý tương ứng
  }
  return {
    message: data.message|| 'New notification',
    created_at: new Date().toISOString(),
  };
};

export const getNotification = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_NOTIFICATIONS);
  return response;
};

export const updateNotificationStatus = async (): Promise<any> => {
  const response = await fetchAPI(
    {
      method: 'PATCH',
      data: {},  // Có thể thêm dữ liệu nếu cần, ví dụ gửi userId nếu backend yêu cầu
    },
    import.meta.env.VITE_UPDATE_NOTI_STATUS
  );
  return response;
};


