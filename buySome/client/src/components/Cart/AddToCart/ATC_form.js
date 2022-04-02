import React, { useState, useEffect, useContext } from 'react'
import axios from 'axios';
import { CircularProgress } from '@mui/material';
import styles from './ATC.module.css';
import useSessionStorage from '../../../libs/useSessionStorage';
import {Context as prodContext} from '../../../context_apis/ProductsContext';
import { Context as cartSizeCtx } from '../../../context_apis/CartSizeContext';

export default function ATC_form({ product }) {
    const [error, seterror] = useState('');
    const [success, setsuccess] = useState('');
    const [loading, setloading] = useState(false);
    const [qty, setqty] = useState(0);
    const customer = useSessionStorage('bs_cus');
    const pContext = useContext(prodContext);
    const cs_ctx = useContext(cartSizeCtx);

    const addToCart = async (e) => {
        e.preventDefault();
        setloading(true);
        seterror('');

        if (qty <= 0 || qty === '') {
            return seterror("Invalid quantity");
        } else {
            await axios({
                url: `${process.env.NEXT_PUBLIC_API_BASE_URL}/carts`,
                method: 'POST',
                data: {
                    product_id: product.id,
                    quantity: qty
                },
                headers: {
                    'Authorization': `Bearer ${customer.access_token}`
                }
            }).then((res) => {
                pContext.fetchProducts();
                cs_ctx.getCartSize();
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
            mount = false
        }
    }, [error, success])

    return (
        <form className={styles.ATC_form} onSubmit={(e) => addToCart(e)}>
            {
                error || success ? (
                    <p className={`${error ? styles._frm_erm : ''} ${success ? styles._frm_scm : ''}`}>
                        {error}{success}
                    </p>
                ) : ''
            }

            {
                success === '' ? (
                    <>
                        <label htmlFor='quantity'>{product.name}</label>
                        <input type='number' autoComplete='off' name='quantity' placeholder='Enter quantity here' onChange={(e) => setqty(e.target.value)} />
                        <button type='submit' disabled={loading ? true : false}>
                            {
                                loading ? <CircularProgress color='inherit' size={'1.5rem'} /> : 'finish'
                            }
                        </button>
                    </>
                ) : ''
            }
        </form>
    )
}
