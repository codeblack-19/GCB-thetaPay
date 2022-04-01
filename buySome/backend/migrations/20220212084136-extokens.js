'use strict';

module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.createTable('extokens', {
      token: {
        type: Sequelize.STRING,
        primaryKey: true,
        allowNull: false
      }, customer_id: {
        type: Sequelize.UUID,
        allowNull: false,
        references: {
          model: "customers",
          key: "id"
        }
      }, createdAt: Sequelize.DATE,
        updatedAt: Sequelize.DATE,
        deletedAt: Sequelize.DATE
    });
  },

  async down (queryInterface, Sequelize) {
    await queryInterface.dropTable('extokens');
  }
};
