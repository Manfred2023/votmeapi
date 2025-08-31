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
        // console.log("An unespected error occur when saving country", err);
        Reply.errorServer(res, "error while saving the country", 500, "An unespected error occur, try again later", req);

    }
};

exports.index = async (_req, res) => {
    try {

        const countries = await svc.listCountries();

        if (countries === null) {
            Reply.success(res, null, "No country found or saved", 404);
        }
        Reply.success(res, countries, "List of available countries");
    } catch (err) {

        // console.log("An unespected error occur when retirving all counries", err);
        Reply.errorServer(res, "An unexpected error orrcur", 500, "impossible to display all countries", req);
    }

};

exports.show = async (req, res) => {
    try {
        const country = await svc.getByGuid(req.params.guid);
        if (!country)
            Reply.notFound(res, "Not found", "country doens't exist", 404, req);
        // Reply.success(res, country);
        Reply.success(res, country, "Country data");

    } catch (err) {
        console.log("An unespected error occur when retirving all counries", err);
        Reply.errorServer(res, "An unexpected error orrcur", 500, "impossible to retrieve the country data, try again later", req);
    }

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

