// routes/countryRoutes.js
const express = require('express');
const ctrl = require('../controllers/countryController');
const router = express.Router();


router.post('/', ctrl.create);
router.get('/', ctrl.index);
router.get('/:guid', ctrl.show);
router.put('/:guid', ctrl.update);
router.delete('/:guid', ctrl.destroy);

module.exports = router;
