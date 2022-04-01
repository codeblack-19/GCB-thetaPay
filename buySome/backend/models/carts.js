const Sequelize = require("sequelize");
const sequelize = require("../db/connection.js");

module.exports = sequelize.define("carts", {
    id: {
        type: Sequelize.INTEGER,
        allowNull: false,
        autoIncrement: true,
        primaryKey: true
    }, quantity: {
        type: Sequelize.INTEGER(10),
        allowNull: false
    }, product_id: {
        type: Sequelize.INTEGER,
        allowNull: false,
        references: {
            model: "products",
            key: "id"
        }
    }, customer_id : {
        type: Sequelize.UUID,
        allowNull: false,
        references: {
            model: "customers",
            key: "id"
        }
    }
}, {
    timestamps: true,
})