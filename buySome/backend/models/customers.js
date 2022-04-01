const Sequelize = require("sequelize");
const sequelize = require("../db/connection.js");

module.exports = sequelize.define("customer", {
    id: {
        type: Sequelize.UUID,
        defaultValue: Sequelize.UUIDV4,
        allowNull: false,
        primaryKey: true,
    }, username: {
        type: Sequelize.STRING(20),
        allowNull: false,
        unique: true
    }, password: {
        type: Sequelize.STRING(60),
        allowNull: false,
    }, phone_number: {
        type: Sequelize.STRING(15),
        allowNull: false
    }, address: {
        type: Sequelize.STRING,
        allowNull: false,
    }
}, {
    timestamps: true,
    paranoid: true
})