import * as React from 'react';
import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import Modal from '@mui/material/Modal';
import styles from './ATC.module.css';
import { ShoppingCart, ShoppingCartRounded } from '@mui/icons-material';
import {Link} from 'react-router-dom';
import useSessionStorage from '../../../libs/useSessionStorage';
import ATCForm from './ATC_form';

export default function ATCModel({product}) {
  const [open, setOpen] = React.useState(false);
  const customer = useSessionStorage('bs_cus');
  const handleOpen = () => setOpen(true);
  const handleClose = () => setOpen(false);

  return (
    <div style={{ display: 'flex', justifyContent: 'center' }}>
      <Button onClick={handleOpen} className={styles.atc_btn}>
        <span>Add To Cart</span> <ShoppingCart />
      </Button>
      <Modal
        open={open}
        onClose={handleClose}
        aria-labelledby="modal-modal-title"
        aria-describedby="modal-modal-description"
      >
        <Box className={styles.atc_md_bx}>
          <div className={styles.atc_md_crt}>
            <h4>Add to </h4> <ShoppingCartRounded fontSize='large' /> 
          </div>
          {
            !customer ? (
              <>
                <Typography id="modal-modal-title" component="p" className={styles.atc_nlg_h6}>
                  Please login to add product to cart
                </Typography>
                <div className={styles.atc_nlg_btn_bx}>
                  <Link to={'/login'}>
                    <Button size='small' className={styles.atc_nlg_btn}>login</Button>
                  </Link>
                </div>
              </>
            ) : (
              <ATCForm product={product} />
            )
          }
        </Box>
      </Modal>
    </div>
  );
}