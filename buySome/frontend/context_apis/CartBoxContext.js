/* eslint-disable react-hooks/exhaustive-deps */
import React, { createContext, useState } from "react";

export const Context = createContext();

export const CartBoxProvider = ({children}) => {
    const [open, setOpen] = useState(false);
    const handleOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);
    
    return(
        <Context.Provider value={{open, handleClose, handleOpen}}>
            {children}
        </Context.Provider>
    )
}

export default Context