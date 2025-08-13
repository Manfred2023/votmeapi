require('dotenv').config();
const sequelize = require('./config/database');

(async () => {
    try {
        await sequelize.authenticate();
        console.log('✅ Connexion réussie !');
    } catch (error) {
        console.error('❌ Erreur connexion :', error);
    } finally {
        await sequelize.close();
    }
})();
