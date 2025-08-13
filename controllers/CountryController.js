// controllers/userController.js
//const User = require('../models/CountryModel');

const Reply = require("../services/shared/Reply");
exports.getAllUsers = async (req, res) => {
    try {
        Reply.success(res, { foo: 'bar' }, 'Tout est OK');
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};
