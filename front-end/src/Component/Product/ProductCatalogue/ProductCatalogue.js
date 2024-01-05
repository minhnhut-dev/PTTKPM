import React, { useEffect, useState } from "react";
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import "../../Body/Body.css";
import NumberFormat from "react-number-format";
import {linkImage} from "../../../constants/index";
import { getProductbyProductCatalogueId } from "../../../apis/products";
export default function ProductCatalogue(props) {
  const { title, id } = props;
  const [product, setProduct] = useState([]);
  useEffect(() => {
    getProductbyProductCatalogueId({id: id, page: 1}).then((response) => {
      console.log(response.data.data);
      setProduct(response.data.data)
    })
  }, []);
  return (
    <>
    {product.length > 0 &&
       <div id="featured-product">
        <div style={{ position: "relative" }}>
          <h2 className="new-product-title">{title}</h2>
          <Link className="gearvn-new-products-hot-view-all" to={`collections/${id}`}>
            Xem tất cả
            <i className="fa fa-chevron-right"></i>
          </Link>
        </div>
        <div className="loop-pro">
          <div className="module-products row">
            {product.map((item, index) => (
              <div
                className="col-sm-4 col-xs-12 padding-none col-fix20"
                key={index}
              >
                <div className="product-row">
                  <div className="product-row-img">
                    <Link to={`/productDetail/${item.id}`}>
                      <img
                        src={linkImage + item.AnhDaiDien}
                        className="product-row-thumbnail"
                      />
                    </Link>
                    <div className="product-row-price-hover">
                      <div className="product-row-note pull-left">
                        Xem chi tiết
                      </div>

                      <Link
                        to={`/productDetail/${item.id}`}
                        className="product-row-btnbuy pull-right"
                      >
                        Đặt hàng
                      </Link>
                    </div>
                  </div>

                  <h2 className="product-row-name">{item.TenSanPham}</h2>
                  <div className="product-row-info">
                    <div className="product-row-price pull-left">
                      <NumberFormat
                        value={item.GiaCu}
                        displayType={"text"}
                        thousandSeparator={true}
                        suffix={" VNĐ"}
                        renderText={(value, props) => (
                          <del {...props}>{value} </del>
                        )}
                      />
                      <br />
                      <span className="product-row-sale">
                        <NumberFormat
                          value={item.GiaKM}
                          displayType={"text"}
                          thousandSeparator={true}
                          suffix={" VNĐ"}
                          renderText={(value, props) => (
                            <div {...props}>{value}</div>
                          )}
                        />
                      </span>
                    </div>
                    <div className="new-product-percent">
                      {item.SoLuong == 0 ? "Hết hàng" : "Còn hàng"}
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    }
     
    </>
  );
}
