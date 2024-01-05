import axiosClient from "../services/axiosSevice";
import {DOMAIN} from "../constants/index";

export const register_account = (params) => {
  return axiosClient.post(`${DOMAIN}/api/Register`, params);
}

export const login = (params) => {
  return axiosClient.post (`${DOMAIN}/api/Login`, params);
}

export const checkLoginGoogle = (params) => {
  return axiosClient.post (`${DOMAIN}/api/checkLoginGoogle`, params);

}
export const reActiveUser = (params) => {
  return axiosClient.post(`${DOMAIN}/api/reActiveUser`, params);
}

export const sendMailResetPassword = (params) => {
  return axiosClient.post(`${DOMAIN}/api/user/forgot-password/`, params);
}

export const resetPassword = (params) => {
  return axiosClient.post(`${DOMAIN}/api/user/reset-password-client`, params);
}

export const updatePassword = (params) => {
  return axiosClient.post(`${DOMAIN}/api/updatePassword`, params);
}



export const updateUser = (params) => {
  return axiosClient.post(`${DOMAIN}/api/updateUser/${params.user_id}`, params);
}