const express = require('express');
const router = express.Router();
const esValidator = require('../helper/validator.js');
const middleware = require('../helper/middlewares.js');
const sysUserController = require('../controllers/users.js');

// get all users
router.get('/', middleware.sysUser_mw('super_admin'), sysUserController.getall());

// create user
router.post('/', middleware.sysUser_mw('super_admin'), esValidator.validateBody('createUser'), sysUserController.createNew());

// delete a user temp.
router.delete('/:uid', middleware.sysUser_mw('super_admin'), sysUserController.deleteUser());

// edit user info
router.put('/:uid', middleware.sysUser_mw('super_admin'), esValidator.validateBody('editUser'), sysUserController.editUser());

// get deleted users
router.get('/deleted', middleware.sysUser_mw('super_admin'), sysUserController.deletedUsers());

// restore deleted user
router.put('/:uid/restore', middleware.sysUser_mw('super_admin'), sysUserController.restore());


module.exports = router;