import { React, useState, useEffect } from "react";
import Modal from "react-bootstrap/Modal";
import "./ModalNews.css";
function ModalNews() {
  const [show, setShow] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  useEffect(() => {
    setTimeout(() => {
      setShow(true);
    }, 2000);
  }, []);
  return (
    <>
      <Modal show={show} onHide={handleClose} className="modal-news">
        <p className="close-modal" onClick={handleClose}>
          [x]
        </p>
        <img
          src="https://cdn3.dhht.vn/wp-content/uploads/2022/12/mini-banner-trang-chu-fossil-heritage.jpg"
          alt="modal__image"
        />
      </Modal>
    </>
  );
}

export default ModalNews;
