const express = require("express");
const router = express.Router();

const countryRoutes = require('./CountryRoutes');
const clientAuthRoutes = require('./ClientAuthRoutes');


//Register all route group
router.use("/auth", clientAuthRoutes);
router.use("/countries", countryRoutes);

module.exports = router;