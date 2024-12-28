import { fetchAPI } from '../utilities/fetchAPI';

export const getAllOrder = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_ORDERS);
  return response;
};

export const getOrderDetail = async (id: number) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_ORDER_DETAIL}${id}`
  );
  return response;
};

export const confirmOrder = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_CONFIRM_ORDER}${id}`
    
  );
  return response;
};

export const startProcessOrder = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_START_PROCESS_ORDER}${id}`
  );
  return response;
};

export const completeOrder = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_COMPLETE_ORDER}${id}`
  );
  return response;
};


export const cancelOrder = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_CANCEL_ORDER}${id}`
  );
  return response;
};

