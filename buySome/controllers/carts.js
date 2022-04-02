const Carts = require('../models/carts.js');
const Customers = require('../models/customers.js');
const Products = require('../models/products.js');
const {
    logError
} = require("../services/ErrorHandler");
const {
    validationResult
} = require('express-validator');

// relations between carts, products and customers
Products.hasMany(Carts, {foreignKey: 'product_id'});
Carts.belongsTo(Products, {foreignKey: 'product_id'});
Customers.hasMany(Carts, {foreignKey: 'customer_id'});
Carts.belongsTo(Customers, {foreignKey: 'customer_id'});

// get all customer carts
exports.getCusCarts = () => {
    return async (req, res) => {
        const {
            customer_id
        } = req.params;

        try {
            const prods = await Carts.findAll({
                where: {
                    customer_id
                }, include : [
                    {model: Products}
                ]
            })

            return res.json([...prods]);
            
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// get customer cart count
exports.getCartCount = () => {
    return async (req, res) => {
        const {
            customer_id
        } = req.params;

        try {
            const totalCount = await Carts.count({
                where: {
                    customer_id
                }
            })

            return res.json({
                count : totalCount
            });

        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// add product to cart
exports.addProdCarts = () => {
    return async (req, res) => {
        const {
            product_id,
            quantity
        } = req.body;

        try {
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                return res.status(400).json({
                    error: errors.array()[0].msg
                })
            }

            const prod = await Products.findByPk(product_id);

            if(!prod){
                return res.status(400).json({
                    error: `Product with Id ${product_id} does not exist`
                })
            }

            if(prod.quantity < quantity){
                return res.status(400).json({
                    error: `Only ${prod.quantity} remaining`
                })
            }

            const cart = await Carts.findOne({
                where: {
                    product_id,
                    customer_id: res.locals.customerId
                }
            });

            if(cart){
                return res.status(400).json({
                    error: `${prod.name} already in cart`
                })
            }

            await Carts.create({
                quantity,
                product_id,
                customer_id: res.locals.customerId
            })
        
            let rmQty = prod.quantity - quantity;

            prod.set('quantity', rmQty);
            prod.save()

            return res.json({
                message: `Product added to cart successfully`
            })

        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// edit cart quantiy
exports.editQuantity = () => {
    return async (req, res) => {
        const {
            product_id,
            quantity
        } = req.body;

        const {cart_id} = req.params;

        try {
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                return res.status(400).json({
                    error: errors.array()[0].msg
                })
            }

            const prod = await Products.findByPk(product_id);

            if (!prod) {
                return res.status(400).json({
                    error: `Product with Id ${product_id} does not exist`
                })
            }

            if (prod.quantity < quantity) {
                return res.status(400).json({
                    error: `Only ${prod.quantity} remaining`
                })
            }

            const cart = await Carts.findByPk(cart_id);
            if (!cart) {
                return res.status(400).json({
                    error: `Cart with id ${cart_id} does not exist`
                })
            }

            if(quantity < cart.quantity){
                let pQty = cart.quantity - quantity;
                prod.quantity = prod.quantity + pQty;
            }else{
                let pQty = quantity - cart.quantity;
                prod.quantity = prod.quantity - pQty;
            }
        
            cart.set('quantity', quantity);
            cart.save();
            prod.save();

            return res.json({
                message: `Cart quantity updated`
            })

        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// delete cart
exports.deleteCart = () => {
    return async (req, res) => {
        const {cart_id} = req.params;

        try {
            const cart = await Carts.findByPk(cart_id);
            if (!cart) {
                return res.status(400).json({
                    error: `Cart with id ${cart_id} does not exist`
                })
            }

            await cart.destroy();

            const prod = await Products.findByPk(cart.product_id, {
                paranoid : false
            });

            let rmQty = prod.quantity + cart.quantity;

            prod.set('quantity', rmQty);
            prod.save()

            return res.json({
                message: `Cart deleted`
            })
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}