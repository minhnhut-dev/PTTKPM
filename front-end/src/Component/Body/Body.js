import React, { useEffect, useState }  from "react";
import "./Body.css";
import Skeleton from 'react-loading-skeleton';
import ProductBestSellingByBrand from "../../Component/Product/ProductBestSellingByBrand/ProductBestSellingByBrand";
import ProductCatalogue from "../Product/ProductCatalogue/ProductCatalogue";
import {getProductCatalogues} from "../../apis/products";
function Body(props) { 
  const [productCatalogues,setProductCatalogues] = useState([]);
    useEffect(() => {
      getProductCatalogues().then((response) => {
        setProductCatalogues(response.data);
      })
    },[]);

  const {products,loading}=props;
    return (
    <>
      <div className="container pd0-sm-mb">
        <div dangerouslySetInnerHTML={{__html: `  <div class="zalo-chat-widget" data-oaid="1117289676740842034" data-welcome-message="Rất vui khi được hỗ trợ bạn!" data-autopopup="0" data-width="" data-height=""></div>`}}></div>
       {loading ?<Skeleton  count={5} /> :  <ProductBestSellingByBrand products={products}/>} 
       {productCatalogues.map((item,index)=>(
          loading ? <Skeleton  count={5}/> : <ProductCatalogue key={index} title={item.name} id={item.id} />
       ))}
      </div>
    </>
  );
}

export default Body;
