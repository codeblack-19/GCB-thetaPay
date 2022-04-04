require('dotenv').config();
const jwt = require('jsonwebtoken');

exports.customer_mw = () => {
    return (req, res, next) => {
        if (req.headers.authorization) {
            let token = req.headers.authorization.split(' ')[1];
            jwt.verify(token, process.env.JWT_Auth_key_1, function (err, decode) {
                if (err) {
                    return res.status(401).json({
                        "error": err.message
                    });
                }

                res.locals.customerId = decode.uid;
                next()
            })

        } else {
            return res.status(401).json({
                "error": "Unauthorized"
            });
        }
    }
}

exports.sysUser_mw = (role) => {
    return (req, res, next) => {
        if (req.headers.authorization) {
            let token = req.headers.authorization.split(' ')[1];
            jwt.verify(token, process.env.JWT_Auth_key_2, function (err, decode) {
                if (err) {
                    return res.status(401).json({
                        "error": err.message
                    });
                }

                res.locals.sysUserId = decode.uid;

                if (role == 'super_admin') {
                    if (decode.role == 'admin' || decode.role == 'admin_sub') {
                        return res.status(401).json({
                            "error": "You are not authorized for this action"
                        });
                    } else {
                        next()
                    }
                } else if (role == 'admin') {
                    if (decode.role != 'admin' && decode.role != 'super_admin') {
                        return res.status(401).json({
                            "error": "You are not authorized for this action"
                        });
                    } else {
                        next();
                    }
                } else if (role == 'admin_sub') {
                    next();
                }
            })

        } else {
            return res.status(401).json({
                "error": "Unauthorized"
            });
        }
    }
}

exports.paymenthook = () => {
    return (req, res, next) => {
        if (req.headers.authorization) {
            let token = req.headers.authorization.split(' ')[1];
            
            if(process.env.ThetaPay_GATEWAY_PUBLIC_KEY != token){
                return res.status(401).json({
                    "error": "Invalid public key"
                });
            }else{
                next()
            }
        } else {
            return res.status(401).json({
                "error": "Unauthorized"
            });
        }
    }
}