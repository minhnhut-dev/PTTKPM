import axiosClient from "../services/axiosSevice";
import {DOMAIN} from "../constants/index";

export const getOrderUnpaidByUserID = (id) => {
  return axiosClient.get(`${DOMAIN}/api/getOrderUnpaidByUserID/${id}`);
}

export const getOrderCompleteByUserId = (id) => {
  return axiosClient.get(`${DOMAIN}/api/getOrderCompleteByUserId/${id}`);
}

export const getOrderDetails = (id) => {
  return axiosClient.get(`${DOMAIN}/api/getOrderDetails/${id}`);
}

export const getOrderCanceled = (id) => {
  return axiosClient.get(`${DOMAIN}/api/getOrderCanceled/${id}`);
}

export const getOrderPaidByUserID = (id) => {
  return axiosClient.get(`${DOMAIN}/api/getOrderPaidByUserID/${id}`);
}

export const createOrder = (params ) => {
  return axiosClient.post(`${DOMAIN}/api/order`, params);
}

export const createOrderVNpay = (params ) => {
  return axiosClient.post(`${DOMAIN}/api/paymentVNPAY`, params);
}

export const updateOrder = (params) => {
  return axiosClient.post(`${DOMAIN}/api/updateOrder/${params.order_id}`, params);
}
export const updateOrderCanceled = (order_id) => {
  return axiosClient.post(`${DOMAIN}/api/updateOrderCanceled/${order_id}`);
}

export const getInformationOrderById = (order_id) =>{
  return axiosClient.get(`${DOMAIN}/api/getInformationOrderById/${order_id}`);
}

export const createOrderMomo = (params) => {
  return axiosClient.post(`${DOMAIN}/api/orderMomo`, params);

}