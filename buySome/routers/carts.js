const express = require('express');
const router = express.Router();
const middleware = require('../helper/middlewares.js');
const cartController = require('../controllers/carts.js');
const esValidator = require('../helper/validator.js');

// get all customer carts
router.get('/:customer_id', middleware.customer_mw(), cartController.getCusCarts());

// get customer cart count
router.get('/:customer_id/count', middleware.customer_mw(), cartController.getCartCount())

// add product to cart
router.post('/', middleware.customer_mw(), esValidator.validateCusBody('addToCart'), cartController.addProdCarts());

// update quantity of product
router.put('/:cart_id', middleware.customer_mw(), esValidator.validateCusBody('addToCart'), cartController.editQuantity());

// delete product from cart
router.delete('/:cart_id', middleware.customer_mw(), cartController.deleteCart());


module.exports = router;