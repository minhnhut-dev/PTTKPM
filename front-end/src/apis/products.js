import axiosClient from "../services/axiosSevice";
import {DOMAIN} from "../constants/index";

export const getAllProduct = () => {
  return axiosClient.get(`${DOMAIN}/api/getAllProduct`);
};

export const getAccessoriesByTypeProductId = (id) => {
  return axiosClient.get(`${DOMAIN}/api/getAccessoriesByTypeProductId/${id}`);
};

export const GetProductByID = (id) => {
  return axiosClient.get(`${DOMAIN}/api/GetProductByID/${id}`);
}

export const  GetImageProductByID = (id) => {
  return axiosClient.get(`${DOMAIN}/api/GetImageProductByID/${id}`);
}

export const  suggestProducts = (id) => {
  return axiosClient.get(`${DOMAIN}/api/suggestProduct/${id}`);
}

export const  getComments = (id) => {
  return axiosClient.get(`${DOMAIN}/api/getComments/${id}`);
}

export const  AddComment = (params) => {
  return axiosClient.post(`${DOMAIN}/api/AddComment`, params);
}

export const  searchProductByKeyWord = (keyword) => {
  return axiosClient.get(`${DOMAIN}/api/searchByKeyWord/${keyword}`);
}

export const getTopProductHot = () => {
  return axiosClient.get(`${DOMAIN}/api/top-products-hot`);
}

export const getProductCatalogues = () => {
  return axiosClient.get(`${DOMAIN}/api/productCatalogues`);
}

export const getProductbyProductCatalogueId = (params) => {
  return axiosClient.get(`${DOMAIN}/api/getProductbyProductCatalogueId/${params.id}?page=${params.page}`);
}
export const getProductbyCategoryId = (params) => {
  return axiosClient.get(`${DOMAIN}/api/getProductByTypeProductId/${params.id}?page=${params.page}`);
}