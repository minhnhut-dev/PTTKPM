import React, { useEffect, useState } from "react";
import { Link, Redirect } from "react-router-dom";
import Header from "../../Component/Header/Header";
import Footer from "../../Component/Footer/Footer";
import NumberFormat from "react-number-format";
import { Button } from "react-bootstrap";
import "./Cart.css";
import Paypal from "../../Component/Paypal/Paypal";
import { useSnackbar } from "notistack";
import CircularProgress from "@material-ui/core/CircularProgress";
import Backdrop from "@material-ui/core/Backdrop";
import { useForm } from "react-hook-form";
import { TextField } from "@material-ui/core";
import {createOrder, createOrderVNpay, createOrderMomo} from "../../apis/order";
import { useHistory } from "react-router-dom"

function Cart(props) {
  const userLogin = JSON.parse(localStorage.getItem("userLogin") || "[]");
  let history = useHistory()

  const { cartItems, onRemove, onAdd, onRemoveAll, setCartItems} = props;
  const itemsPrice = cartItems.reduce((a, c) => a + c.qty * c.GiaKM, 0);
  const totalPrice = itemsPrice;
  const LinkImage = "http://127.0.0.1:8000/images/";
  const [optionPayment, setOptionPayment] = useState();
  const [redirect, setRedirect] = useState(false);
  const [payURL, setPayURL] = useState();
  const [paypal, setPayPal] = useState(false);
  const [openBackDrop, setOpenBackDrop] = useState(false);
  const { enqueueSnackbar } = useSnackbar();
  const [name, setName] = useState(userLogin.TenNguoidung);
  const [address, setAddress] = useState(userLogin.DiaChi);
  const [phone, setPhone] = useState(userLogin.SDT);
  const {
    register,
    handleSubmit,
    formState: { errors },
    watch,
  } = useForm();

  const newArr = cartItems.map((item) => {
    return { san_phams_id: item.id, DonGia: item.GiaKM, SoLuong: item.qty };
  });
  const handleCloseBackdrop = () => {
    setOpenBackDrop(false);
  };

  const handleOpenBackDrop = () => {
    setOpenBackDrop(true);
  }

  // Thanh toán MOMO

  const Moneyconversion = (totalPrice) => {
    var dola = totalPrice / 23000;
    return dola.toFixed(2).toString();
  };

  const handleCreateOrder = async(data) => {
    createOrder({ten_nguoi_dung: data.name, 
      email: data.email, sdt: data.phone, 
      diachigiaohang: data.address, 
      hinh_thuc_thanh_toans_id: data.payment,
      hinh_thuc_giao_hangs_id: data.shipping,
      trang_thai_don_hangs_id: 1,
      line_items: newArr
    })
    .then(res => {
      if(res){
        handleOpenBackDrop();
        setCartItems([]);
        history.push(`/resultOrder?orderId=${res.order.id}`)
      }
    }).catch(error => {
     enqueueSnackbar(error.response.data.error, {
      variant: "error",
      autoHideDuration: 3000,
      preventDuplicate: true,
      });})
  }
  
  const onSubmit = async(data) => {
    switch (data.payment) {
      case '1':
         await handleCreateOrder(data);
        break;
      case '2':
        createOrderMomo({ten_nguoi_dung: data.name, 
          email: data.email, sdt: data.phone, 
          diachigiaohang: data.address, 
          hinh_thuc_thanh_toans_id: data.payment,
          hinh_thuc_giao_hangs_id: data.shipping,
          trang_thai_don_hangs_id: 1,
          line_items: newArr
        }).then(response => {
          console.log(response);
          setCartItems([]);
          window.location.assign(response.payURL);
        })
        break;
      default:
        break;
    }
  }

  return (
    <>
      <Header />
      <div className="noindex">
        <div id="mainframe">
          <div className="container">
            {cartItems.length === 0 ? (
              <div className="site-content-inner">
                <div className="woocommerce">
                  <div className="woocommerce-notices-wrapper">
                    <div className="gearvn-cart-empty">
                      <img
                        className="lazy loaded"
                        src="https://beta.gearvn.com/wp-content/themes/gearvn-electro-child-v1/assets/images/empty-cart.png"
                        alt="cart_empty"
                      />
                      <p className="text-center">
                        Chưa có sản phẩm nào trong giỏ hàng của bạn.
                      </p>
                    </div>
                    <p className="return-to-shop">
                      <Link className="button wc-backward" to="/">
                        Quay lại trang chủ
                      </Link>
                    </p>
                  </div>
                </div>
              </div>
            ) : (
              <div id="wrap-cart" className="container">
                <div className="row">
                  <div id="layout-page-card" className="container">
                    <span className="header-page clearfix">
                      <h1 className="title-card"> Giỏ hàng</h1>
                    </span>
                    <form onSubmit={handleSubmit(onSubmit)}>
                      <div id="cartformpage">
                        <table width="100%">
                          <thead>
                            <tr>
                              <th className="image">Sản phẩm</th>
                              <th className="item">Tên sản phẩm</th>
                              <th className="qty">Số lượng</th>
                              <th className="price">Giá tiền</th>
                              <th className="remove">Xóa</th>
                            </tr>
                          </thead>
                          <tbody>
                            {cartItems.map((item) => (
                              <tr>
                                <th className="image">
                                  <div className="product_image">
                                    <Link to="#">
                                      <img
                                        src={LinkImage + item.AnhDaiDien}
                                        style={{ width: "100px" }}
                                        alt={item.AnhDaiDien}
                                      />
                                    </Link>
                                  </div>
                                </th>
                                <th className="item">
                                  <Link to={`/ProductDetail/${item.id}`}>
                                    <strong>{item.TenSanPham}</strong>
                                  </Link>
                                </th>
                                <th
                                  className="qty"
                                  style={{
                                    display: "flex",
                                    marginTop: "30px",
                                    justifyContent: "space-evenly",
                                  }}
                                >
                                  <button
                                    onClick={() => onRemove(item)}
                                    className="remove"
                                  >
                                    -
                                  </button>
                                  <span className="cart-qty">{item.qty}</span>

                                  <button
                                    onClick={() => onAdd(item)}
                                    className="add"
                                  >
                                    +
                                  </button>
                                </th>
                                <th className="price">
                                  <NumberFormat
                                    value={item.GiaKM}
                                    displayType={"text"}
                                    thousandSeparator={true}
                                    suffix={" VNĐ"}
                                    renderText={(value, props) => (
                                      <div {...props}>{value} </div>
                                    )}
                                  />
                                </th>
                                <th className="remove">
                                  <i
                                    className="fas fa-trash-alt"
                                    onClick={() => onRemoveAll(item)}
                                  ></i>
                                </th>
                              </tr>
                            ))}
                            <tr className="summary">
                              <td
                                colSpan="4"
                                style={{ fontWeight: "bold", fontSize: "20px" }}
                              >
                                Tổng tiền
                              </td>
                              <td className="price">
                                <span className="total">
                                  <NumberFormat
                                    value={totalPrice}
                                    displayType={"text"}
                                    thousandSeparator={true}
                                    suffix={" VNĐ"}
                                    renderText={(value, props) => (
                                      <strong {...props}>{value} </strong>
                                    )}
                                  />
                                </span>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div className="cart-container">
                          <h1 style={{ margin: 0 }}>Đặt hàng</h1>
                          <div className="cart_step2">
                            <div className="title_box_cart">
                              1. Thông tin khách hàng
                            </div>
                            <div className="input-group name d-flex">
                              <TextField
                                id="outlined-basic-1"
                                variant="outlined"
                                type="text"
                                placeholder="Nhập họ tên"
                                name="name"
                                value={name}
                                {...register("name", {required: true})}
                                error={Boolean(errors.name)}
                                helperText={errors.name && "Tên khách hàng là bắt buộc"}
                              />

                              <TextField
                                style={{ marginLeft: "10px" }}
                                id="outlined-basic-2"
                                variant="outlined"
                                placeholder="Email"
                                type="text"
                                value={userLogin.Email}
                                name="email"
                                {...register("email", {required: true})}
                                error={Boolean(errors.email)}
                                helperText={errors.email && "Email là bắt buộc"}
                              />
                            </div>
                            <div className="input-group name d-flex">
                              <TextField
                                id="outlined-basic-3"
                                variant="outlined"
                                type="text"
                                placeholder="Nhập số điện thọai"
                                name="phone"
                                value={phone}
                                {...register("phone", {required: true})}
                                error={Boolean(errors.phone)}
                                helperText={errors.phone && "Số điện thoại là bắt buộc"}

                              />
                              <TextField
                                style={{ marginLeft: "10px" }}
                                id="outlined-basic-4"
                                variant="outlined"
                                type="text"
                                placeholder="Nhập địa chỉ"
                                value={address}
                                {...register("address", {required: true})}
                                error={Boolean(errors.address)}
                                helperText={errors.address && "Địa chỉ là bắt buộc"}
                              />
                            </div>
                            <div className="box-cart-user-info">
                              <div className="title_box_cart">
                                2. Hình thức thanh toán
                              </div>
                              <div
                                className="left"
                                style={{ marginRight: "15px" }}
                              >
                                <input
                                  type="radio"
                                  name="payment"
                                  value="1"
                                  {...register("payment", {required: true})}
                                />
                                <label
                                  style={{
                                    display: "inline-block",
                                    lineHeight: "20px",
                                    marginLeft: "15px",
                                  }}
                                >
                                  Tiền mặt
                                </label>
                              </div>

                              <div id="momo">
                                <input
                                  type="radio"
                                  name="payment"
                                  value="2"
                                  {...register("payment", {required: true})}
                                />
                                <label
                                  style={{
                                    display: "inline-block",
                                    lineHeight: "20px",
                                    marginLeft: "15px",
                                  }}
                                >
                                  <i className="cps-zalopay"></i>
                                  <span> Cổng thanh toán MOMO</span>
                                </label>
                              </div>
                              {/* <div id="paypal">
                                <input
                                  type="radio"
                                  name="payment"
                                  value="3"
                                  {...register("payment", {required: true})}
                                />
                                <label
                                  style={{
                                    display: "inline-block",
                                    lineHeight: "20px",
                                    marginLeft: "15px",
                                  }}
                                >
                                  <i className="fab fa-cc-paypal"></i>
                                  <span> Paypal</span>
                                </label>
                              </div> */}
                              {/* <div id="vnpay">
                                <input
                                  type="radio"
                                  name="payment"
                                  value="4"
                                  // onChange={(e) =>
                                  //   setOptionPayment(e.target.value)
                                  // }
                                  {...register("payment", {required: true})}
                                />
                                <label
                                  style={{
                                    display: "inline-block",
                                    lineHeight: "20px",
                                    marginLeft: "15px",
                                  }}
                                >
                                  <i className="cs-vnpay"></i>
                                  <span> Cổng thanh toán VNPAY</span>
                                </label>
                              </div> */}
                            </div>
                            {/* <div className="box-cart-user-info">
                              <div className="title_box_cart">
                                3. Hình thức giao hàng
                              </div>
                              <div
                                className="left"
                                style={{ marginRight: "15px" }}
                              >
                                <input
                                  name="shipping"
                                  type="radio"
                                  value="1"
                                  {...register("shipping", {required: true})}
                                />
                                <label
                                  style={{
                                    display: "inline-block",
                                    lineHeight: "20px",
                                    marginLeft: "15px",
                                  }}
                                >
                                  COD
                                </label>
                              </div>
                            </div> */}
                            {paypal ? (
                              <Paypal
                                Moneyconversion={Moneyconversion(totalPrice)}
                              />
                            ) : (
                              ""
                            )}
                          </div>
                        </div>
                        <div className="col-xl-12 col-md-12 cart-buttons inner-right inner-left">
                          <div className="buttons">
                            <Button
                              id="checkout"
                              name="checkout"
                              type="submit"
                            >
                              Thanh toán
                            </Button>
                          </div>
                          <Backdrop
                            open={openBackDrop}
                            className="backdrop-mui"
                            onClick={handleCloseBackdrop}
                          >
                            <CircularProgress color="inherit" />
                          </Backdrop>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
}

export default Cart;
