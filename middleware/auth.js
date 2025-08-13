// middleware/jwtSimple.js
const jwt = require('jsonwebtoken');


function generateToken(apiKey, appName, expiresIn = '2h') {
    const payload = { apiKey, appName };
    const token = jwt.sign(payload, process.env.JWT_SECRET, { expiresIn });
    return `Bearer ${token}`;
}

function verifyToken(req, res, next) {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1]; // Bearer <token>

    if (!token) return res.status(401).json({ success: false, message: 'Token manquant' });

    try {
        req.client = jwt.verify(token, process.env.JWT_SECRET); // apiKey + appName disponible dans req.client
        next();
    } catch (err) {
        return res.status(403).json({ success: false, message: 'Token invalide' });
    }
}

module.exports = { generateToken, verifyToken };
