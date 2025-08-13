// middleware/rateLimiter.js
const rateLimit = require('express-rate-limit');
const limiter = rateLimit({
    windowMs: 60 * 1000,
    max: 30,
    message: { success: false, message: "Trop de requêtes, réessayer plus tard" },
});

module.exports = limiter;
