import React, {useState, useEffect} from 'react'
import {Helmet} from 'react-helmet'
import {Link, useNavigate} from 'react-router-dom';
import MainAppBar from '../components/AppBar/AppBar'
import Footer from '../components/Footer/Footer'
import styles from '../styles/login.module.css';
import axios from 'axios';
import { CircularProgress } from '@mui/material';

export default function SignUp() {
    const [userCred, setUserCreds] = useState({
        username: '',
        password: '',
        address: "",
        phone_number: ""
    });
    const [error, seterror] = useState('');
    const [success, setsuccess] = useState('');
    const [loading, setloading] = useState(false);
    const navigate = useNavigate();

    const submitForm = async (e) => {
        e.preventDefault();
        setloading(true);
        seterror('');

        if (userCred.username === '') {
            return seterror('Please enter your username');
        } else if (userCred.password === '') {
            return seterror('Password is required');
        } else if (userCred.phone_number === '') {
            return seterror('Phone is required');
        }else if (userCred.address === '') {
            return seterror('Address is required');
        } else {
            axios({
                method: 'POST',
                url: `${process.env.REACT_APP_API_BASE_URL}/auth/signUp`,
                data: userCred,
            }).then((res) => {
                setsuccess('Authenticated successfully');

                const sessiondata = res.data;
                sessionStorage.setItem('bs_cus', JSON.stringify(sessiondata));
                document.getElementById('SignUpFm').reset();

                setTimeout(() => {
                    navigate('/')
                }, 1000)
            }).catch((e) => {
                seterror('An error occured, please try again');
                if (e.response.data) {
                    return seterror(e.response.data.error);
                }
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
            <Helmet>
                <title>Login - buysome eCommerce</title>
                <meta name="description" content="Sign Up to find and buy your favourites" />
                <link rel="icon" href="/favicon.ico" />
            </Helmet>
            <main className={styles.main}>
                {/* Appbar */}
                <MainAppBar />

                <div className={styles.lg_bx}>
                    <form className={styles.lg_frm} onSubmit={(e) => submitForm(e)} id='SignUpFm'>
                        <h3>SignUp</h3>

                        {
                            error || success ? (
                                <p className={`${error ? styles.lg_frm_erm : ''} ${success ? styles.lg_frm_scm : ''}`}>
                                    {error}{success}
                                </p>
                            ) : ''
                        }

                        <input type='text' autoComplete='off' name='username' placeholder='Username' onChange={(e) => dataSetter(e)}/>
                        <input type='password' autoComplete='off' name='password' placeholder='Password' onChange={(e) => dataSetter(e)}/>
                        <input type='tel' autoComplete='off' name='phone_number' placeholder='Phone number (+233)' onChange={(e) => dataSetter(e)}/>
                        <input type='text' autoComplete='off' name='address' placeholder='Address' onChange={(e) => dataSetter(e)}/>

                        <button type='submit' disabled={loading ? true : false}>
                            {
                                loading ? <CircularProgress color='inherit' size={'1.5rem'} /> : 'submit'
                            }
                        </button>
                        <div className={styles.lg_fgp_sg}>
                            <Link to='/forgotPassword'>
                                Forgot password?
                            </Link>
                            <Link to='/login'>
                                login
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
