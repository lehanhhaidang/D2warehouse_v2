import { fetchAPI } from '../utilities/fetchAPI';

export const getShevles = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_SHELVES);
  return response;
};

export const deletShelve = async (id: number) => {
  const response = await fetchAPI(
    { method: 'DELETE' },
    `${import.meta.env.VITE_DELETE_SHELVES}${id}`
  );
  return response;
};
export const createShelve = async (
  name: string,
  warehouse_id: number,
  number_of_levels: number,
  storage_capacity: number,
  category_id: number
) => {
  const response = await fetchAPI(
    {
      method: 'POST',
      data: {
        name,
        warehouse_id,
        number_of_levels,
        storage_capacity,
        category_id,
      },
    },
    import.meta.env.VITE_CREATE_SHELVES
  );
  return response;
};

export const updateShelve = async (
  id: number,
  name: string,
  number_of_levels: number,
  storage_capacity: number,
  warehouse_id: number,
  category_id: number
) => {
  const response = await fetchAPI(
    {
      method: 'PATCH',
      data: {
        name,
        number_of_levels,
        storage_capacity,
        warehouse_id,
        category_id,
      },
    },
    `${import.meta.env.VITE_UPDATE_SHELF}${id}`
  );
  return response;
};

export const filterShelves = async (
  warehouse_id: number,
  product_id: number
) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_FILTER_SHELF}warehouse_id=${warehouse_id}&${warehouse_id === 1 ? `material_id` : `product_id`}=${product_id}`
  );
  return response;
};
export const filterShelvesExport = async (
  warehouse_id: number,
  product_id: number
) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_FILTER_SHELF_EXPORT}warehouse_id=${warehouse_id}&${warehouse_id === 1 ? `material_id` : `product_id`}=${product_id}`
  );
  return response;
};

export const shelfDetail = async (shelf_id: number) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_SHELF_DETAIL}${shelf_id}`
  );
  return response;
};

export const shelfDetailFilter = async (warehouse_id: number) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_SHELVES_DETAILS_BY_WAREHOUSE}${warehouse_id}`
  );
  return response;
};
