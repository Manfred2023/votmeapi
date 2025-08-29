// middleware/jwtSimple.js
const jwt = require('jsonwebtoken');
const config = require('../config/config');

/**
 * generate user unique token
 * @param {Object} clientData 
 * @param {*} expiresIn default '2h'
 * @returns Bearer token
 */
function generateToken(clientData, expiresIn = '24h') {
    const payload = clientData;
    const token = jwt.sign(payload, config.jwt.secret, { expiresIn });
    return token;
}

function verifyToken(req, res, next) {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1]; // Bearer <token>

    if (!token) return res.status(401).json({ success: false, message: 'Token manquant' });

    try {
        req.client = jwt.verify(token, config.jwt.secret); // apiKey + appName disponible dans req.client
        next();
    } catch (err) {
        return res.status(403).json({ success: false,
            error: "invalid token",
            error_description: "token is expired or revoked",
        });
    }
}

module.exports = { generateToken, verifyToken };
