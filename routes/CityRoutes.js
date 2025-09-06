const express = require('express');
const ctrl = require('../controllers/CityController');
const validate = require('../middleware/validate');
const { createCitySchema } = require('../validators/CitySchema');

const router = express.Router();

router.post('/', validate(createCitySchema), ctrl.create);
router.get('/:guid', ctrl.show);

module.exports = router;