const Sequelize = require("sequelize");
const sequelize = require("../db/connection.js");

module.exports = sequelize.define("transactions", {
    id: {
        type: Sequelize.STRING,
        allowNull: false,
        primaryKey: true
    },
    status: {
        type: Sequelize.ENUM(["success", "failed"]),
        allowNull: false,
    },
}, {
    timestamps: true,
})