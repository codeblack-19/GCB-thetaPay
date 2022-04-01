/* eslint-disable react-hooks/exhaustive-deps */
import { Button } from '@mui/material';
import React, { useContext, useEffect, useState } from 'react'
import Context from '../../../context_apis/ProductsContext';
import { Context as cb_Ctx } from '../../../context_apis/CartBoxContext';
import ATC_Model from '../../Cart/AddToCart/ATC_modal';
import styles from './products.module.css';

export default function CartBtns({ prod }) {
    const [isInCart, setIsInCart] = useState(false);
    const { products } = useContext(Context);
    const cB_Context = useContext(cb_Ctx);

    const checkCartForUser = () => {
        if(JSON.parse(sessionStorage.getItem('bs_cus'))){
            let cus = JSON.parse(sessionStorage.getItem('bs_cus'));
            prod.carts.map((cart) => {
                if (cart.product_id === prod.id && cart.customer_id === cus.user.id) {
                    return setIsInCart(true);
                }else{
                    return setIsInCart(false);
                }

                
            })
        }
    }

    useEffect(() => {
        let mount = true;

        if(mount){
            checkCartForUser();
        }

        return () => mount = false
    }, [products]);

    return (
        <>
            {
                isInCart ? (
                    <div style={{ display: 'flex', justifyContent: 'center' }}>
                        <Button className={styles.vIc_btn} onClick={cB_Context.handleOpen} >
                            View in Cart
                        </Button>
                    </div>
                ) : (
                    <ATC_Model product={prod} />
                )
            }
        </>
    )
}
