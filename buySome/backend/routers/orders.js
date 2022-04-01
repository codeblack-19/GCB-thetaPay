const express = require('express');
const router = express.Router();
const middleware = require('../helper/middlewares.js');
const esValidator = require('../helper/validator.js');
const orderController = require('../controllers/orders.js');

// get all orders
router.get('/', middleware.sysUser_mw('admin'), orderController.getAllorders());

// get customer orders
router.get('/:customer_id', middleware.customer_mw(), orderController.getCusOrders());

// make order or checkout order
router.post('/checkout', middleware.customer_mw(), esValidator.validateCusBody('checkout'), orderController.checkout())

// edit order
router.put('/:order_id', middleware.sysUser_mw('admin'), esValidator.validateCusBody('editOrderStatus'), orderController.editOrder());

// revert order
router.post('/:order_id/revert', middleware.customer_mw(), orderController.revertOrder());

// delete order

module.exports = router;