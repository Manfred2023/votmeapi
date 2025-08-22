const express = require("express");
const ctrl = require('../controllers/ClientAuthController');
const validate = require('../middleware/validate');
const { createClientIdSchema, refreshClientIdSchema } = require("../validators/ClientAuthSchema");
const router = express.Router();


router.post('/client-id', validate(createClientIdSchema), ctrl.createClientID);
router.post('/client-id-refresh', validate(refreshClientIdSchema), ctrl.refreshClientId);

module.exports = router;