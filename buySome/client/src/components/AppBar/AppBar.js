/* eslint-disable react/display-name */
import styles from './AppBar.module.css';
import {Link, useNavigate} from 'react-router-dom';
import React, { useEffect } from 'react';
import { AppBar, Toolbar, Menu, MenuItem, IconButton, Box, Button, ButtonGroup } from '@mui/material';
import { AccountCircle } from '@mui/icons-material';
import MoreIcon from '@mui/icons-material/MoreVert';
import AppbarOperation from '../../VanillaJs/AppbarOps';
import CartBadge from '../Cart/CartBadge/CartBadge';
import useSessionStorage from '../../libs/useSessionStorage';
import bslogo from '../../asserts/buysome1.png'

export default function MainAppBar(props) {
  const [anchorEl, setAnchorEl] = React.useState(null);
  const [mobileMoreAnchorEl, setMobileMoreAnchorEl] = React.useState(null);
  const customer = useSessionStorage('bs_cus');
  const navigate = useNavigate();

  const isMenuOpen = Boolean(anchorEl);
  const isMobileMenuOpen = Boolean(mobileMoreAnchorEl);

  const handleProfileMenuOpen = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleMobileMenuClose = () => {
    setMobileMoreAnchorEl(null);
  };

  const handleMenuClose = () => {
    setAnchorEl(null);
    handleMobileMenuClose();
  };

  const handleMobileMenuOpen = (event) => {
    setMobileMoreAnchorEl(event.currentTarget);
  };

  const Logout = () => {
    sessionStorage.removeItem('bs_cus');
    window.location.reload();
  }

  useEffect(() => {
    let mount = true

    if (mount) {
      AppbarOperation();
    }

    return () => {
      mount = false
    }
  }, [])

  const menuId = 'primary-search-account-menu';
  const renderMenu = (
    <Menu
      anchorEl={anchorEl}
      anchorOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      id={menuId}
      keepMounted
      transformOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      open={isMenuOpen}
      onClose={handleMenuClose}
    >
      <MenuItem onClick={() => {navigate("/myaccount")}}>My account</MenuItem>
      <MenuItem onClick={() => {Logout()}}>Logout</MenuItem>
    </Menu>
  );

  const mobileMenuId = 'primary-search-account-menu-mobile';
  const renderMobileMenu = (
    <Menu
      anchorEl={mobileMoreAnchorEl}
      anchorOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      id={mobileMenuId}
      keepMounted
      transformOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      open={isMobileMenuOpen}
      onClose={handleMobileMenuClose}
    >
      {
        customer ? (
                <div>
                  <MenuItem>
                    <p>Cart</p>
                    <CartBadge customer={customer} />
                  </MenuItem>
                  <MenuItem onClick={() => {navigate("/myaccount")}}>
                    <p>My Account</p>
                  </MenuItem>
                  <MenuItem onClick={() => {Logout()}}>
                    <p>Logout</p>
                  </MenuItem>
                </div>
              ) : (
                <div>
                  <MenuItem onClick={() => {navigate("/login")}}>
                    <p>Login</p>
                  </MenuItem>
                  <MenuItem onClick={() => navigate("/signup")}>
                    <p>Sign up</p>
                  </MenuItem>
                </div>
              )
      }
    </Menu>
  );

  return (
    <Box sx={{ flexGrow: 1 }} >
      <AppBar position="fixed" id="AppBar" className={styles.bs_apb} style={{backgroundColor:'rgba(255, 255, 255, 0)', boxShadow: 'none'}} >
        <Toolbar className={styles.bs_apb_tb}>
          <img
            src={bslogo}
            alt='logo' width="150px" height="40px"
            className={styles.bs_apb_logo}
            onClick={() => { navigate('/') }}
          />

          <Box sx={{ flexGrow: 1 }} />
          <Box sx={{ display: { xs: 'none', md: 'flex' } }}>
            {
              customer ? (
                <>
                  <CartBadge customer={customer} />
                  <IconButton
                    size="large"
                    edge="end"
                    aria-label="account of current user"
                    aria-controls={menuId}
                    aria-haspopup="true"
                    onClick={handleProfileMenuOpen}
                    color="inherit"
                  >
                    <AccountCircle />
                  </IconButton>
                </>
              ) : (
                <ButtonGroup variant="text" aria-label="text button group">
                  <Link to={'/login'} className={styles.bs_apb_btn}>
                    <Button>Login</Button>
                  </Link>
                  <Link to={'/signup'} className={styles.bs_apb_btn}>
                    <Button>Signup</Button>
                  </Link>
                </ButtonGroup>
              )
            }

          </Box>
          <Box sx={{ display: { xs: 'flex', md: 'none' } }}>
            <IconButton
              size="large"
              aria-label="show more"
              aria-controls={mobileMenuId}
              aria-haspopup="true"
              onClick={handleMobileMenuOpen}
              color="inherit"
            >
              <MoreIcon />
            </IconButton>
          </Box>
        </Toolbar>
      </AppBar>
      {renderMobileMenu}
      {renderMenu}
    </Box>
  );
}