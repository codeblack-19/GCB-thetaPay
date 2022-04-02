const Products = require('../models/products.js');
const { validationResult } = require('express-validator');
const { logError } = require("../services/ErrorHandler");
const Sequelize = require('sequelize');
const Carts = require('../models/carts.js');

//get all products
exports.getAll = () => {
    return async (req, res) => {
        try {
            const prods = await Products.findAll({
                include: [
                    {model: Carts}
                ]
            });

            return res.json([...prods])
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// new product
exports.newprod = () => {
    return async (req, res) => {
        const {
            name,
            price,
            quantity,
            description,
            image_url
        } = req.body

        try {
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                return res.status(400).json({
                    error: errors.array()[0].msg
                })
            }

            await Products.create({
                name,
                price,
                quantity,
                description,
                image_url
            }).then(async () => {
                
                return res.json({
                    success: "Product added successfully"
                })
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

// edit product
exports.editprod = () => {
    return async (req, res) => {
        const {
            product
        } = req.params;

        const {
            name,
            price,
            quantity,
            description
        } = req.body;

        try {
            await Products.update({
                name,
                price,
                quantity,
                description
            }, {
                where: {
                    id: product
                }
            }).then(async () => {
                
                return res.json({
                    message: "Product updated successfully"
                })
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

// delete product
exports.deleteProd = () => {
    return async (req, res) => {
        const {
            product
        } = req.params;

        try {
            const prod = await Products.findOne({
                where: {
                    id: product
                },
                paranoid: true
            })

            if (!prod) {
                return res.status(400).json({
                    error: "Product does not exist"
                })
            }

            await prod.destroy()
                .then(async (data) => {
                    return res.json({
                        success: "Product deleted successfully"
                    })
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

// get all deleted products
exports.deletedProds = () => {
     return async (req, res) => {
        try {
            const prods = await Products.findAll({
                where: {
                    deletedAt: {
                        [Sequelize.Op.ne]: null
                    }
                },
                paranoid: false
            });

            return res.json([...prods])
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// restore product
exports.restore = () => {
    return async (req, res) => {
        const {
            product
        } = req.params;

        try {
            const prod = await Products.findOne({
                where: {
                    id: product
                },
                paranoid: false
            })

            if (!prod) {
                return res.status(400).json({
                    error: "Product does not exist"
                })
            }

            await prod.restore()
                .then(async (data) => {
                    
                    return res.json({
                        success: "Product Restored successfully"
                    })
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