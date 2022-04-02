/* eslint-disable react-hooks/exhaustive-deps */
import React, { createContext, useEffect } from "react";
import { useRouter } from 'next/router';

export const Context = createContext();

export const AuthProvider = ({children}) => {
    const router = useRouter();
   
    useEffect(() => {
        let mount = true;

        if(mount){
            if ((router.pathname === '/login' || router.pathname === '/signup') && JSON.parse(sessionStorage.getItem('bs_cus'))) {
                router.push('/');
            }
        }

        return () => mount = false;
    },[])
    
    return(
        <Context.Provider value={{}}>
            {children}
        </Context.Provider>
    )
}

export default Context