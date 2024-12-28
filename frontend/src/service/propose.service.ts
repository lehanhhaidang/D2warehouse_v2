import { fetchAPI } from '../utilities/fetchAPI';

export const createPropose = async (data: any) => {
  const response = await fetchAPI(
    { method: 'POST', data },
    import.meta.env.VITE_CREATE_PROPOSE
  );
  return response;
};

export const getPropose = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_GET_PROPOSE);
  return response;
};

export const updatePropose = async (data: any, id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH', data },
    `${import.meta.env.VITE_UPDATE_PROPOSE}${id}`
  );
  return response;
};

export const deletePropose = async (id: number) => { 
  const response = await fetchAPI(
    { method: 'DELETE' },
    `${import.meta.env.VITE_DELETE_PROPOSE}${id}`
  );
  return response;
};

export const updateStatusPropose = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_UPDATE_SEND_PROPOSE}${id}`
  );
  return response;
};

export const loadProposeDetail = async (id: number) => {
  const response = await fetchAPI(
    {},
    `${import.meta.env.VITE_GET_PROPOSE_DETAIL}${id}`
  );
  return response;
};

export const sendPropose = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_SEND_PROPOSE}${id}`
  );
  return response;
}

export const acceptPropose = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_ACCEPT_PROPOSE}${id}`
  );
  return response;
}

export const rejectPropose = async (id: number) => {
  const response = await fetchAPI(
    { method: 'PATCH' },
    `${import.meta.env.VITE_REJECT_PROPOSE}${id}`
  );
  return response;
}

export const updateProposeDetail = async (id: number, data: any) => {
  const response = await fetchAPI({ method: 'PATCH', data }, `${import.meta.env.VITE_UPDATE_PROPOSE_DETAIL}${id}`);
  return response;
}