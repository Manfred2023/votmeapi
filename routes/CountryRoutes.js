// routes/countryRoutes.js
const express = require('express');
const ctrl = require('../controllers/CountryController');
const validate = require('../middleware/validate');
const { createCountrySchema, updateCountrySchema } = require('../validators/CountrySchema');
const router = express.Router();


router.post('/', validate(createCountrySchema), ctrl.create);
router.get('/', ctrl.index);
router.get('/:guid', ctrl.show);
router.put('/:guid', validate(updateCountrySchema), ctrl.update);
router.delete('/:guid', ctrl.destroy);

module.exports = router;
