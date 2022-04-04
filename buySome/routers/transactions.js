const express = require('express');
const router = express.Router();
const txnController = require('../controllers/transactions');
const middleware = require('../helper/middlewares.js');

// webhook for webpayment
router.post('/webpaymenthook', middleware.paymenthook(), txnController.webpaymenthook());

// get transaction by Id
router.get('/:id', middleware.customer_mw(), txnController.txnById());

module.exports = router;