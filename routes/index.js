const express = require("express");
const router = express.Router();

const countryRoutes = require('./CountryRoutes');
const clientAuthRoutes = require('./ClientAuthRoutes');
const cityRoutes = require('./CityRoutes');
const { verifyToken } = require("../middleware/auth");


//Register all route group
router.use("/auth", clientAuthRoutes);
router.use("/countries", verifyToken, countryRoutes);
router.use("/cities",verifyToken, cityRoutes);

module.exports = router;