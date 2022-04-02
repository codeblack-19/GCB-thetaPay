const Sequelize = require("sequelize");
const sequelize = require("../db/connection.js");

module.exports = sequelize.define("sys_user", {
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
    }, full_name: {
        type: Sequelize.STRING(20),
        allowNull: false,
    }, phone_number: {
        type: Sequelize.STRING(15),
        allowNull: false
    }, role: {
        type: Sequelize.ENUM(["super_admin", "admin"]),
        allowNull: false,
    }
}, {
    timestamps: true,
    paranoid: true
})