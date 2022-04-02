/* eslint-disable react-hooks/exhaustive-deps */
import React, { createContext, useState, useEffect } from "react";
import axios from "axios";

export const Context = createContext();

export const ProductProvider = ({children}) => {
    const [products, setproducts] = useState([]);

    const fetchProducts = async () => {
        setproducts([])
        await axios.get(`${process.env.NEXT_PUBLIC_API_BASE_URL}/products/`).then((res) => {
            return setproducts(res.data)
        }).catch((e) => {
            console.log(e);
        })
    }


    useEffect(() => {
        fetchProducts();
    }, [])
    
    return(
        <Context.Provider value={{products, fetchProducts}}>
            {children}
        </Context.Provider>
    )
}

export default Context