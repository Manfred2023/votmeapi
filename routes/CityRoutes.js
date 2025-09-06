const express = require('express');
const ctrl = require('../controllers/CityController');
const validate = require('../middleware/validate');
const { createCitySchema, updateCitySchema } = require('../validators/CitySchema');

const router = express.Router();

router.post('/', validate(createCitySchema), ctrl.create);
router.get('/:guid', ctrl.show);
router.put('/:guid', validate(updateCitySchema), ctrl.update);

module.exports = router;