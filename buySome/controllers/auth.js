const {
    logError
} = require("../services/ErrorHandler");
const bcrypt = require('bcrypt');
const { getToken } = require("../services/Jwt_Auth.js");
const { omit } = require("lodash")
const SysUsers = require('../models/sys_users.js');
const Customers = require('../models/customers.js');
const { validationResult } = require('express-validator');

// admin login
exports.AdminLogin = () => {
    return async (req, res) => {
        const {
            username,
            password
        } = req.body;

        try {
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                return res.status(400).json({
                    error: errors.array()[0].msg
                })
            }

            const user = await SysUsers.findOne({
                where: {
                    username
                }
            });

            if (!user) {
                return res.status(400).json({
                    error: "Invalid username"
                })
            }

            var match = bcrypt.compareSync(password, user.password);

            if (!match) {
                return res.status(400).json({
                    error: "Invalid Password"
                })
            }

            var token = await getToken(user, 'admin');

            return res.json({
                access_token: token,
                user: omit(user.toJSON(), "password")
            })

        } catch (e) {
            logError(e);
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// customer login
exports.CusLogin = () => {
    return async (req, res) => {
        const {
            username,
            password
        } = req.body;

        try {
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                return res.status(400).json({
                    error: errors.array()[0].msg
                })
            }

            const user = await Customers.findOne({
                where: {
                    username
                }
            });

            if (!user) {
                return res.status(400).json({
                    error: "Invalid username"
                })
            }

            var match = bcrypt.compareSync(password, user.password);

            if (!match) {
                return res.status(400).json({
                    error: "Invalid Password"
                })
            }

            var token = await getToken(user, 'customer');

            return res.json({
                access_token: token,
                user: omit(user.toJSON(), "password")
            })

        } catch (e) {
            logError(e);
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// customer signUp
exports.SignUp = () => {
    return async (req, res) => {
        const {
            address,
            phone_number,
            password,
            username,
        } = req.body;

        try {
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                return res.status(400).json({
                    error: errors.array()[0].msg
                })
            }

            const hashedPassword = bcrypt.hashSync(password, 10);

            await Customers.findOrCreate({
                where: {
                    username
                },
                defaults: {
                    address,
                    phone_number,
                    password: hashedPassword,
                    username,
                }
            }).then(async (user_inst) => {
                const token = await getToken(user_inst[0], 'customer');

                if (!user_inst[1]) {
                    return res.status(400).json({
                        error: `${username} already exist`
                    })
                } else {
                    return res.json({
                        access_token: token,
                        user: omit(user_inst[0].toJSON(), "password")
                    })
                }
            }).catch((e) => {
                logError(e)
                return res.status(500).json({
                    error: e.message
                })
            })
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }

    }
}

// logout
