// controllers/countryController.js
const svc = require('../services/CountryServices');
const Reply = require("../utils/shared/Reply");

exports.create = async (req, res) => {
    try {
        const reqData = req.validated;

        const found = await svc.findCountry(reqData?.name);

        if (found !== null)
            return Reply.errorServer(res, "country already exist", 409, "The country data you provided has already been saved", req);


        const savedCountry = await svc.createCountry(req.body);
        Reply.success(res, savedCountry, "Country created successfully");

    } catch (err) {
        console.log("An unespected error occur when saving country", err);
        Reply.errorServer(res, "error while saving the country", 500, "AN unespected error occur, try again later", req);

    }
};

exports.index = async (_req, res) => {
    const countries = await svc.listCountries();
    Reply.success(res, countries)

};

exports.show = async (req, res) => {
    const country = await svc.getByGuid(req.params.guid);
    if (!country) Reply.notFound(res);
    Reply.success(res, country)

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

