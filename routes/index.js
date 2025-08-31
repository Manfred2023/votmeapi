const express = require("express");
const router = express.Router();

const countryRoutes = require('./CountryRoutes');
const clientAuthRoutes = require('./ClientAuthRoutes');
const { verifyToken } = require("../middleware/auth");


//Register all route group
router.use("/auth", clientAuthRoutes);
router.use("/countries",verifyToken, countryRoutes);

module.exports = router;