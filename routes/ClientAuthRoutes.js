const express = require("express");
const ctrl = require('../controllers/ClientAuthController');
const validate = require('../middleware/validate');
const { createClientIdSchema } = require("../validators/ClientAuthSchema");
const router = express.Router();


router.post('/client', validate(createClientIdSchema), ctrl.createClientID);

module.exports = router;