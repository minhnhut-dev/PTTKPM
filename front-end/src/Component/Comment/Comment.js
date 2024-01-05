import React, { useEffect, useState } from "react";
import {
  Divider,
  Avatar,
  Grid,
  Paper,
  TextField,
  Box,
} from "@material-ui/core";

function Comment(props) {
  const { comment } = props;
  return (
    <>
      {comment.map((item, index) => (
        <Paper style={{ padding: "40px 20px", margin: "30px 0" }} key={index}>
          <Grid container wrap="nowrap" spacing={2}>
            <Grid item>
              <Avatar alt="Remy Sharp" src={item.user.Anh} />
            </Grid>
            <Grid justifyContent="left" item xs zeroMinWidth>
             
              <h4 style={{ margin: 0, textAlign: "left" }}>{item.user.TenNguoidung}</h4>
              <p style={{ textAlign: "left" }}>{item.content}</p>
            </Grid>
          </Grid>
        </Paper>
      ))}

      {/* <Divider variant="fullWidth" style={{ margin: "30px 0" }} /> */}
    </>
  );
}

export default Comment;
