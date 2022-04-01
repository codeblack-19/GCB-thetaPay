const Sequelize = require("sequelize");
const sequelize = require("../db/connection.js");

module.exports = sequelize.define("extoken", {
    token: {
        type: Sequelize.STRING,
        primaryKey: true,
        allowNull: false
    }, customer_id : {
        type: Sequelize.UUID,
        allowNull: false,
        references: {
            model: "customers",
            key: "id"
        }
    },
}, {
    timestamps: true,
})