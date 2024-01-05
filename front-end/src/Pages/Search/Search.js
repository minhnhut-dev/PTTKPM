import React, { useEffect, useState } from "react";
import Header from "../../Component/Header/Header";
import Footer from "../../Component/Footer/Footer";
import { Link, useLocation } from "react-router-dom";
import NumberFormat from "react-number-format";
import {linkImage} from "../../constants/index";
import {searchProductByKeyWord} from "../../apis/products";
export default function Search() {
  const [result,setResult]=useState([]);
  function useQuery() {
    return new URLSearchParams(useLocation().search);
  }
  var query = useQuery();
  var name = query.get("kq");
  useEffect(() => {
    searchProductByKeyWord(name)
      .then((response) =>{
        setResult(response);
      })
  },[])

  return (
    <>
      <Header />
      <div className="noindex">
        <section className="light_section">
          <div id="collection" className="container">
            <div className="col-sm-12">
              <h1 className="title-box-collection" style={{ fontSize: "36px" }}>
                Tìm kiếm
              </h1>
              <div className="row">
                <div className="main-content">
                  <div id="breadcrumb">
                    <div className="main">
                      <div className="breadcrumbs container">
                        <span className="showHere">Kết quả tìm kiếm: {name}</span>
                      </div>
                    </div>
                  </div>

                  <div className="col-md-12 product-list">
                    <div className="row content-product-list">

                      {result.map((v,index)=>(
                        <div className="col-sm-4 col-xs-12 padding-none col-fix20" key={index}>
                        <div className="product-row">
                          <div className="product-row-img">
                            <Link to={`/ProductDetail/${v.id}`}>
                              <img
                                src={linkImage + v.AnhDaiDien}
                                alt={v.AnhDaiDien}
                                className="product-row-thumbnail"
                              />
                            </Link>
                            <div className="product-row-price-hover">
                              <Link to={`/ProductDetail/${v.id}`}>
                                <div className="product-row-note pull-left">
                                  Xem chi tiết
                                </div>
                              </Link>
                              <Link
                                to={`/ProductDetail/${v.id}`}
                                className="product-row-btnbuy pull-right"
                              > 
                                đặt hàng
                              </Link>
                            </div>
                          </div>

                          <h2 className="product-row-name">
                            {v.TenSanPham}
                          </h2>
                          <div className="product-row-info">
                            <div className="product-row-price pull-left">
                              {/* <del>17,290,000₫</del> */}
                              <NumberFormat
                                value={v.GiaCu}
                                displayType={"text"}
                                thousandSeparator={true}
                                suffix={" VNĐ"}
                                renderText={(value, props) => (
                                  <del {...props}>{value}</del>
                                )}
                              />
                              <br />
                             
                              <NumberFormat
                                value={v.GiaKM}
                                displayType={"text"}
                                thousandSeparator={true}
                                suffix={" VNĐ"}
                                renderText={(value, props) => (
                                  <span className="product-row-sale" {...props}>{value}</span>
                                )}
                              />
                            </div>
                            <div className="new-product-percent">-10%</div>
                          </div>
                        </div>
                      </div>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <Footer />
    </>
  );
}