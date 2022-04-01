/* eslint-disable react-hooks/exhaustive-deps */
import React, { useState, useEffect } from 'react'
import Head from 'next/head'
import Link from 'next/link';
import styles from '../styles/login.module.css';
import MainAppBar from '../components/AppBar/AppBar';
import Footer from '../components/Footer/Footer';
import axios from 'axios';
import { CircularProgress } from '@mui/material';
import { useRouter } from 'next/router';

export default function Login() {
    const [userCred, setUserCreds] = useState({
        username : '',
        password : ''
    });
    const [error, seterror] = useState('');
    const [success, setsuccess] = useState('');
    const [loading, setloading] = useState(false);
    const router = useRouter();
    
    const getToken = async (e) => {
        e.preventDefault();
        setloading(true);
        seterror('');

        if(userCred.username === ''){
            return seterror('Please enter your username');
        }else if(userCred.password === ''){
            return seterror('Password is required');
        }else{
            axios({
                method: 'POST',
                url: `${process.env.NEXT_PUBLIC_API_BASE_URL}/auth/login_ct`,
                data : userCred,
            }).then((res) => {
                setsuccess('Authenticated successfully');

                const sessiondata = res.data;
                sessionStorage.setItem('bs_cus', JSON.stringify(sessiondata));
                document.getElementById('loginFm').reset();
                
                setTimeout(() => {
                    router.push('/')
                }, 1000)
            }).catch((e) => {
                if (e.response.data) {
                    return seterror(e.response.data.error);
                }
                seterror('An error occured, please try again');
            })
        }
    }

    useEffect(() => {
        let mount = true;

        if (mount) {
            if (error || success) {
                setloading(false);
            }

            if (success) {
                seterror('');
            }

        }

        return () => {
            mount = false
        }
    }, [error, success])


    const dataSetter = (e) => {
        let hold = userCred;
        hold[e.target.name] = e.target.value;
        setUserCreds(hold);
    }

    return (
        <div className={styles.container}>
            <Head>
                <title>Login - buysome eCommerce</title>
                <meta name="description" content="Login to find and buy your favourites" />
                <link rel="icon" href="/favicon.ico" />
            </Head>
            <main className={styles.main}>
                {/* Appbar */}
                <MainAppBar />

                <div className={styles.lg_bx}>
                    <form className={styles.lg_frm} onSubmit={(e) => getToken(e)} id='loginFm'>
                        <h3>Login</h3>

                        {
                            error || success ? (
                                <p className={`${error ? styles.lg_frm_erm : ''} ${success ? styles.lg_frm_scm : ''}`}>
                                    {error}{success}
                                </p>
                            ) : ''
                        }

                        <input type='text' autoComplete='off' name='username' placeholder='Username' onChange={(e) => dataSetter(e)} />
                        <input type='password' autoComplete='off' name='password' placeholder='Password' onChange={(e) => dataSetter(e)} />

                        <button type='submit' disabled={loading ? true : false}>
                            {
                                loading ? <CircularProgress color='inherit' size={'1.5rem'} /> : 'Login'
                            }
                        </button>

                        <div className={styles.lg_fgp_sg}>
                            <Link href='/forgotpassword'>
                                <a>Forgot password?</a>
                            </Link>
                            <Link href='/signup'>
                                <a>SignUp</a>
                            </Link>
                        </div>
                    </form>
                </div>

                {/* footer */}
                <Footer />
            </main>
        </div>
    )
}
