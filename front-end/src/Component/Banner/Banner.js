import React, { useState } from "react";
import "./Banner.css";
import { Carousel } from "react-bootstrap";
import { Link } from "react-router-dom";
import { useEffect } from "react";
import {getSlide} from "../../apis/slides";
import {DOMAIN} from "../../constants/index";
import { getAllTypeProduct } from "../../apis/categories";
 function Banner() {
  const [typeProduct, setTypeProduct] = useState([]);
  const [slides,setSlides]= useState([]);

  useEffect(() => {
    getAllTypeProduct().then((response) => {
      setTypeProduct(response);
    })
      getSlide().then((response) => {
        setSlides(response.images);
      })
  }, []);
  
  const linkImage = `${DOMAIN}/slides/`;

  return (
    <>
      <div className="gearvn-header-navigation ">
        <div className="row gearvn-content-section gearvn-header-navigation-content padding-10-0 container">
          <div className="gearvn-header-menu">
            <div className="cat-menu gearvn-cat-menu" style={{height: 'auto'}}>
              <nav id="megamenu-nav" className="megamenu-nav">
                <ol className="megamenu-nav-main">
                 {typeProduct.map((item, index)=>(
                       <li className="cat-menu-item " key={index}>
                       <Link className="gearvn-cat-menu-item" to={`/collections/${item.id}`}>
                         <div className="gearvn-cat-menu-icon" dangerouslySetInnerHTML={{__html: item.icon}}>
                         </div>
                         <span className="gearvn-cat-menu-name">{item.TenLoai}</span>
                       </Link>
                     </li>
                 ))}
                </ol>
              </nav>
            </div>
          </div>
          <div className="gearvn-header-navigation-block">  
            <div className="gearvn-header-banner">
              <div className="left" style={{width: '100%'}}>
                <div className="slider-wrap">
                  <Carousel>
                    {slides.map((slide, index) =>(
                      <Carousel.Item key={index}>
                        <img
                          className="d-block w-100"
                          src={linkImage+slide.image_name}
                          alt={slide.image_name}
                        />
                    </Carousel.Item>
                    ))}
                  </Carousel>
                </div>
              
              </div>
             
            </div>
          </div>
        </div>
      </div>
      <div className=" gearvn-content-section i100 mb-10" id="xxx-banner">
        <div className="row row-margin-small">
          <img src="https://lh3.googleusercontent.com/pw/AL9nZEVdWNLiFtJdhseX8bd1v7pR1EZ-xnPcmDByR205Ku3uxcTVgqNK_pw7XoQwUH8nLVaO1w6N4FcS6QenhH_vIOyiovbcdLdBC9ornBgtv_qU9hTxM3CzjkYrRdCTRgq3cCnQyJ78DRBtEBykFSOilqVJ=w1920-h528-no?authuser=0"/>
        </div>
      </div>
    </>
  );
}
export default React.memo(Banner) ;