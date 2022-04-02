'use strict';
const bcrypt = require('bcrypt');
const {
  v4: uuidv4
} = require('uuid');

module.exports = {
  async up(queryInterface, Sequelize) {

    await queryInterface.bulkInsert('sys_users', [{
      id: uuidv4(),
      username: "magnito",
      phone_number: "+233547555463",
      full_name: "Magnum Magnet",
      password: bcrypt.hashSync('123456789', 10),
      role: "super_admin",
      createdAt: new Date(),
      updatedAt: new Date(),
      deletedAt: null
    }], {});

  },

  async down(queryInterface, Sequelize) {
    /**
     * Add commands to revert seed here.
     *
     * Example:
     * await queryInterface.bulkDelete('People', null, {});
     */
    await queryInterface.bulkDelete('sys_users', null, {});
  }
};