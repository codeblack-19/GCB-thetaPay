import React, {} from "react";
import {Routes, Route, Navigate} from "react-router-dom";
import useSessionStorage from "../libs/useSessionStorage";
import Home from "../pages/home";
import Login from '../pages/login';
import SignUp from "../pages/signup";

export default function Navigator() {
    const user = useSessionStorage('bs_cus');

    const PrivateRoute1 = ({ children }) => {
        return user ? <Navigate to = "/" /> : children;
    }

    return (
        <Routes>
            <Route exact path="/" element={<Home />} />
            <Route path="/login" element={<PrivateRoute1><Login /></PrivateRoute1>} />
            <Route path="/signup" element={<PrivateRoute1><SignUp /></PrivateRoute1>} />
        </Routes>
    )
}