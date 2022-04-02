const express = require('express');
const router = express.Router();
const esValidator = require('../helper/validator.js');
const authController = require('../controllers/auth.js');

// login for sys_users
router.post('/login_ad', esValidator.validateBody('login'), authController.AdminLogin());

// login for customer
router.post('/login_ct', esValidator.validateBody('login'), authController.CusLogin())

// signup
router.post('/signUp', esValidator.validateCusBody('signUp'), authController.SignUp())


module.exports = router;