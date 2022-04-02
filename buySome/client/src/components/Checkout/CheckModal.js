/* eslint-disable no-unused-vars */
import React, {useState, useEffect} from 'react';
import { Box, Modal, Button, CircularProgress } from '@mui/material';
import styles from './checkout.module.css';
import styles_2 from '../Cart/AddToCart/ATC.module.css';

export default function CheckModal({cartProds}) {
    const [open, setOpen] = useState(false);
    const [error, seterror] = useState('');
    const [success, setsuccess] = useState('');
    const [loading, setloading] = useState(false);
    const [order, setorderinfo] = useState({
        order_email: "",
        billing_info: "GCB-thetaPay",
        shipping_address: "",
        order_details: []
    });

    const [txnInfo, settxnInfo] = useState({
        
    })

    const handleOpen = () => setOpen(true);
    const handleClose = () => {
        setOpen(false);
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
            open={open}
            onClose={handleClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
        >
            <Box className={styles_2.atc_md_bx}>
                <form>
                    <input type='email' autoComplete='off' required={true} name='order_email' placeholder='Email' onChange={(e) => dataSetter(e)}/>
                    <input type='text' autoComplete='off' required={true} name='shipping_address' placeholder='Shipping Address' onChange={(e) => dataSetter(e)}/>
                    
                    <button type='submit' disabled={loading ? true : false}>
                        {
                            loading ? <CircularProgress color='inherit' size={'1.5rem'} /> : 'Initiate Payment'
                        }
                    </button>
                </form>
            </Box>
        </Modal>
    </>
  )
}
