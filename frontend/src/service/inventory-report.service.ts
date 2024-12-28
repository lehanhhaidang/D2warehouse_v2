import { fetchAPI } from '../utilities/fetchAPI';

export const getAllInventoryReport = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_INVENTORY);
  return response;
};

export const createInventoryReport = async (data: any) => {
  const response = await fetchAPI(
    { method: 'POST', data },
    import.meta.env.VITE_CREATE_INVENTORY
  );
  return response;
};

export const updateInventoryReport = async (id: number, data: any) => {
  const response = await fetchAPI(
    { method: 'PATCH', data },
    `${import.meta.env.VITE_UPDATE_INVENTORY}${id}`
  );
  return response;
};

export const deleteInventoryReport = async (id: number) => {
  const response = await fetchAPI(
    { method: 'DELETE' },
    `${import.meta.env.VITE_DELETE_INVENTORY}${id}`
  );
  return response;
};

export const getInventoryDetail = async (id: number) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_GET_INVENTORY_DETAIL}${id}`
  );
  return response;
};

export const sendInventory = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_UPDATE_STATUS_INVENTORY}${id}`
  );
  return response;
};

export const acceptInventory = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_ACCEPT_INVENTORY}${id}`
  );
  return response;
};

export const rejectInventory = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_REJECT_INVENTORY}${id}`
  );
  return response;
};

export const confirmUpdate = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_CONFIRM_UPDATE}${id}`
  );
  return response;
};

export const cancelInventoryReport = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_CANCEL_INVENTORY}${id}`
  );
  return response;
};
