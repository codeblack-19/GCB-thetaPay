require('dotenv').config();
const {Sequelize} = require('sequelize');

const sequelize = new Sequelize(process.env.DB_name, process.env.DB_username, process.env.DB_password, {
    host: process.env.DB_host,
    dialect: "mysql",
    operatorAliases: false,
});

sequelize.authenticate()
    .then(() => {
        console.log('Connection has been established successfully.');
    }).catch((error) => {
        console.error('Unable to connect to the database:', error);
    })

module.exports = sequelize;