// routes/CountryRoutes.js
const express = require('express');
const { getAllUsers } = require('../controllers/CountryController');
const CountryRouter = express.Router();

CountryRouter.get('/', getAllUsers);
CountryRouter.post('/', getAllUsers);

module.exports = CountryRouter;
