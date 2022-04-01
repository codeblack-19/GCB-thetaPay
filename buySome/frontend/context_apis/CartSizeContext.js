/* eslint-disable react-hooks/exhaustive-deps */
import React, { createContext, useState, useEffect } from "react";
import axios from "axios";

export const Context = createContext();

export const CartSizeProvider = ({children}) => {
    const [cartSize, setCartSize] = useState(0);

    const getCartSize = async () => {
        if(JSON.parse(sessionStorage.getItem('bs_cus'))){
            let cus = JSON.parse(sessionStorage.getItem('bs_cus'))
            await axios({
                method: 'GET',
                url: `${process.env.NEXT_PUBLIC_API_BASE_URL}/carts/${cus.user.id}/count`,
                headers: {
                    'authorization': `Bearer ${cus.access_token}`
                }
            }).then((res) => {
                return setCartSize(res.data.count);
            }).catch((e) => {
                console.log(e);
            })
        }
    }

    useEffect(() => {
        getCartSize();
    }, [])
    
    return(
        <Context.Provider value={{cartSize, setCartSize, getCartSize}}>
            {children}
        </Context.Provider>
    )
}

export default Context