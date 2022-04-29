/* eslint-disable react-hooks/exhaustive-deps */
/* eslint-disable no-unused-vars */
import React, {useState, useEffect} from 'react';
import { Box, Modal, Button, CircularProgress } from '@mui/material';
import styles from './checkout.module.css';
import styles_2 from '../Cart/AddToCart/ATC.module.css';
import axios from 'axios';

export default function CheckModal({cartProds}) {
    const [open, setOpen] = useState(false);
    const [error, seterror] = useState('');
    const [success, setsuccess] = useState('');
    const [loading, setloading] = useState(false);
    const [txn_id, settxn_id] = useState(localStorage.getItem("_txnId") ? JSON.parse(localStorage.getItem("_txnId")).txn_id : "");
    const [order, setorderinfo] = useState({
        order_email: localStorage.getItem("_txnId") ? JSON.parse(localStorage.getItem("_txnId")).email : "",
        billing_info: "GCB-thetaPay",
        shipping_address: localStorage.getItem("_txnId") ? JSON.parse(localStorage.getItem("_txnId")).shipping_address : "",
        order_details: [],
        txn_id: localStorage.getItem("_txnId") ? JSON.parse(localStorage.getItem("_txnId")).txn_id : ""
    });

    const [txnInfo, settxnInfo] = useState({
        "amount": cartProds.reduce((sum, prod) => {
            return sum + (prod.product.price * prod.quantity)
        }, 0.00).toFixed(2),
        "currency": "GHS",
        "description": "purchase item",
        "webhook": "http://localhost:3006/api/v1/transactions/webpaymenthook"
    })

    const handleOpen = () => setOpen(true);
    const handleClose = () => {
        seterror("");
        setsuccess("");
        setOpen(false);
    }

    useEffect(() => {
        let mount = true;

        if (mount) {

            if (error || success) {
                setloading(false);
            }

            if (success) {
                seterror('');
            }
        }

        return () => {
            mount = false
        }
    }, [error, success])

    useEffect(() => {
        if (localStorage.getItem("_txnId")) {
            setloading(true);
            checkTxnStatus();
        }
    },[])

    const checkoutCart = async (e) => {
        e.preventDefault();
        setloading(true);
        seterror('');

        if (order.order_email === '') {
            return seterror('Please enter your username');
        } else if (order.shipping_address === '') {
            return seterror('Password is required');
        } else { 
            // send request to gateway
            await axios({
                url: `${process.env.REACT_APP_GCB_THETAPAY_API}/transactions/initiate_payment`,
                method: 'POST',
                data: txnInfo,
                headers: {
                    'Content-Type': 'application/json',
                    "Authorization": `Bearer ${process.env.REACT_APP_GCB_THETAPAY_SECRETEKEY}`,
                },
            }).then((res) => {
                let txn = res.data;
                settxn_id(txn.txn_id);
                localStorage.setItem(
                    "_txnId", JSON.stringify({
                                txn_id: txn.txn_id, 
                                email: order.order_email, 
                                shipping_address: order.shipping_address,
                                date: txn.edt
                            })
                );
                window.open(txn.link, "_blank").focus();
                return checkTxnStatus();
            }).catch((e) => {
                seterror('An error occured, please try again');
                if (e.response.data) {
                    return seterror(e.response.data.error);
                }
            })
        }

    }

    const placeOrder = async ()  => {
        let tmp = order;
        cartProds.forEach((prod) => {
            tmp['order_details'].push(prod.id);
        });
        tmp['txn_id'] = JSON.parse(localStorage.getItem("_txnId")).txn_id;
        setorderinfo(tmp);

        let user = JSON.parse(sessionStorage.getItem('bs_cus'))
        await axios({
            url: `${process.env.REACT_APP_API_BASE_URL}/orders/checkout`,
            method: 'POST',
            data: order,
            headers: {
                "Authorization": `Bearer ${user.access_token}`,
            },
        }).then((res) => {
            localStorage.removeItem("_txnId");
            setsuccess(res.data.message);
            setTimeout(() => {
                window.location.href = '/myaccount';
            }, 1500)
        }).catch((e) => {
            seterror('An error occured, please try again');
            if (e.response.data) {
                localStorage.removeItem("_txnId");
                return seterror(e.response.data.error);
            }
        })
    } 

    const checkTxnStatus = async () => {
        let user = JSON.parse(sessionStorage.getItem('bs_cus'));
        let exdt = JSON.parse(localStorage.getItem("_txnId")).edt
        if((new Date(exdt)) < (new Date())){
            localStorage.removeItem('_txnId');
            return seterror('Checkout Transaction failed');
        }

        await axios({
            url: `${process.env.REACT_APP_API_BASE_URL}/transactions/${JSON.parse(localStorage.getItem("_txnId")).txn_id}`,
            method: 'GET',
            headers: {
                "Authorization": `Bearer ${user.access_token}`,
            },
        }).then((res) => {
            if(res.data){
                if(res.data.status === 'success'){
                    return placeOrder();
                }else if(res.data.status === 'failed'){
                    seterror('Checkout Transaction failed');
                    localStorage.removeItem('_txnId');
                }else{
                    setTimeout(() => {
                        checkTxnStatus()
                    }, 2000)
                }
            }else{
                setTimeout(() => {
                    checkTxnStatus()
                }, 2000)
            }
        }).catch((e) => {
            seterror('An error occured, please try again');
            if (e.response.data) {
                localStorage.removeItem("_txnId");
                return seterror(e.response.data.error);
            }
        })
    }

    const dataSetter = (e) => {
        let hold = order;
        hold[e.target.name] = e.target.value;
        setorderinfo(hold);
    }
    
    return (
    <>
        <Button variant="contained" size="medium" className={styles.cob_btn} onClick={handleOpen} >
            Checkout
        </Button>

        <Modal
            open={loading ? true : open}
            disableEscapeKeyDown={loading ? false : true}
            onBackdropClick={loading ? handleOpen : handleClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
        >
            <Box className={styles_2.atc_md_bx}>
                <form onSubmit={(e) => checkoutCart(e)} className={styles.co_fm}>

                    {
                        loading? (
                            <CircularProgress color='inherit' size={'3rem'} />
                        ) : (
                            <>  
                                <h3>Finish Checkout</h3>
                                {
                                    error || success ? (
                                        <p className={`${error ? styles._frm_erm : ''} ${success ? styles._frm_scm : ''}`}>
                                            {error}{success}
                                        </p>
                                    ) : ''
                                }

                                {
                                    !success ? (
                                        <>
                                            <input type='email' autoComplete='off' required={true} name='order_email' placeholder='Email' onChange={(e) => dataSetter(e)}/>
                                            <input type='text' autoComplete='off' required={true} name='shipping_address' placeholder='Shipping Address' onChange={(e) => dataSetter(e)}/>
                                            
                                            <button type='submit' disabled={loading ? true : false}>
                                                Initiate Payment
                                            </button>
                                        </>
                                    ) : ""
                                }
                            </>
                        )
                    }
                </form>
            </Box>
        </Modal>
    </>
  )
}
