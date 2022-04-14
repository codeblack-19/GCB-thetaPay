"use strict";

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance"); }

function _iterableToArray(iter) { if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } }

var Orders = require('../models/orders');

var OrderDetails = require('../models/order_details');

var _require = require("../services/ErrorHandler"),
    logError = _require.logError;

var _require2 = require('express-validator'),
    validationResult = _require2.validationResult;

var Carts = require('../models/carts');

var Products = require('../models/products');

var _require3 = require('../services/thetaPayService'),
    refundTxn = _require3.refundTxn; // relationship btn orders and order_details


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
}); // get all orders

exports.getAllorders = function () {
  return function _callee(req, res) {
    var orders;
    return regeneratorRuntime.async(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            _context.prev = 0;
            _context.next = 3;
            return regeneratorRuntime.awrap(Orders.findAll({
              include: [{
                model: OrderDetails,
                include: [{
                  model: Products
                }]
              }]
            }));

          case 3:
            orders = _context.sent;
            return _context.abrupt("return", res.json(_toConsumableArray(orders)));

          case 7:
            _context.prev = 7;
            _context.t0 = _context["catch"](0);
            logError(_context.t0);
            return _context.abrupt("return", res.status(500).json({
              error: _context.t0.message
            }));

          case 11:
          case "end":
            return _context.stop();
        }
      }
    }, null, null, [[0, 7]]);
  };
}; // get customer orders


exports.getCusOrders = function () {
  return function _callee2(req, res) {
    var customer_id, orders;
    return regeneratorRuntime.async(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            customer_id = req.params.customer_id;
            _context2.prev = 1;
            _context2.next = 4;
            return regeneratorRuntime.awrap(Orders.findAll({
              where: {
                customer_id: customer_id
              },
              include: [{
                model: OrderDetails,
                include: [{
                  model: Products
                }]
              }]
            }));

          case 4:
            orders = _context2.sent;
            return _context2.abrupt("return", res.json(_toConsumableArray(orders)));

          case 8:
            _context2.prev = 8;
            _context2.t0 = _context2["catch"](1);
            logError(_context2.t0);
            return _context2.abrupt("return", res.status(500).json({
              error: _context2.t0.message
            }));

          case 12:
          case "end":
            return _context2.stop();
        }
      }
    }, null, null, [[1, 8]]);
  };
}; // get all orders


exports.checkout = function () {
  return function _callee3(req, res) {
    var _req$body, order_email, billing_info, shipping_address, order_details, txn_id, errors, order, i, cart, ods;

    return regeneratorRuntime.async(function _callee3$(_context3) {
      while (1) {
        switch (_context3.prev = _context3.next) {
          case 0:
            _req$body = req.body, order_email = _req$body.order_email, billing_info = _req$body.billing_info, shipping_address = _req$body.shipping_address, order_details = _req$body.order_details, txn_id = _req$body.txn_id;
            _context3.prev = 1;
            errors = validationResult(req);

            if (errors.isEmpty()) {
              _context3.next = 5;
              break;
            }

            return _context3.abrupt("return", res.status(400).json({
              error: errors.array()[0].msg
            }));

          case 5:
            _context3.next = 7;
            return regeneratorRuntime.awrap(Orders.create({
              status: 'pending',
              order_email: order_email,
              billing_info: billing_info,
              shipping_address: shipping_address,
              txn_id: txn_id,
              customer_id: res.locals.customerId
            }));

          case 7:
            order = _context3.sent;
            i = 0;

          case 9:
            if (!(i < order_details.length)) {
              _context3.next = 24;
              break;
            }

            _context3.next = 12;
            return regeneratorRuntime.awrap(Carts.findByPk(order_details[i]));

          case 12:
            cart = _context3.sent;

            if (cart) {
              _context3.next = 16;
              break;
            }

            order.destroy();
            return _context3.abrupt("return", res.status(400).json({
              error: "Cart with id ".concat(order_details[i], " does not exist")
            }));

          case 16:
            _context3.next = 18;
            return regeneratorRuntime.awrap(OrderDetails.create({
              product_id: cart.product_id,
              quantity: cart.quantity,
              order_id: order.id
            }));

          case 18:
            ods = _context3.sent;
            _context3.next = 21;
            return regeneratorRuntime.awrap(cart.destroy());

          case 21:
            i++;
            _context3.next = 9;
            break;

          case 24:
            order.set('status', 'success');
            order.save();
            return _context3.abrupt("return", res.json({
              message: 'Checkout completed wait for delivery'
            }));

          case 29:
            _context3.prev = 29;
            _context3.t0 = _context3["catch"](1);
            logError(_context3.t0);
            return _context3.abrupt("return", res.status(500).json({
              error: _context3.t0.message
            }));

          case 33:
          case "end":
            return _context3.stop();
        }
      }
    }, null, null, [[1, 29]]);
  };
}; // edit order


exports.editOrder = function () {
  return function _callee4(req, res) {
    var order_id, status, order;
    return regeneratorRuntime.async(function _callee4$(_context4) {
      while (1) {
        switch (_context4.prev = _context4.next) {
          case 0:
            order_id = req.params.order_id;
            status = req.body.status;
            _context4.prev = 2;
            _context4.next = 5;
            return regeneratorRuntime.awrap(Orders.findByPk(order_id));

          case 5:
            order = _context4.sent;

            if (order) {
              _context4.next = 8;
              break;
            }

            return _context4.abrupt("return", res.status(400).json({
              error: "Order with id ".concat(order_id, " does not exist")
            }));

          case 8:
            order.set('status', status);
            order.save();
            return _context4.abrupt("return", res.json({
              message: 'Order status changed successfully'
            }));

          case 13:
            _context4.prev = 13;
            _context4.t0 = _context4["catch"](2);
            logError(_context4.t0);
            return _context4.abrupt("return", res.status(500).json({
              error: _context4.t0.message
            }));

          case 17:
          case "end":
            return _context4.stop();
        }
      }
    }, null, null, [[2, 13]]);
  };
}; // revert order


exports.revertOrder = function () {
  return function _callee6(req, res) {
    var order_id, order, refund, order_details;
    return regeneratorRuntime.async(function _callee6$(_context6) {
      while (1) {
        switch (_context6.prev = _context6.next) {
          case 0:
            order_id = req.params.order_id;
            _context6.prev = 1;
            _context6.next = 4;
            return regeneratorRuntime.awrap(Orders.findByPk(order_id));

          case 4:
            order = _context6.sent;

            if (order) {
              _context6.next = 7;
              break;
            }

            return _context6.abrupt("return", res.status(400).json({
              error: "Order with id ".concat(order_id, " does not exist")
            }));

          case 7:
            if (!(order.status == 'reverted')) {
              _context6.next = 9;
              break;
            }

            return _context6.abrupt("return", res.status(400).json({
              error: "Order has been reverted already"
            }));

          case 9:
            _context6.next = 11;
            return regeneratorRuntime.awrap(refundTxn(order.txn_id));

          case 11:
            refund = _context6.sent;

            if (!(refund != true)) {
              _context6.next = 18;
              break;
            }

            if (!(refund === false)) {
              _context6.next = 17;
              break;
            }

            return _context6.abrupt("return", res.status(400).json({
              error: "Order could not be refunded, please call service care"
            }));

          case 17:
            return _context6.abrupt("return", res.status(400).json({
              error: refund
            }));

          case 18:
            _context6.next = 20;
            return regeneratorRuntime.awrap(OrderDetails.findAll({
              where: {
                order_id: order_id
              }
            }));

          case 20:
            order_details = _context6.sent;
            order_details.forEach(function _callee5(ords) {
              var prod, newqty;
              return regeneratorRuntime.async(function _callee5$(_context5) {
                while (1) {
                  switch (_context5.prev = _context5.next) {
                    case 0:
                      _context5.next = 2;
                      return regeneratorRuntime.awrap(Products.findByPk(ords.product_id));

                    case 2:
                      prod = _context5.sent;
                      newqty = prod.quantity + ords.quantity;
                      prod.set('quantity', newqty);
                      prod.save();

                    case 6:
                    case "end":
                      return _context5.stop();
                  }
                }
              });
            });
            order.set('status', 'reverted');
            order.save();
            return _context6.abrupt("return", res.json({
              message: "Your order has been reverted successfully and Order payment has been refunded"
            }));

          case 27:
            _context6.prev = 27;
            _context6.t0 = _context6["catch"](1);
            logError(_context6.t0);
            return _context6.abrupt("return", res.status(500).json({
              error: _context6.t0.message
            }));

          case 31:
          case "end":
            return _context6.stop();
        }
      }
    }, null, null, [[1, 27]]);
  };
};