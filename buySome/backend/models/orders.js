const Sequelize = require("sequelize");
const sequelize = require("../db/connection.js");

module.exports = sequelize.define("orders", {
    id: {
        type: Sequelize.INTEGER,
        allowNull: false,
        autoIncrement: true,
        primaryKey: true
    }, status: {
        type: Sequelize.ENUM(["pending", "success", "failed", "reverted", "delived"]),
        allowNull: false,
    }, order_email: {
        type: Sequelize.STRING,
        allowNull: false,
    }, billing_info: {
        type: Sequelize.STRING,
        allowNull: false
    }, shipping_address: {
        type: Sequelize.STRING,
        allowNull: false
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
    paranoid: true
})