import { useEffect, useRef } from "react";
import Pusher from "pusher-js";

class PusherService {
  private pusher: Pusher | null = null;
  private channels: { [key: string]: Pusher.Channel } = {};
  
  constructor() {
    this.initPusher();
    window.addEventListener('storage', this.onStorageChange);
  }

  // Khởi tạo lại kết nối Pusher nếu token thay đổi
  private initPusher() {
    const token = localStorage.getItem("token");
    if (token && !this.pusher) {
      this.pusher = new Pusher("8a44b6c8198314f04256", {
        cluster: "ap1",
        authEndpoint: "http://localhost:8000/broadcasting/auth",
        auth: {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        },
      });
    }
  }

  // Lắng nghe sự thay đổi của token trong localStorage
  private onStorageChange = (e: StorageEvent) => {
    if (e.key === "token" && e.newValue !== e.oldValue) {
      // Token đã thay đổi, tái khởi tạo kết nối
      this.pusher?.disconnect();
      this.initPusher();
    }
  };

  subscribeToChannel(channelName: string, eventName: string, callback: (data: any) => void) {
    if (!this.pusher) {
      this.initPusher(); // Khởi tạo Pusher nếu chưa có kết nối
    }
    
    if (!this.channels[channelName]) {
      this.channels[channelName] = this.pusher!.subscribe(channelName);
    }

    this.channels[channelName].unbind(eventName); // Gỡ bỏ sự kiện cũ
    this.channels[channelName].bind(eventName, callback); // Đăng ký sự kiện mới
  }

  unsubscribeFromChannel(channelName: string) {
    if (this.channels[channelName]) {
      this.pusher!.unsubscribe(channelName);
      delete this.channels[channelName];
    }
  }

  // Hủy bỏ lắng nghe khi không còn sử dụng
  public cleanup() {
    window.removeEventListener('storage', this.onStorageChange);
  }
}

const pusherService = new PusherService();



// Sử dụng hook để đăng ký và hủy đăng ký kênh
export const usePusher = (subscriptions: { channelName: string; eventName: string; callback: (data: any) => void }[]) => {
  const activeSubscriptions = useRef<{ [key: string]: Set<string> }>({});

  useEffect(() => {
    const currentSubscriptions = activeSubscriptions.current;

    subscriptions.forEach(({ channelName, eventName, callback }) => {
      if (!currentSubscriptions[channelName]) {
        currentSubscriptions[channelName] = new Set();
      }

      const key = eventName;
      if (!currentSubscriptions[channelName].has(key)) {
        currentSubscriptions[channelName].add(key);
        pusherService.subscribeToChannel(channelName, eventName, callback);
      }
    });

    return () => {
      subscriptions.forEach(({ channelName, eventName }) => {
        if (currentSubscriptions[channelName]?.has(eventName)) {
          currentSubscriptions[channelName].delete(eventName);
          pusherService.unsubscribeFromChannel(channelName);
        }

        if (currentSubscriptions[channelName]?.size === 0) {
          delete currentSubscriptions[channelName];
        }
      });
    };
  }, [subscriptions]);
};

export const getDefaultSubscriptions = (callback: (data: any) => void) => [

  //Warehouse Events
  { channelName: "private-global", eventName: "warehouse.created", callback },
  { channelName: "private-global", eventName: "warehouse.updated", callback },
  { channelName: "private-global", eventName: "warehouse.deleted", callback },

  //Shelf Events
  { channelName: "private-global", eventName: "shelf.created", callback },
  { channelName: "private-global", eventName: "shelf.updated", callback },
  { channelName: "private-global", eventName: "shelf.deleted", callback },
  
  // Propose Events
  { channelName: "private-global", eventName: "propose.created", callback },
  { channelName: "private-global", eventName: "propose.deleted", callback },
  { channelName: "private-global", eventName: "propose.updated", callback },
  { channelName: "private-global", eventName: "propose.sent", callback },
  { channelName: "private-global", eventName: "propose.accepted", callback },
  { channelName: "private-global", eventName: "propose.rejected", callback },

  // Product Events
  { channelName: "private-global", eventName: "product.created", callback },
  { channelName: "private-global", eventName: "product.updated", callback },
  { channelName: "private-global", eventName: "product.deleted", callback },

  // Material Events
  { channelName: "private-global", eventName: "material.created", callback },
  { channelName: "private-global", eventName: "material.updated", callback },
  { channelName: "private-global", eventName: "material.deleted", callback },

  // Receipt and Export
  { channelName: "private-global", eventName: "product-receipt.created", callback },
  { channelName: "private-global", eventName: "material-receipt.created", callback },
  { channelName: "private-global", eventName: "material-export.created", callback },
  { channelName: "private-global", eventName: "product-export.created", callback },

  // Inventory Report
  { channelName: "private-global", eventName: "inventory-report.created", callback },
  { channelName: "private-global", eventName: "inventory-report.updated", callback },
  { channelName: "private-global", eventName: "inventory-report.deleted", callback },
  { channelName: "private-global", eventName: "inventory-report.sent", callback },
  { channelName: "private-global", eventName: "inventory-report.confirmed", callback },
  { channelName: "private-global", eventName: "inventory-report.rejected", callback },
  { channelName: "private-global", eventName: "inventory-report.passed", callback },
  { channelName: "private-global", eventName: "inventory-report.cancelled", callback },

  //Manufacturing Plan

  { channelName: "private-global", eventName: "manufacturing-plan.created", callback },
  { channelName: "private-global", eventName: "manufacturing-plan.deleted", callback },
  { channelName: "private-global", eventName: "manufacturing-plan.sent", callback },
  { channelName: "private-global", eventName: "manufacturing-plan.confirmed", callback },
  { channelName: "private-global", eventName: "manufacturing-plan.rejected", callback },
  { channelName: "private-global", eventName: "manufacturing-plan.begin", callback },
  { channelName: "private-global", eventName: "manufacturing-plan.finish", callback },

  //Orders

  { channelName: "private-global", eventName: "order.confirmed", callback },
  { channelName: "private-global", eventName: "order.cancelled", callback },
  { channelName: "private-global", eventName: "order.in-process", callback },


];


export default pusherService;
