const sequelize = require('./database');
const CountryModel = require('../models/CountryModel');

(async () => {
    try {
        await sequelize.sync( );
        console.log('✅ Tables synchronisées avec MySQL !');
    } catch (error) {
        console.error('❌ Erreur lors de la synchronisation :', error.message);
    } finally {
        await sequelize.close();
    }
})();
