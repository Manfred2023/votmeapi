require("dotenv").config(); // Load variables from .env

const config = {
    app: {
        name: process.env.APP_NAME,
        version: process.env.APP_VERSION,
        port: process.env.PORT || 3000,
        code : process.env.APP_CODE
    },
    db: {
        host: process.env.DB_HOST,
        name: process.env.DB_NAME,
        user: process.env.DB_USER,
        password: process.env.DB_PASS,
        port: process.env.DB_PORT
    },
    jwt: {
        secret: process.env.JWT_SECRET,
        expireIn_h: '2h',
        expireIn_s: '7200' //in seconds 
    },
};

module.exports = config;
