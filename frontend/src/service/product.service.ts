import { fetchAPI } from '../utilities/fetchAPI';

export const getProducts = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_PRODUCTS);
  return response;
};
export const deleteProduct = async (id: number) => {
  const response = await fetchAPI(
    { method: 'DELETE' },
    `${import.meta.env.VITE_DELETE_PRODUCT}/${id}`
  );
  return response;
};
export const createProduct = async (
  name: string,
  image: any,
  unit: string,
  quantity: number,
  category_id: number,
  color_id: number
) => {
  const response = await fetchAPI(
    {
      method: 'POST',
      data: {
        name,
        product_img: image,
        unit,
        quantity,
        category_id,
        color_id,
        status: 1,
      },
    },
    import.meta.env.VITE_CREATE_PRODUCT
  );
  return response;
};

export const updateProduct = async (
  id: number,
  name: string,
  image: any,
  unit: string,
  quantity: number,
  category_id: number,
  color_id: number,
  status: number
) => {
  const response = await fetchAPI(
    {
      method: 'PATCH',
      data: {
        name,
        product_img: image,
        unit,
        quantity,
        category_id,
        color_id,
        status,
      },
    },
    `${import.meta.env.VITE_UPDATE_PRODUCT}/${id}`
  );
  return response;
};

export const createProductReceipt = async (data: any) => {
  const response = await fetchAPI(
    { method: 'POST', data },
    import.meta.env.VITE_CREATE_PRODUCT_RECEIPT
  );
  return response;
};

export const createProductExport = async (data: any) => {
  const response = await fetchAPI(
    { method: 'POST', data },
    import.meta.env.VITE_CREATE_PRODUCT_EXPORT
  );
  return response;
};

