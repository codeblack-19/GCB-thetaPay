import { Button, Chip, Divider, Grid, Paper, Box, Modal, CircularProgress, Typography } from '@mui/material'
import axios from 'axios';
import React, { useState, useEffect } from 'react';
import styles from "../../styles/account.module.css";
import styles1 from "../HomeComps/Products/products.module.css";

export default function Userorders() {
    const [orders, setorders] = useState([]);
    const [open, setOpen] = useState(false);
    const [msg, setmsg] = useState('');
    const [loading, setloading] = useState(false)
    const handleOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);

    const fetchOrders = async () => {
        if (JSON.parse(sessionStorage.getItem('bs_cus'))) {
            let cus = JSON.parse(sessionStorage.getItem('bs_cus'))
            await axios({
                method: 'GET',
                url: `${process.env.REACT_APP_API_BASE_URL}/orders/${cus.user.id}`,
                headers: {
                    'authorization': `Bearer ${cus.access_token}`
                }
            }).then((res) => {
                return setorders(res.data.sort((a, b) => (new Date(b.createdAt)) - (new Date(a.createdAt))));
            }).catch((e) => {
                console.log(e);
            })
        }
    }

    const style = {
        position: 'absolute',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        maxWidth: 400,
        bgcolor: 'background.paper',
        border: '1px solid #000',
        boxShadow: 24,
        textAlign: 'center',
        p: 4,
    };

    const revertOrder = async (order_id) => {
        let confirmOpera = window.confirm(`Do you really want to revert and refund order with ID = ${order_id}`);

        if(confirmOpera){
            setloading(true);
            handleOpen();

            let cus = JSON.parse(sessionStorage.getItem('bs_cus'))
            await axios({
                method: 'POST',
                url: `${process.env.REACT_APP_API_BASE_URL}/orders/${order_id}/revert`,
                headers: {
                    'authorization': `Bearer ${cus.access_token}`
                }
            }).then((res) => {
                fetchOrders();
                setmsg(res.data.message);
                setloading(false);
            }).catch((e) => {
                setloading(true);
                console.log(e);
            })
        }
    }


    useEffect(() => {
        fetchOrders();
    }, [])

  return (
    <div>
        <br />

        {
            orders.map((order, i) => {
                return (
                    <Paper sx={{ p: '10px 10px', m: '0 0 15px' }} key={i} elevation={2} >
                        <Grid container direction='row' justifyContent={'space-evenly'} alignItems='center' spacing={3}>
                            <Grid item md={3}>
                                <h3 className={styles.orderNo} >Order ID: {order.id}</h3>
                            </Grid>
                            <Grid item md={3} sx={{textAlign: 'center'}} >
                                <Chip label={order.status} color={order.status === 'success' ? 'success' : 'info'} />
                            </Grid>
                            <Grid item md={3} sx={{textAlign: 'center'}} >
                                {order.status === "success" || order.status === "pending" ? (
                                    <Button variant='contained' size='small' color='warning' onClick={() => {revertOrder(order.id)}} >Revert & Refund</Button>
                                ): ''}
                            </Grid>
                        </Grid>

                        <Divider sx={{m: '10px 0'}} />

                        <Paper elevation={0}>
                            <h4 className={styles.orderDetH}>Order Details</h4>

                            <Grid className={styles.orderDetBx} container direction='row' justifyContent={'space-evenly'} alignItems='center' spacing={3}>
                                <Grid item md={3}>
                                    <div>
                                        <h5>Email: </h5>
                                        <p>{order.order_email}</p>
                                    </div>
                                </Grid>
                                <Grid item md={3}>
                                    <div>
                                        <h5>Shipping Address: </h5>
                                        <p>{order.shipping_address}</p>
                                    </div>
                                </Grid>
                                <Grid item md={3}>
                                    <div>
                                        <h5>Date: </h5>
                                        <p>{new Date(order.createdAt).toUTCString()}</p>
                                    </div>
                                </Grid>
                            </Grid>

                            <Divider sx={{m: '10px 0'}} />

                            <h5 className={styles.orderDetProd}>Products</h5>
                            
                            <Grid container direction='row' className={styles1.pd_ctn_grd} justifyContent='center' wrap='wrap' spacing={3}>
                                {
                                    order.order_details.map((prod, key) => (
                                        <Grid item xs={12} sm={6} md={4} lg={3} key={key}>
                                            <Paper className={styles1.pd_cd_hm}>
                                                <div className={styles.pd_cd_img_bx} style={{ backgroundImage: `url(${prod.product.image_url})` }}>
                                                    <p className={styles1.pd_cd_price}>
                                                        GH&#x20B5; {prod.product.price}
                                                    </p>
                                                </div>
                                                <div className={styles1.pd_cd_text}>
                                                    <p className={styles.pd_cd_name} title={prod.name}>
                                                        {prod.product.name} (Quantity : {prod.quantity})
                                                    </p>
                                                    <p className={styles1.pd_cd_desc}>
                                                        {prod.product.description}
                                                    </p>
                                                    
                                                </div>
                                            </Paper>
                                        </Grid>
                                    ))
                                }


                            </Grid>

                        </Paper>

                    </Paper>
                )
            })
        }

        <Modal
            open={loading ? true : open}
            disableEscapeKeyDown={loading ? false : true}
            onBackdropClick={loading ? handleOpen : handleClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
        >
            <Box sx={style}>
                {
                    loading ? (
                        <CircularProgress color='warning' />
                    ) : (
                        <Typography variant="h5" gutterBottom component="p">
                            {msg}
                        </Typography>
                    )
                }
            </Box>
        </Modal>
    </div>
  )
}
