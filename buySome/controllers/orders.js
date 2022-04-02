const Orders = require('../models/orders');
const OrderDetails = require('../models/order_details');
const {
    logError
} = require("../services/ErrorHandler");
const {
    validationResult
} = require('express-validator');
const Carts = require('../models/carts');
const Products = require('../models/products');

// relationship btn orders and order_details
Orders.hasMany(OrderDetails, {
    foreignKey: 'order_id'
});
OrderDetails.belongsTo(Orders, {
    foreignKey: 'order_id'
});
Products.hasMany(OrderDetails, {
    foreignKey: 'product_id'
});
OrderDetails.belongsTo(Products, {
    foreignKey: 'product_id'
});

// get all orders
exports.getAllorders = () => {
    return async (req, res) => {
        try {
            const orders = await Orders.findAll({
                include: [{
                    model: OrderDetails,
                    include: [{
                        model: Products
                    }]
                }]
            })

            return res.json([...orders]);
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// get customer orders
exports.getCusOrders = () => {
    return async (req, res) => {
        const {
            customer_id
        } = req.params;

        try {
            const orders = await Orders.findAll({
                where: {
                    customer_id
                },
                include: [{
                    model: OrderDetails,
                    include: [{
                        model: Products
                    }]
                }]
            })

            return res.json([...orders]);
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// get all orders
exports.checkout = () => {
    return async (req, res) => {
        const {
            order_email,
            billing_info,
            shipping_address,
            order_details
        } = req.body;

        try {
            const order = await Orders.create({
                status: 'pending',
                order_email,
                billing_info,
                shipping_address,
                customer_id: res.locals.customerId
            })

            for (let i = 0; i < order_details.length; i++) {
                let cart = await Carts.findByPk(order_details[i]);

                if (!cart) {
                    order.destroy();
                    return res.status(400).json({
                        error: `Cart with id ${order_details[i]} does not exist`
                    })
                }

                let ods = await OrderDetails.create({
                    product_id: cart.product_id,
                    quantity: cart.quantity,
                    order_id: order.id
                })

                await cart.destroy();
            }

            order.set('status', 'success');
            order.save();

            return res.json({
                message: 'Checkout completed wait for delivery'
            })
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// edit order
exports.editOrder = () => {
    return async (req, res) => {
        const {order_id} = req.params;
        const {status} = req.body;

        try {
            console.log(order_id, status);

            const order = await Orders.findByPk(order_id);

            if (!order) {
                return res.status(400).json({
                    error: `Order with id ${order_id} does not exist`
                })
            }

            order.set('status', status);
            order.save();

            return res.json({
                message : 'Order status changed successfully'
            })

        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// revert order
exports.revertOrder = () => {
    return async (req, res) => {
        const { order_id } = req.params;

        try {
            const order = await Orders.findByPk(order_id);

            if(!order){
                return res.status(400).json({
                    error: `Order with id ${order_id} does not exist`
                })
            }

            if(order.status == 'reverted'){
                return res.status(400).json({
                    error: `Order has been reverted already`
                })
            }

            // make refund api call

            const order_details = await OrderDetails.findAll({
                where: {
                    order_id: order_id
                }
            })

            order_details.forEach(async (ords) => {
                const prod = await Products.findByPk(ords.product_id);
                let newqty = prod.quantity + ords.quantity;
                prod.set('quantity', newqty);
                prod.save();
            })

            order.set('status', 'reverted');
            order.save();

            return res.json({
                message : "Your order has been reverted successfully and Order payment has been refunded"
            });
        } catch (e) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}
