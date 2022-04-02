import React, { useState, useEffect, useContext } from 'react'
import Box from '@mui/material/Box';
import { CircularProgress, IconButton } from '@mui/material';
import { Edit, ShoppingCartRounded } from '@mui/icons-material';
import Modal from '@mui/material/Modal';
import styles from './Cartmodal.module.css';
import styles_2 from '../AddToCart/ATC.module.css';
import axios from 'axios';
import {Context as prodContext} from '../../../context_apis/ProductsContext';
import useSessionStorage from '../../../libs/useSessionStorage';

export default function EditCart({ prodInfo, reFetchData }) {
    const [open, setOpen] = useState(false);
    const handleOpen = () => setOpen(true);
    const handleClose = () => {
        seterror('');
        setsuccess('');
        reFetchData();
        setOpen(false);
    }
    const [error, seterror] = useState('');
    const [success, setsuccess] = useState('');
    const [loading, setloading] = useState(false);
    const [qty, setqty] = useState(prodInfo.quantity);
    const pContext = useContext(prodContext);
    const customer = useSessionStorage('bs_cus');

    const makeChanges = (e) => {
        e.preventDefault();
        setloading(true);
        seterror('');

        if (qty <= 0 || qty === '') {
            return seterror("Invalid quantity");
        } else {
            axios({
                url: `${process.env.NEXT_PUBLIC_API_BASE_URL}/carts/${prodInfo.id}`,
                method: 'PUT',
                data: {
                    product_id: prodInfo.product_id,
                    quantity: qty
                },
                headers: {
                    'Authorization': `Bearer ${customer.access_token}`
                }
            }).then((res) => {
                pContext.fetchProducts();
                return setsuccess(res.data.message);
            }).catch((e) => {
                if (e.response.data) {
                    return seterror(e.response.data.error);
                }
                seterror('An error occured, please try again');
            })
        }
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
                <Edit />
            </IconButton>
            <Modal
                open={open}
                onClose={handleClose}
                aria-labelledby="modal-modal-title"
                aria-describedby="modal-modal-description"
            >
                <Box className={styles_2.atc_md_bx}>
                    <div className={styles_2.atc_md_crt}>
                        <h4>Edit Quantity</h4> <ShoppingCartRounded fontSize='large' />
                    </div>
                    <form className={styles_2.ATC_form} onSubmit={(e) => makeChanges(e)}>
                        {
                            error || success ? (
                                <p className={`${error ? styles_2._frm_erm : ''} ${success ? styles_2._frm_scm : ''}`}>
                                    {error}{success}
                                </p>
                            ) : ''
                        }

                        
                        <label htmlFor='quantity'>{prodInfo.product.name}</label>
                        <input type='number' style={{textAlign: 'center'}} autoComplete='off' name='quantity' defaultValue={prodInfo.quantity} placeholder='Enter quantity here' onChange={(e) => setqty(e.target.value)} />
                        <button type='submit' disabled={loading ? true : false}>
                            {
                                loading ? <CircularProgress color='inherit' size={'1.5rem'} /> : 'finish'
                            }
                        </button>

                    </form>
                </Box>
            </Modal>
        </>
    )
}
