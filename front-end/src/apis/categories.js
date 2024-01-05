import axiosClient from "../services/axiosSevice";
import {DOMAIN} from "../constants/index";

export const getAllTypeProduct = () => {
  return axiosClient.get(`${DOMAIN}/api/getAllTypeProduct`);
}

export  const getTypeProductById = (id) => {
  return axiosClient.get(`${DOMAIN}/api/getTypeProductById/${id}`);
}

export  const getAccessoriesByTypeProductId = (params) => {
  return axiosClient.get(`${DOMAIN}/api/getAccessoriesByTypeProductId/${params.id}?page=${params.page}`);
}

