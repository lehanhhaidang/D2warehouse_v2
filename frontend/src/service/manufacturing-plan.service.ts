import { fetchAPI } from '../utilities/fetchAPI';

export const getAllManufacturingPlan = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_MANUFACTURING_PLAN);
  return response;
};

export const createManufacturingPlan = async (data: any) => {
  const response = await fetchAPI(
    { method: 'POST', data },
    import.meta.env.VITE_CREATE_MANUFACTURING_PLAN
  );
  return response;
};

export const updateManufacturingPlan = async (id: number, data: any) => {
  const response = await fetchAPI(
    { method: 'PATCH', data },
    `${import.meta.env.VITE_UPDATE_MANUFACTURING_PLAN}${id}`
  );
  return response;
};

export const deleteManufacturingPlan = async (id: number) => {
  const response = await fetchAPI(
    { method: 'DELETE' },
    `${import.meta.env.VITE_DELETE_MANUFACTURING_PLAN}${id}`
  );
  return response;
};

export const getManufacturingDetail = async (id: number) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_MANUFACTURING_PLAN_DETAIL}${id}`
  );
  return response;
};

export const sendManufacturingPlan = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_SEND_MANUFACTURING_PLAN}${id}`
    
  );
  return response;
};

export const acceptManufacturingPlan = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_ACCEPT_MANUFACTURING_PLAN}${id}`
  );
  return response;
};

export const rejectManufacturingPlan = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_REJECT_MANUFACTURING_PLAN}${id}`
  );
  return response;
};

export const beginManufacturing = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_BEGIN_MANUFACTURING}${id}`
  );
  return response;
};

export const finishManufacturing = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_FINISH_MANUFACTURING}${id}`
  );
  return response;
};
