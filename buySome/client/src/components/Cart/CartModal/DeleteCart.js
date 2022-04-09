import React, { useState, useEffect, useContext } from 'react'
import Box from '@mui/material/Box';
import { CircularProgress, IconButton, Typography } from '@mui/material';
import { RemoveCircle, ShoppingCartRounded } from '@mui/icons-material';
import Modal from '@mui/material/Modal';
import styles from './Cartmodal.module.css';
import styles_2 from '../AddToCart/ATC.module.css';
import axios from 'axios';
import { Context as prodContext } from '../../../context_apis/ProductsContext';
import { Context as cartSizeCtx } from '../../../context_apis/CartSizeContext';
import useSessionStorage from '../../../libs/useSessionStorage';

export default function DeleteCart({ prodInfo, reFetchData }) {
    const [open, setOpen] = useState(false);
    const handleOpen = () => setOpen(true);
    const [error, seterror] = useState('');
    const [success, setsuccess] = useState('');
    const [loading, setloading] = useState(false);
    const pContext = useContext(prodContext);
    const cs_ctx = useContext(cartSizeCtx);
    const customer = useSessionStorage('bs_cus');

    const handleClose = () => {
        seterror('');
        setsuccess('');
        setOpen(false);
    }

    const deleteItem = async (e) => {
        e.preventDefault();
        setloading(true);
        seterror('');

        await axios({
            url: `${process.env.REACT_APP_API_BASE_URL}/carts/${prodInfo.id}`,
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${customer.access_token}`
            }
        }).then((res) => {
            cs_ctx.getCartSize();
            pContext.fetchProducts();
            reFetchData();
            return setsuccess(res.data.message);
        }).catch((e) => {
            if (e.response.data) {
                return seterror(e.response.data.error);
            }
            seterror('An error occured, please try again');
        })

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
            mount = false;
        }
    }, [error, success])


    return (
        <>
            <IconButton
                size="small"
                edge="end"
                aria-label="view product"
                aria-haspopup="true"
                color="inherit"
                className={styles.cpd_crd_btn}
                onClick={handleOpen}
            >
                <RemoveCircle />
            </IconButton>
            <Modal
                open={open}
                onClose={handleClose}
                aria-labelledby="modal-modal-title"
                aria-describedby="modal-modal-description"
            >
                <Box className={styles_2.atc_md_bx}>
                    <div className={styles_2.atc_md_crt}>
                        <h4>Delete Cart Item</h4> <ShoppingCartRounded fontSize='large' />
                    </div>
                    <form className={styles_2.ATC_form} onSubmit={(e) => deleteItem(e)}>
                        {
                            error || success ? (
                                <p className={`${error ? styles_2._frm_erm : ''} ${success ? styles_2._frm_scm : ''}`}>
                                    {error}{success}
                                </p>
                            ) : ''
                        }

                        {
                            success === '' ? (
                                <>
                                    <Typography variant='h6' component='p' className={styles.del_cart_text}>
                                        You are deleting <br />
                                        <span>{prodInfo.product.name}</span> <br />
                                        from cart?
                                    </Typography>

                                    <button type='submit' disabled={loading ? true : false}>
                                        {
                                            loading ? <CircularProgress color='inherit' size={'1.5rem'} /> : 'Yes, Delete'
                                        }
                                    </button>
                                </>

                            ) : ''
                        }

                    </form>
                </Box>
            </Modal>
        </>
    )
}
