/* eslint-disable react-hooks/exhaustive-deps */
import React, { useContext } from 'react'
import { Badge, IconButton } from '@mui/material';
import { ShoppingCart } from '@mui/icons-material';
import { Context } from '../../../context_apis/CartSizeContext';
import { Context as cB_Context } from '../../../context_apis/CartBoxContext'

export default function CartBadge() {
    const {cartSize} = useContext(Context);
    const {handleOpen} = useContext(cB_Context)

    return (
        <IconButton onClick={handleOpen} size="large" aria-label="show 4 new mails" color="inherit">
            <Badge badgeContent={cartSize} color="error">
                <ShoppingCart />
            </Badge>
        </IconButton>
    )
}
