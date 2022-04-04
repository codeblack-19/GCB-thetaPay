require('dotenv').config();
const express = require('express');
const app = express();
const cors = require('cors');
const path = require('path');

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({extended: true}));

// view config
app.use(express.static(path.resolve(__dirname, './client/build')));

//middleware for response headers
app.use(function (req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    next();
});

// routes
const auth = require('./routers/auth.js');
const products = require('./routers/products.js');
const sysUsers = require('./routers/users.js');
const orders = require('./routers/orders.js');
const carts = require('./routers/carts.js');
const transactions = require('./routers/transactions');

app.use(`${process.env.API_Version}/auth`, auth);
app.use(`${process.env.API_Version}/products`, products);
app.use(`${process.env.API_Version}/sysUsers`, sysUsers);
app.use(`${process.env.API_Version}/carts`, carts);
app.use(`${process.env.API_Version}/orders`, orders);
app.use(`${process.env.API_Version}/transactions`, transactions);

//base url
app.get(`${process.env.API_Version}/`, function (req, res) {
    res.send("<h1>Backend is running</h1>");
})

app.get("*", (req, res) => {
    res.sendFile(path.resolve(__dirname, './client/build', 'index.html'));
})

//db connections
require("./db/connection.js");

//starting app in port 3006
app.listen(3006, function () {
    console.log("App is running on port 3006");
})