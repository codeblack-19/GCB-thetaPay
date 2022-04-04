/* eslint-disable react-hooks/exhaustive-deps */
import { useContext, useEffect } from 'react'
import MainAppBar from '../components/AppBar/AppBar'
import Footer from '../components/Footer/Footer'
import HomeProducts from '../components/HomeComps/Products/Products'
import styles from '../styles/Home.module.css'
import Context from '../context_apis/CartSizeContext'
import CartModal from '../components/Cart/CartModal/CartModal'
import { Helmet } from 'react-helmet'
import axios from 'axios'

export default function Home() {
  const cartContext = useContext(Context);

  const testAPi = async () => {
    await axios({
      url: `${process.env.REACT_APP_GCB_THETAPAY_API}/`,
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        "Authorization": `Bearer ${process.env.REACT_APP_GCB_THETAPAY_SECRETEKEY}`,
      },
    }).then((res) => {
      console.log(res.data);
    }).catch((e) => {
      console.log(e);
    })
  }

  useEffect(() => {
    cartContext.getCartSize();
    testAPi();
  },[])
  
  return (
    <div className={styles.container}>
      <Helmet>
        <title>buysome eCommerce</title>
        <meta name="description" content="find and buy all your products on one ecommerce site" />
        <link rel="icon" href="/favicon.ico" />
      </Helmet>

      <main className={styles.main}>
        {/* header bar */}
        <MainAppBar />

        {/* bannrer */}
        <div className={styles.bs_bn_ctn}>
          <div className={styles.bs_bn_in}>
            <h3>welcome to <span>buysome</span> eCommerce</h3>
            <p>find and buy you favourites here</p>
          </div>
        </div>

        {/* products */}
        <HomeProducts />

        {/* cart box */}
        <CartModal />

        {/* footer */}
        <Footer />

      </main>

    </div>
  )
}
