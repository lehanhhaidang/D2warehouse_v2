import { fetchAPI } from '../utilities/fetchAPI';

export const getMaterials = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_MATERIALS);
  return response;
};

export const createMaterial = async (
  name: string,
  image: any,
  unit: string,
  quantity: number,
  category_id: number,
) => {
  const response = await fetchAPI(
    {
      method: 'POST',
      data: {
        name,
        material_img: image,
        unit,
        quantity,
        category_id,
        status: 1,
      },
    },
    import.meta.env.VITE_CREATE_MATERIAL
  );
  return response;
};

export const updateMaterial = async (
  id: number,
  name: string,
  image: any,
  unit: string,
  quantity: number,
  category_id: number,
  status: number
) => {
  const response = await fetchAPI(
    {
      method: 'PATCH',
      data: {
        name,
        material_img: image,
        unit,
        quantity,
        category_id,
        status,
      },
    },
    `${import.meta.env.VITE_UPDATE_MATERIAL}${id}`
  );
  return response;
};


export const deleteMaterial = async (id: number) => {
  const response = await fetchAPI(
    { method: 'DELETE' },
    `${import.meta.env.VITE_DELETE_MATERIALS}${id}`
  );
  return response;
};

export const createMaterialReceipt = async (data: any) => {
  const response = await fetchAPI(
    { method: 'POST', data },
    import.meta.env.VITE_CREATE_MATERIAL_RECEIPT
  );
  return response;
};

export const getMaterialReceipts = async () => {
  const response = await fetchAPI(
    {},
    import.meta.env.VITE_GET_MATERIAL_RECEIPT
  );
  return response;
};

export const getDetailMaterialReceipt = async (id: number) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_MATERIAL_RECEIPT_DETAIL}${id}`
  );
  return response;
};

export const createMaterialExport = async (data: any) => {
  const response = await fetchAPI(
    { method: 'POST', data },
    import.meta.env.VITE_MATERIAL_EXPORT_DETAIL
  );
  return response;
}

export const calculateMaterials = async (products: { product_id: number; product_quantity: number }[]) => {
  const response = await fetchAPI(
    {
      method: 'POST',
      data: {
        products,
      },
    },
    import.meta.env.VITE_CALCULATE
  );
  return response;
};