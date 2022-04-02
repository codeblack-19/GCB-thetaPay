const Sequelize = require("sequelize");
const sequelize = require("../db/connection.js");

module.exports = sequelize.define("products", {
    id: {
        type: Sequelize.INTEGER,
        allowNull: false,
        autoIncrement: true,
        primaryKey: true
    }, name: {
        type: Sequelize.STRING,
        allowNull: false,
    }, price: {
        type: Sequelize.FLOAT,
        allowNull: false,
    }, quantity: {
        type: Sequelize.INTEGER,
        allowNull: false
    }, description: {
        type: Sequelize.STRING,
        allowNull: false
    }, image_url : {
        type: Sequelize.STRING,
        allowNull: false
    }
}, {
    timestamps: true,
    paranoid: true
})