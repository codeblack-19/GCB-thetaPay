/* eslint-disable react-hooks/exhaustive-deps */
import { useEffect, useState } from "react";

const useSessionStorage = (sname) => {
    const [value, setvalue] = useState(null);

    useEffect(() => {
        if(typeof window !== 'undefined'){
            setvalue(JSON.parse(sessionStorage.getItem(sname)));
        }
    },[])
    
    return value;
}

export default useSessionStorage;