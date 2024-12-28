import axios from "axios";
import { fetchAPI } from "../utilities/fetchAPI";
// login
export const signIn = async (email: string, password: string) => {
    const endpoint = import.meta.env.VITE_BASE_URL + import.meta.env.VITE_LOGIN_URL;
    const reponse = await axios.post(endpoint, {email, password});
    return reponse;
}

export const forgotPassword = async (email: string) => { 
    const endpoint = import.meta.env.VITE_BASE_URL + import.meta.env.VITE_FORGOT_PASSWORD;
    const reponse = await axios.post(endpoint, {email});
    return reponse;
}

export const resetPassword = async (token: string, email: string, newPassword: string, confirmPassword: string) => { 
    const endpoint = import.meta.env.VITE_BASE_URL + import.meta.env.VITE_RESET_PASSWORD;
    const response = await axios.post(endpoint, {
        token,
        email,
        password: newPassword, 
        password_confirmation: confirmPassword  
    });
    return response;
}

export const changePassword = async (oldPassword: string, newPassword: string, confirmPassword: string) => {
  const endpoint = import.meta.env.VITE_PASSWORD_CHANGE;
  const config = {
    method: 'PATCH',
    data: {
      current_password: oldPassword,
      new_password: newPassword,
      new_password_confirmation: confirmPassword,
    },
  };
  const response = await fetchAPI(config, endpoint);
  return response;
};
