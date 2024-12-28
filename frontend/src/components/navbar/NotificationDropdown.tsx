import React, { useEffect, useState } from "react";
import { Badge, Popover } from "antd";
import { BellOutlined } from "@ant-design/icons";
import { getNotification, handleNewNotification, Notification } from "../../service/notification.service";
import { usePusher, getDefaultSubscriptions } from "../../service/pusher.service";
import { Link } from "react-router-dom";
import { updateNotificationStatus } from "../../service/notification.service";  // Thêm import cho updateNotificationStatus

const NotificationDropdown: React.FC = () => {
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [unreadCount, setUnreadCount] = useState(0);

  const user = JSON.parse(localStorage.getItem("user") || "{}");
  const userId = user?.id;
  const userRole = user?.role_id;

  const handleNotification = (data: any) => {
    const newNotification = handleNewNotification(data, userId, userRole);

    if (newNotification && newNotification.message !== null) {
      setNotifications((prevNotifications) => {
        const uniqueNotifications = new Map(
          prevNotifications.map((notification) => [notification.created_at + notification.message, notification])
        );
        uniqueNotifications.set(newNotification.created_at + newNotification.message, newNotification);
        return Array.from(uniqueNotifications.values());
      });
      setUnreadCount((prevCount) => prevCount + 1);
    }
  };

  usePusher(getDefaultSubscriptions(handleNotification));

  useEffect(() => {
    const fetchNotifications = async () => {
      const response = await getNotification();
      if (response?.data) {
        const fetchedNotifications = response.data.map((data: any) => {
          const notification = handleNewNotification(data, userId, userRole);
          return {
            message: data.message,
            created_at: data.created_at,
            url: data.url || notification?.url,
            status: data.status || 0,  // Thêm trạng thái vào thông báo
          };
        });
        setNotifications(fetchedNotifications);
        setUnreadCount(fetchedNotifications.filter(n => n.status === 0).length);  // Cập nhật số lượng thông báo chưa đọc
      }
    };

    fetchNotifications();
  }, [userId, userRole]);

  const notificationList = (
    <div className="w-80 max-h-96 overflow-auto bg-white shadow-lg rounded-md">
      {notifications.length > 0 ? (
        [...notifications].reverse().map((notification, index) => (
          <div key={index} className="px-4 py-2 border-b last:border-none hover:bg-gray-100 transition">
            <p className="text-sm text-gray-800">
              {notification.url ? (
                <Link to={notification.url} reloadDocument>
                  {notification.message}
                </Link>
              ) : (
                notification.message
              )}
            </p>

            <span className="text-xs text-gray-500">
              {new Date(notification.created_at).toLocaleTimeString()} - {new Date(notification.created_at).toLocaleDateString()}
            </span>
          </div>
        ))
      ) : (
        <div className="px-4 py-2 text-center text-gray-500">No new notifications</div>
      )}
    </div>
  );

  const handleClick = async () => {
    setUnreadCount(0);
    if (unreadCount > 0) {
      // Gọi API để cập nhật trạng thái của tất cả thông báo thành "đã đọc" khi nhấn chuông
      const response = await updateNotificationStatus();
      if (response?.success) {
        setNotifications((prevNotifications) =>
          prevNotifications.map((notification) => ({ ...notification, status: 1 }))
        );
      }
    }
  };

  return (
    <Popover content={notificationList} title="Notifications" trigger="click" placement="bottomRight" overlayClassName="shadow-lg">
      <Badge count={unreadCount} offset={[10, 0]}>
        <BellOutlined
          className="text-2xl cursor-pointer text-white hover:text-gray-800 transition"
          onClick={handleClick}
        />
      </Badge>
    </Popover>
  );
};

export default NotificationDropdown;
