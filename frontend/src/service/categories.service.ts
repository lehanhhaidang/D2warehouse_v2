import { fetchAPI } from '../utilities/fetchAPI';

export const getAllCategory = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_ALL_CATEGORY);
  return response;
};

export const getCategoriesParent = async () => {
  const response = await fetchAPI(
    {},
    import.meta.env.VITE_GET_CATEGORIES_PARENT
  );
  return response;
};

export const createCategory = async ( name: string, type: string, parent_id: number) => {
  const response = await fetchAPI(
    { method: 'POST', data: {  name, type, parent_id } },
    import.meta.env.VITE_CREATE_CATEGORY
  );
  return response;
}
export const updateCategory = async (id: number, data: any) => {
  const response = await fetchAPI(
    { method: 'PATCH', data },
    `${import.meta.env.VITE_UPDATE_CATEGORY}${id}`
  );
  return response;
};

export const deleteCategory = async (id: number) => {
  const response = await fetchAPI(
    { method: 'DELETE' },
    `${import.meta.env.VITE_DELETE_CATEGORY}${id}`
  );
  return response;
};
