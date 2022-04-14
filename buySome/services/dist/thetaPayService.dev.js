"use strict";

require('dotenv').config();

var _require = require('axios'),
    axios = _require["default"];

var refundTxn = function refundTxn(txn_id) {
  var refund;
  return regeneratorRuntime.async(function refundTxn$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          _context.next = 2;
          return regeneratorRuntime.awrap(axios({
            method: 'POST',
            url: "".concat(process.env.GCB_THETAPAY_API, "/transactions/webpayment_refund"),
            data: {
              txn_id: txn_id
            },
            headers: {
              "Authorization": "Bearer ".concat(process.env.ThetaPay_GATEWAY_PRIVATE_KEY)
            }
          }).then(function (res) {
            return true;
          })["catch"](function (e) {
            if (e.response.data) {
              return e.response.data.error;
            } else {
              return false;
            }
          }));

        case 2:
          refund = _context.sent;
          return _context.abrupt("return", refund);

        case 4:
        case "end":
          return _context.stop();
      }
    }
  });
};

module.exports = {
  refundTxn: refundTxn
};