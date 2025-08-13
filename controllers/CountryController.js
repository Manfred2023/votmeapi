// controllers/countryController.js
const svc = require('../services/CountryServices');
const Reply = require("../utils/shared/Reply");

exports.create = async (req, res) => {
    try {
        const {   name,iso2,iso3 ,dialCode } = req.body;
        if (!name || !iso2 ||!iso3 || !dialCode) {
            return Reply.fail(res, 'Bad request');
        }
        const country = await svc.createCountry(req.body);
        Reply.success(res,country)

    } catch (e) {
        Reply.fail(res,e.message,500)

    }
};

exports.index = async (_req, res) => {
    const countries = await svc.listCountries();
    Reply.success(res,countries)

};

exports.show = async (req, res) => {
    const country = await svc.getByGuid(req.params.guid);
    if (!country) Reply.notFound(res);
    Reply.success(res,country)

};

exports.update = async (req, res) => {
    try {
        const updated = await svc.updateCountry(req.params.id, req.body);
        if (!updated) return Reply.notFound(res);
        res.json(updated);
    } catch (e) {
        res.status(400).json({ error: e.message });
    }
};

exports.destroy = async (req, res) => {
    const n = await svc.deleteCountry(req.params.id);
    if (!n) return Reply.notFound(res);
    Reply.destroy(res)
};

