const {
    body
} = require('express-validator');

exports.validateBody = (method) => {
    switch (method) {
        case 'login': {
            return [
                body('username', 'Username is required').exists(),
                body('password', 'Password is required').exists()
            ]
        }case 'addproduct' : {
            return[
                body('name').isString().isLength({max: 255, min: 2 })
                    .exists().withMessage('Product name is required'),
                body('price').isFloat({min: 0.00})
                    .exists().withMessage('Product price is required'),
                body('quantity').isInt({min: 0})
                    .exists().withMessage('Product quantity is required'),
                body('description').isString().isLength({max: 255, min: 2 })
                    .exists().withMessage('Product description is required'),
                body('image_url').exists().withMessage('Product image is required')
                    .isString().isLength({max: 255})
            ]
        }
        case 'createUser': {
            return [
                body('full_name').isString().isLength({ max: 255})
                    .exists().withMessage('Full name is required'),
                body('phone_number').isString().isLength({max: 255})
                    .exists().withMessage('Phone number is required'),
                body('username').isString().isLength({ max: 255})
                    .exists().withMessage('Username is required'),
                body('password').isString().isLength({min: 5 })
                    .exists().withMessage('Password is required'),
                body('role').isIn(['admin', 'super_admin'])
                    .exists().withMessage('Password is required'),
            ]
        }
        case 'editUser' : {
            return[
                body('full_name').isString().isLength({max: 255}),
                body('phone_number').isString().isLength({max: 255}),
                body('username').isString().isLength({max: 255}),
                body('password').if(body('password').exists({checkFalsy: true})).isString().isLength({
                    min: 5
                }),
                body('role').if(body('role').exists({checkFalsy: true})).isIn(['admin', 'super_admin']),
            ]
        }
    }
}

exports.validateCusBody = (method) => {
    switch (method){
        case 'signUp': {
            return [
                body('address').isString().isLength({ max: 255})
                    .exists().withMessage('Address is required'),
                body('phone_number').isString().isLength({max: 255})
                    .exists().withMessage('Phone number is required'),
                body('username').isString().isLength({ max: 255})
                    .exists().withMessage('Username is required'),
                body('password').isString().isLength({min: 5 })
                    .exists().withMessage('Password is required'),
            ]
        }
        case 'addToCart': {
            return [
                body('product_id').isInt().withMessage('Invalid value for product Id').isLength({ max: 255})
                    .exists().withMessage('product Id is required'),
                body('quantity').isInt().isLength({max: 100}).withMessage('Cant other more than 100 of a product')
                    .exists().withMessage('Quantity is required')
            ]
        }
        case 'checkout' : {
            return [
                body('order_email').isEmail().withMessage('Invalid value for order_email').isLength({ max: 255})
                    .exists().withMessage('order email is required'),
                body('billing_info').isString().isLength({max: 255})
                    .exists().withMessage('billing info is required'),
                body('shipping_address').isString().isLength({max: 255})
                    .exists().withMessage('shipping info is required'),
                body('order_details').isArray({min: 1}).withMessage('Cant checkout 0 products')
                    .exists().withMessage('Array of orders is required'),
                body('order_details.*.cart_id').exists().withMessage('product id is required for all'),
                
            ]
        }
        case 'editOrderStatus' : {
            return [
                body('status').isIn(["pending", "success", "failed", "reverted", "delived"]).withMessage('Invalid value for status')
                    .exists().withMessage('status is required'),
            ]
        }
    }
}