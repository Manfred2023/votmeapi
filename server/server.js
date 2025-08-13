// server.js
require('dotenv').config();
const express = require('express');
const cors = require('cors');
const sequelize = require('../config/database');

require('../models/CountryModel');
const CountryRouter = require("../routes/CountryRoutes");
const app = express();
const { generateToken } = require('../middleware/auth');
const rateLimiter = require('../middleware/rateLimiter');
const Reply = require("../services/shared/Reply");

app.use(cors());
app.use(express.json());
app.use(rateLimiter);


app.use('/country', CountryRouter);

app.post('/authBearer', (req, res) => {
    console.log(req)
    const { key, name } = req.body;
    if (!key || !name) {
        return Reply.fail(res, 'key et name sont requis');
    }

    const bearerToken = generateToken(key, name);

    Reply.bearer(res, bearerToken,  );
});


(async () => {
    try {
        await sequelize.authenticate();
        console.log('✅ Connexion MySQL réussie !');

        await sequelize.sync({ alter: true });
        console.log('✅ Tables synchronisées !');

        const PORT = process.env.PORT || 3000;
        app.listen(PORT, () => console.log(`🚀 Server running at http://localhost:${PORT}`));

    } catch (error) {
        console.error('❌ Erreur lors du démarrage du serveur :', error.name, error.message);
    }
})();
