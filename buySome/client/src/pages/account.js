import React, {useState} from 'react'
import { Helmet } from 'react-helmet'
import { useNavigate } from 'react-router-dom';
import styles from '../styles/account.module.css';
import PropTypes from 'prop-types';
import Tabs from '@mui/material/Tabs';
import Tab from '@mui/material/Tab';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import { Container, AppBar, Toolbar, IconButton } from '@mui/material';
import bslogo from '../asserts/buysome1.png';
import { Logout } from '@mui/icons-material';
import Userorders from '../components/Accounts/Userorders';

function TabPanel(props) {
  const { children, value, index, ...other } = props;

  return (
    <div
      role="tabpanel"
      hidden={value !== index}
      id={`simple-tabpanel-${index}`}
      aria-labelledby={`simple-tab-${index}`}
      {...other}
    >
      {value === index && (
        <Box sx={{ p: 3 }}>
          <Typography>{children}</Typography>
        </Box>
      )}
    </div>
  );
}

TabPanel.propTypes = {
  children: PropTypes.node,
  index: PropTypes.number.isRequired,
  value: PropTypes.number.isRequired,
};

function a11yProps(index) {
  return {
    id: `simple-tab-${index}`,
    'aria-controls': `simple-tabpanel-${index}`,
  };
}


export default function Account() {
    const [value, setValue] = useState(0);
    const navigate = useNavigate();

    const handleChange = (event, newValue) => {
        setValue(newValue);
    };

    const LogoutUser = () => {
      sessionStorage.removeItem('bs_cus');
      window.location.href = '/';
    }

  return (
    <Container className={styles.holder}>
        <Helmet>
            <title>buysome eCommerce</title>
            <meta name="description" content="find and buy all your products on one ecommerce site" />
            <link rel="icon" href="/favicon.ico" />
        </Helmet>

        {/* header bar */}
            <AppBar position="fixed" className={styles.bs_apb}>
              <Toolbar>
                <img
                  src={bslogo}
                  alt='logo' width="150px" height="40px"
                  className={styles.bs_apb_logo}
                  onClick={() => { navigate('/') }}
                />
                <Box sx={{ flexGrow: 1 }} />
                <IconButton
                  size="large"
                  aria-label="show more"
                  aria-haspopup="true"
                  onClick={() => LogoutUser()}
                  color="inherit"
                >
                  <Logout />
                </IconButton>
              </Toolbar>
            </AppBar>
            <Toolbar />

            <Container className={styles.content}>
                <h3 className={styles.acct_header}>User Account</h3>

                <Box sx={{ width: '100%' }}>
                    <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                        <Tabs value={value} onChange={handleChange} textColor="secondary" indicatorColor="secondary" aria-label="basic tabs example" centered>
                            <Tab label="All Orders" {...a11yProps(0)} />
                            <Tab label="Profile" {...a11yProps(1)} />
                        </Tabs>
                    </Box>
                    <TabPanel value={value} index={0}>
                        <Userorders />
                    </TabPanel>
                    <TabPanel value={value} index={1}>
                        Item Two
                    </TabPanel>
                </Box>
            </Container>  

    </Container>
  )
}
