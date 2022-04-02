require('dotenv').config();
const {Sequelize} = require('sequelize');

const sequelize = new Sequelize(`mariadb://${process.env.DB_user}@${process.env.DB_host}:${process.env.DB_port}/${process.env.DB_name}`, {
    operatorAliases: false,
});


try {
   sequelize.authenticate();
    console.log('Connection has been established successfully.');
} catch (error) {
    console.error('Unable to connect to the database:', error);
}
module.exports = sequelize;