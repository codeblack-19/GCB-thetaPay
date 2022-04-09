/* eslint-disable react-hooks/exhaustive-deps */
import React, { useContext, useEffect, useState } from 'react';
import Box from '@mui/material/Box';
import Drawer from '@mui/material/Drawer';
import Divider from '@mui/material/Divider';
import { ShoppingCart } from '@mui/icons-material';
import Context from '../../../context_apis/CartBoxContext';
import styles from './Cartmodal.module.css';
import { Grid, Paper, Typography } from '@mui/material';
import EditCart from './EditCart';
import axios from "axios";
import DeleteCart from './DeleteCart';
import CheckModal from '../../Checkout/CheckModal';

export default function CartModal() {
    const { open, handleClose } = useContext(Context);
    const [cartprod, setCartprod] = useState([]);

    const getCartprod = async () => {
        if (JSON.parse(sessionStorage.getItem('bs_cus'))) {
            let cus = JSON.parse(sessionStorage.getItem('bs_cus'))
            await axios({
                method: 'GET',
                url: `${process.env.REACT_APP_API_BASE_URL}/carts/${cus.user.id}`,
                headers: {
                    'authorization': `Bearer ${cus.access_token}`
                }
            }).then((res) => {
                return setCartprod(res.data);
            }).catch((e) => {
                console.log(e);
            })
        }
    }

    useEffect(() => {
        let mount = true;

        if(mount && open){
            getCartprod();
        }

        return () => mount = false;
    }, [open])

    return (
        <div>
            <Drawer
                anchor='right'
                open={open}
                onClose={handleClose}
            >
                <Box
                    role="presentation"
                    className={styles.ctb_bx}
                >

                    <Typography className={styles.ctb_h2} component='h2'>
                        <span>My Cart </span>
                        <ShoppingCart fontSize='large' />
                    </Typography>
                    <div className={styles.cpd_bx}>
                        <Grid container direction='column' spacing={2} justifyContent='center'>
                            {
                                cartprod.map((prod, key) => {
                                    return (
                                        <Grid key={key} container item className={styles.cpd_card} lg={12} spacing={2} justifyContent='space-around'>
                                            <Grid item xs={4} lg={4}>
                                                <Paper className={styles.cpd_crd_img} style={{ backgroundImage: `url(${prod.product.image_url})` }}></Paper>
                                            </Grid>
                                            <Grid item className={styles.cpd_crd_text} lg={6}>
                                                <h4>
                                                    {prod.product.name}
                                                </h4>
                                                <div className={styles.cpd_qty_price}>
                                                    <p>Qty: <span>{prod.quantity}</span></p>
                                                    <p>Price: <span>GH&#x20B5; {prod.product.price}</span></p>
                                                </div>
                                                <p className={styles.cpd_ptotal}>
                                                    Total: <span>GH&#x20B5; {prod.product.price * prod.quantity}</span>
                                                </p>
                                            </Grid>
                                            <Grid container direction='row' item className={styles.cpd_crd_btns} lg={2} spacing={1} justifyContent='center' >
                                                <Grid item lg={12}>
                                                    <EditCart prodInfo={prod} reFetchData={getCartprod} />
                                                </Grid>
                                                <Grid item lg={12}>
                                                    <DeleteCart prodInfo={prod} reFetchData={getCartprod} />
                                                </Grid>
                                            </Grid>
                                            <Grid item xs={12}>
                                                <Divider />
                                            </Grid>
                                        </Grid>
                                    )
                                })
                            }
                        </Grid>
                    </div>
                    {
                        cartprod.length !== 0 ? (
                            <div className={styles.cob_bx}>
                                <p> Total Amount: 
                                    <span>
                                        GH&#x20B5;{' '}
                                        {
                                            cartprod.reduce((sum, prod) => {
                                                return sum + (prod.product.price * prod.quantity)
                                            }, 0.00).toFixed(2)
                                        }
                                    </span>
                                </p>
                                <CheckModal cartProds={cartprod} />
                            </div>
                        ) : ''
                    }
                    
                </Box>
            </Drawer>
        </div>
    )
}