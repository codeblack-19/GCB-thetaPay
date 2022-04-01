const Sequelize = require("sequelize");
const sequelize = require("../db/connection.js");

module.exports = sequelize.define("order_detail", {
    id: {
        type: Sequelize.INTEGER,
        allowNull: false,
        autoIncrement: true,
        primaryKey: true
    }, quantity: {
        type: Sequelize.INTEGER(10),
        allowNull: false
    }, order_id: {
        type: Sequelize.INTEGER,
        allowNull: false,
        references: {
            model: "orders",
            key: "id"
        },
    }, product_id : {
        type: Sequelize.INTEGER,
        allowNull: false,
        references: {
            model: "product",
            key: "id"
        }
    }
}, {
    timestamps: true,
    paranoid: true
})