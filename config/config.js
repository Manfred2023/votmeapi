require("dotenv").config(); // Load variables from .env

const APP_MODE = 'dev'; // or  'prod'

// Load the correct .env file based on NODE_ENV
// const envFile = process.env.NODE_ENV === 'production' ? '.env.production' : '.env.development';
// dotenv.config({ path: envFile }); or dotenv.config({ path: `.env.${process.env.NODE_ENV}` });

const config = {
    app: {
        name: process.env.APP_NAME,
        version: process.env.APP_VERSION,
        port: process.env.PORT || 3000,
        code: process.env.APP_CODE,
        mode : APP_MODE,
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
    cors: {
        origins: process.env.ALLOWED_ORIGINS
    }
};

module.exports = config;
