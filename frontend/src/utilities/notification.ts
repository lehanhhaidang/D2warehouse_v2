import { message } from "antd";

export const showNotification = (response) => {
  if (response.status === 200) {
    message.success(response.data.message);
  } else {
    message.error(response.error?.message || 'Đã xảy ra lỗi');
  }
};