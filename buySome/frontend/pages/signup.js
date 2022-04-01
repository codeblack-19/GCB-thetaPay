import React from 'react'
import Head from 'next/head'
import Link from 'next/link';
import MainAppBar from '../components/AppBar/AppBar'
import Footer from '../components/Footer/Footer'
import styles from '../styles/login.module.css';


export default function SignUp() {
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
                    <form className={styles.lg_frm}>
                        <h3>SignUp</h3>

                        <input type='text' autoComplete='off' name='username' placeholder='Username' />
                        <input type='password' autoComplete='off' name='passaord' placeholder='Password' />
                        <input type='tel' autoComplete='off' name='phone_number' placeholder='Phone number (+233)' />
                        <input type='text' autoComplete='off' name='address' placeholder='Address' />

                        <button type='button'>Submit</button>
                        <div className={styles.lg_fgp_sg}>
                            <Link href='/forgotPassword'>
                                <a>Forgot password?</a>
                            </Link>
                            <Link href='/login'>
                                <a>login</a>
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
