'use strict';

module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.createTable('customers', {
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
      },
      createdAt: Sequelize.DATE,
      updatedAt: Sequelize.DATE,
      deletedAt: Sequelize.DATE
    });
  },

  async down (queryInterface, Sequelize) {
    await queryInterface.dropTable('customers');
  }
};
