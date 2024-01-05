import React, { useEffect, useState,useMemo } from "react";
import Footer from "../../Component/Footer/Footer";
import Header from "../../Component/Header/Header";
import "./PaymentResult.css";
import { BrowserRouter as Router, Link, useLocation } from "react-router-dom";
import axios from "axios";
import NumberFormat from "react-number-format";
import {updateOrder, updateOrderCanceled, getInformationOrderById} from "../../apis/order";
const orderInfo = JSON.parse(localStorage.getItem("Order") || "[]");

function PaymentResult(){
  const [order, setOder] = useState([]);
  const [statusOrder,setStatusOrder]=useState("");

  function useQuery() {
    return new URLSearchParams(useLocation().search);
  }

  //localMessage
  //Thành công
  //Đơn hàng đã bị huỷ bỏ
  const data = {
    status: 2,
    order_id: orderInfo.id
  };
 
  var query = useQuery();
  var name = query.get("localMessage");
  var message = query.get("message");
  var response_code=query.get("vnp_ResponseCode");
  var orderId = query.get('orderId');

  // useMemo(() => {
  //   if (name == "Thành công") {
  //     console.log('vao day')
  //     updateOrder(data)
  //       .then((res) => {
          
  //         console.log(res);
  //         setStatusOrder("Đơn hàng thành công");
  //       });

  //   } else if (message == 1) {
  //     updateOrder(data)
  //       .then((res) => {
  //         console.log(res);
  //       });
  //     name = "Đơn hàng thành công";
  //     setStatusOrder("Đơn hàng thành công");
  //   }
  //   else if(response_code =="00")
  //   {
  //     updateOrder(data)
  //     .then((res) => {
  //       console.log(res);
  //     });
  //   setStatusOrder("Đơn hàng thành công");
  //   }
  //    else if (message == 2) {
  //     console.log('vao day')

  //     updateOrderCanceled(orderInfo.id)
  //     .then((res) => {
  //       console.log(res);
  //     });
  //     name = "Đơn hàng bị hủy";
  //     setStatusOrder("Đơn hàng bị hủy");
  //   } 
  //   else if(message == 3){
  //     const data_1 ={
  //       status: 1,
  //       order_id: orderInfo.id
  //     }
  //     updateOrder(data_1)
  //     .then((res) => {
  //       console.log(res);
  //     });
  //     name = "Đơn hàng thành công";
  //     setStatusOrder("Đơn hàng thành công");
  //   }
  //   else {
  //     console.log('vao day')
  //     updateOrderCanceled(orderInfo.id)
  //     .then((res) => {
  //       console.log(res);
  //     });
  //     name = "Đơn hàng bị hủy";
  //     setStatusOrder("Đơn hàng bị hủy");
  //   }
  // }, []);
  useEffect(() => {
    getInformationOrderById(orderId)
      .then((res) => {
        setOder(res);
      });
  }, []);

  return (
    <>
      <Header />
      <div className="noindex">
        {order.map((item, index) => (
          <div className="container" key={index}>
            <div className="order-Detail">
              <div className="Heading">
                <span>Chi tiết đơn hàng #HD{item.id} </span>
                <span className="split">-</span>
                <span className="status">
                  <i className="fal fa-check-circle"></i>
                  {statusOrder}
                </span>
              </div>
              <NumberFormat
                      value={item.Tongtien}
                      displayType={"text"}
                      thousandSeparator={true}
                      suffix={" VNĐ"}
                      renderText={(value, props) => (
                        <div className="totalPrice" {...props}>
                         Tổng tiền: {value}
                        </div>
                      )}
                    />
              <div className="created-date">
                Ngày đặt hàng: {item.ThoiGianMua}
              </div>

              <div className="information-User">
                <div className="address-User">
                  <div className="title">Địa chỉ người nhận</div>
                  <div className="content">
                    <p className="name">{item.ten_nguoi_dung}</p>
                    <p className="address">
                      <span>Địa chỉ:</span>
                      {item.diachigiaohang}
                    </p>
                    <p className="phone">
                      <span>Số điện thoại: </span>
                      {item.sdt}
                    </p>
                  </div>
                </div>
                <div className="address-User">
                  <div className="title">Hình thức vận chuyển</div>
                  <div className="content">
                    <p className="name">{item.TenHinhThuc}</p>

                    <p className="phone">
                      <span>Phí vận chuyển: 0đ</span>
                    </p>
                  </div>
                </div>
                <div className="address-User">
                  <div className="title">Hình thức thanh toán</div>
                  <div className="content">
                    <p className="name">{item.TenThanhToan}</p>
                  </div>
                </div>
              </div>
              <Link
                to={`/account/order/${orderId}`}
                className="view-tracking-detail"
              >
                Xem đơn hàng
              </Link>
            </div>
          </div>
        ))}
      </div>
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <Footer />
    </>
  );
}

export default PaymentResult;
