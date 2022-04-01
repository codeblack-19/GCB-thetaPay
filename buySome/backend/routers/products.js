const express = require('express');
const router = express.Router();
const productController = require('../controllers/products.js');
const esValidator = require('../helper/validator.js');
const middleware = require('../helper/middlewares.js');

// get all products
router.get('/', productController.getAll());

// new product
router.post('/', middleware.sysUser_mw('admin'), esValidator.validateBody('addproduct'), productController.newprod());

// update product 
router.put('/:product', middleware.sysUser_mw('admin'), productController.editprod());

// delete product
router.delete('/:product', middleware.sysUser_mw('admin'), productController.deleteProd());

// get deleted
router.get('/deleted', middleware.sysUser_mw('admin'), productController.deletedProds());

// restore product
router.put('/:product/restore', middleware.sysUser_mw('admin'), productController.restore())

module.exports = router;