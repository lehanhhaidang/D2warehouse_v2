import { fetchAPI } from "../utilities/fetchAPI";

export const getDashboardData = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_DASHBOARD);
  return response.data.data;
};

export const getNotes = async () => {
  const response = await fetchAPI({}, import.meta.env.VITE_NOTES);
  return response.data.data;
}