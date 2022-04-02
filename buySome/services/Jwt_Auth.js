require('dotenv').config();
const jwt = require('jsonwebtoken');

async function getToken(user, type) {
    if(type == 'admin'){
        return jwt.sign({
            uid: user.id,
            username: user.username,
            role: user.role,
        }, process.env.JWT_Auth_key_2, {
            expiresIn: "7d"
        })
    }else{
        return jwt.sign({
            uid: user.id,
            username: user.username,
        }, process.env.JWT_Auth_key_1, {
            expiresIn: "7d"
        })
    }
}

module.exports = {
    getToken
}