/* eslint-disable no-unused-vars */
import React, {useState} from "react";
import {Routes, Route, Navigate} from "react-router-dom";
import useSessionStorage from "../libs/useSessionStorage";
import Account from "../pages/account";
import Home from "../pages/home";
import Login from '../pages/login';
import SignUp from "../pages/signup";

export default function Navigator() {
    const [user, setuser] = useState(sessionStorage.getItem('bs_cus') ? JSON.parse(sessionStorage.getItem('bs_cus')) : "");

    const PrivateRoute1 = ({ children }) => {
        return user ? <Navigate to = "/"/> : children;
    }

    const PrivateRoute2 = ({ children }) => {
        return sessionStorage.getItem('bs_cus') ? children : <Navigate to = "/login"/> ;
    }

    return (
        <Routes>
            <Route exact path="/" element={<Home />} />
            <Route path="/login" element={<PrivateRoute1><Login /></PrivateRoute1>} />
            <Route path="/signup" element={<PrivateRoute1><SignUp /></PrivateRoute1>} />
            <Route path="/myaccount" element={<PrivateRoute2><Account /></PrivateRoute2>} />
        </Routes>
    )
}