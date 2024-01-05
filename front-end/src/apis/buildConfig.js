import axiosClient from "../services/axiosSevice";
import {DOMAIN} from "../constants/index";

export const getProductByTypeProductId = (params) => {
  return axiosClient.get(`${DOMAIN}/api/getProductByTypeProductId/${params.id}?page=${params.page}`);
}

export const getAccessories = (params) => {
  return axiosClient.get(`${DOMAIN}/api/${params}`);
}