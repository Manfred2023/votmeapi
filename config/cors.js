const cors = require('cors');
const config = require('./config');

//load allowed origins from env or hardcode fallback
const allowedOrigins = config.cors.origins ? config.cors.origins.split(',') : [
    'https://admin.votme.cm',
    'https://votme.cm'
];
// Check if config.cors.origins is being read correctly
// console.log('Allowed Origins:', allowedOrigins);

const corsOptions = {
    origin: function (origin, callback) {
        // Allow request with no origin (like mobile apps, curl, Postman)
        if (!origin)
            return callback(null, true);

        if (allowedOrigins.includes(origin)) {
            return callback(null, true);
        } else {
            return callback(new Error('Not allowed by CORS'));
        }

    },
    credentials: true, //Allow cookies, authorization headers,
    allowedHeaders: ['Content-Type', 'Authorization'], // Add this
}

module.exports = cors(corsOptions);