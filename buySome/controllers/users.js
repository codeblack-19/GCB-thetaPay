const Users = require('../models/sys_users.js');
const Sequelize = require('sequelize');
const {
    logError
} = require('../services/ErrorHandler.js');
const {
    validationResult
} = require('express-validator');
const bcrypt = require('bcrypt');


// get all users
exports.getall = () => {
    return async (req, res) => {
        try {
            const users = await Users.findAll({
                attributes: {
                    exclude: ['password']
                }
            })

            return res.json([...users]);
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// create new user
exports.createNew = () => {
    return async (req, res) => {
        const {
            full_name,
            phone_number,
            password,
            username,
            role,
        } = req.body;

        try {
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                return res.status(400).json({
                    error: errors.array()[0].msg
                })
            }

            const hashedPassword = bcrypt.hashSync(password, 10);

            await Users.findOrCreate({
                where: {
                    username
                },
                defaults: {
                    full_name,
                    phone_number,
                    password: hashedPassword,
                    username,
                    role,
                }
            }).then(async (user_inst) => {
                if (!user_inst[1]) {
                    return res.status(400).json({
                        error: `${username} already exist`
                    })
                } else {
                    return res.json({
                        success: "User added successfully"
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

// delete user
exports.deleteUser = () => {
    return async (req, res) => {
        const {
            uid
        } = req.params;

        try {
            const user = await Users.findOne({
                where: {
                    id: uid
                },
                paranoid: true
            })

            if (!user) {
                return res.status(400).json({
                    error: "User does not exist"
                })
            }

            await user.destroy()
                .then(async (data) => {
                    return res.json({
                        message: 'User deleted successfully'
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

// edit user info
exports.editUser = () => {
    return async (req, res) => {
        const {
            uid
        } = req.params;
        const {
            full_name,
            phone_number,
            password,
            username,
            role,
        } = req.body;

        try {
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                return res.status(400).json({
                    error: errors.array()[0].msg
                })
            }

            var user = await Users.findOne({
                where: {
                    id: uid
                }
            });

            if (!user) {
                return res.status(422).json({
                    error: `The user with id ${uid} does not exist`
                })
            }

            user.set('full_name', full_name ? full_name : user.get('full_name'));
            user.set('phone_number', phone_number ? phone_number : user.get('phone_number'));
            user.set('username', username ? username : user.username);
            user.set('password', password ? bcrypt.hashSync(password, 10) : user.password);
            user.set('role', role ? role : user.role);

            await user.save()

            return res.json({
                message: 'User Updated successfully'
            })

        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// get delete users
exports.deletedUsers = () => {
    return async (req, res) => {
        try {
            const users = await Users.findAll({
                where: {
                    deletedAt: {
                        [Sequelize.Op.ne]: null
                    }
                },
                paranoid: false
            })

            return res.json([...users]);
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// restore deleted user
exports.restore = () => {
    return async (req, res) => {
        const {
            uid
        } = req.params;

        try {
            const user = await Users.findOne({
                where: {
                    id: uid
                },
                paranoid: false
            })

            if (!user) {
                return res.status(400).json({
                    error: "User does not exist"
                })
            }

            await user.restore()
                .then(async (data) => {
                    return res.json({
                        message: 'User restored successfully'
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