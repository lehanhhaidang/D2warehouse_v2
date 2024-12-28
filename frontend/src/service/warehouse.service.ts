import { fetchAPI } from '../utilities/fetchAPI';

export const getAllWareHouse = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_WAREHOUSE);
  return response;
};
export const deleteWareHouse = async (id: number) => {
  const response = await fetchAPI(
    { method: 'DELETE' },
    `${import.meta.env.VITE_DELETE_WAREHOUSE}${id}`
  );
  return response;
};
export const createWareHouse = async (data: any) => {
  const response = await fetchAPI(
    { method: 'POST', data },
    import.meta.env.VITE_CREATE_WAREHOUSE
  );
  return response;
};
export const loadEmpoyee = async (id: number) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_GET_USER_BY_WAREHOUSE}${id}`
  );
  return response;
};
export const updateWarehouse = async (
  id: number,
  name: string,
  location: string,
  acreage: number,
  number_of_shelves: number,
  category_id: number
) => {
  const response = await fetchAPI(
    { method: 'PATCH', data: { name,location, acreage, number_of_shelves, category_id } },
    `${import.meta.env.VITE_UPDATE_WAREHOUSE}${id}`
  );
  return response;
};