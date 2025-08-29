// index.js
const express = require('express');
const cors = require('cors');
const sequelize = require('./config/database');
const rateLimiter = require('./middleware/rateLimiter');
const routes = require("./routes/"); //central routes loader

const config = require("./config/config");
const ApiError = require('./utils/shared/errors');

const app = express();

app.use(cors());
app.use(express.json());
app.use(rateLimiter);

//Routes
app.use("/api", routes);

// --- Catch 404 for unmatched routes ---
app.use((req, res, next) => {
    next(new ApiError({
        title: "Notfound",
        status: 404,
        detail: `cannot find ${req.originalUrl}`,
        instance: req.originalUrl
    }));
})

// Error-handling middleware
app.use((err, req, res, next) => {
    if (err instanceof ApiError) {
        res.status(err.status).json(err.toJSON());
    } else {
        console.error(err); //log server errors
        res.status(500).json(new ApiError({
            title: "Internal Server Error",
            status: 500,
            detail: err.message,
            instance: req.originalUrl
        }).toJSON());
    }
})
app.disable('x-powered-by'); //Many developers disable it only for security through obscurity (so attackers can’t immediately know you’re running Express).


    // --- Databae connexion and synchronisation ---
    (async () => {
        try {
            await sequelize.authenticate();
            console.log('✅ Connexion MySQL réussie !');

            await sequelize.sync({ alter: true });
            console.log('✅ Tables synchronisées !');

            const PORT = config.app.port;
            app.listen(PORT, () => console.log(`🚀 Server running at http://localhost:${PORT}`));

        } catch (error) {
            console.error('❌ Erreur lors du démarrage du serveur :', error.name, error.message);
        }
    })();
