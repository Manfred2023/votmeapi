require("dotenv").config(); // Load variables from .env

const config = {
    app: {
        name: process.env.APP_NAME,
        version: process.env.APP_VERSION,
        port: process.env.PORT || 3000,
        code: process.env.APP_CODE
    },
    db: {
        host: process.env.DB_HOST,
        name: process.env.DB_NAME,
        user: process.env.DB_USER,
        password: process.env.DB_PASS,
        port: process.env.DB_PORT
    },
    jwt: {  // you can use "1h", "60s", "2d"
        secret: process.env.JWT_SECRET,
        access_expireIn_h: '24h',
        access_expireIn_s: '86400', //in seconds
        refresh_expireIn_d: '7d',
        refresh_expireIn_s: '604800' //in seconds

    },
};

module.exports = config;
