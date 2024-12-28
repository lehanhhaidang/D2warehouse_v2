import axios, { AxiosRequestConfig } from 'axios';

export const fetchAPI = async (
  { method = 'GET', data = {}, headers }: AxiosRequestConfig,
  path?: string,
) => {
  const baseURL = import.meta.env.VITE_BASE_URL;
  const url = baseURL + path;
  try {
    const token = await localStorage.getItem('token');
    const response = await axios({
      url,
      method,
      headers: {
        Authorization: `Bearer ${token}`,
        ...headers,
      },
      data,
    });
    return { data: response.data, status: response.data.status };
  } catch (error: any) {
    if (error.response) {
      if (error.response.status === 401) {
        await localStorage.removeItem('token');
        window.location.href = '/login';
      }
      return { error: error.response.data, status: error.response.status };
    } else {
      return { error: error.message };
    }
  }
};
