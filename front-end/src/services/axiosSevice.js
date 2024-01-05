import axios from "axios";
import queryString from "query-string";

const axiosClient = axios.create({
  baseURL: process.env.API_URL,
  headers: {
    "content-type": "application/json",
  },
  paramsSerializer: (params) => queryString.stringify(params),
});
axiosClient.interceptors.request.use(async (config) => {
  // Handle token here ...
  return config;
});

 const handleError = error => {
    if(error.response === undefined){
      let error = {data: "Không thể kết nối với máy chủ ", status: 502}
      return error;
    }else{
      const status = error.response.status;
      switch (status) {
        case 401:
          let error = {data: "Vui lòng đăng nhập", status: status}
          return error;
        default:
          let r_error = {data: "Yêu cầu đường dẫn không đúng", status: status}
          return r_error;
      }
    }
  };

axiosClient.interceptors.response.use(
  (response) => {
    if (response && response.data) {
      return response.data;
    }
    return response;
  },
  (error) => {
    // Handle errors
	handleError(error);
    throw error;
  }
);
export default axiosClient;
