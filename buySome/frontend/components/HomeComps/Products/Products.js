/* eslint-disable react-hooks/exhaustive-deps */
import styles from "./products.module.css";
import { Grid, Paper, Skeleton, Typography } from '@mui/material'
import React, { useContext } from 'react'
import CartBtns from "./CartBtns";
import Context from "../../../context_apis/ProductsContext";

export default function HomeProducts() {
    const { products } = useContext(Context);

    return (
        <div className={styles.pd_ctn}>
            <Grid container direction='row' className={styles.pd_ctn_grd} justifyContent='center' wrap='wrap' spacing={3}>
                {
                    (products.length === 0 ? Array.from(new Array(3)) : products).map((prod, key) => (
                        <Grid item xs={12} sm={6} md={4} lg={3} key={key}>
                            <Paper className={styles.pd_cd_hm}>
                                {
                                    prod ? (
                                        <div className={styles.pd_cd_img_bx} style={{ backgroundImage: `url(${prod.image_url})` }}>
                                            <p className={styles.pd_cd_price}>
                                                GH&#x20B5; {prod.price}
                                            </p>
                                        </div>
                                    ) : (
                                        <Skeleton animation="wave" variant="rectangular" style={{ height: '12rem', width: '100%', marginBottom: '5px' }} />
                                    )
                                }

                                <div className={styles.pd_cd_text}>
                                    {
                                        prod ? (
                                            <Typography component='p' noWrap className={styles.pd_cd_name} title={prod.name}>
                                                {prod.name}
                                            </Typography>
                                        ) : (
                                            <Skeleton animation="wave" variant="rectangular" style={{ height: '20px', width: '100%', marginBottom: '5px' }} />
                                        )
                                    }

                                    {
                                        prod ? (
                                            <Typography component='p' className={styles.pd_cd_desc}>
                                                {prod.description}
                                            </Typography>
                                        ) : (
                                            <Skeleton animation="wave" variant="rectangular" style={{ height: '40px', width: '100%', marginBottom: '5px' }} />
                                        )
                                    }
                                </div>

                                <div className={styles.pd_cd_cart}>
                                    {
                                        prod ? (
                                            <CartBtns prod={prod} />
                                        ) : (
                                            <Skeleton animation="wave" variant="rectangular" style={{ height: '40px', width: '60%', margin: '0 auto 5px' }} />
                                        )
                                    }
                                </div>

                            </Paper>
                        </Grid>
                    ))
                }


            </Grid>
        </div>
    )
}
