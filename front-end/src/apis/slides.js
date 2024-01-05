import axiosClient from "../services/axiosSevice";
import {DOMAIN} from "../constants/index";

export const getSlide = () => {
  return axiosClient.get(`${DOMAIN}/api/get-image-slides`);
}