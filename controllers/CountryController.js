// controllers/countryController.js
const svc = require('../services/CountryServices');
const Reply = require("../utils/shared/Reply");

exports.create = async (req, res) => {
    try {
        const reqData = req.validated;
        // const found = await svc.findCountry(reqData?.name);

        // if (found !== null)
        //     return Reply.errorServer(res, "country already exist", 409, "The country data you provided has already been saved", req);

        const errors = await svc.validateFieldsInDB(reqData);
        console.log(reqData);
        if (errors.length > 0) {
            return Reply.fail(res, "country data already exist", "One or more fields you provided already exist", 409, req, errors);
        }


        const savedCountry = await svc.createCountry(req.body);
        return Reply.success(res, savedCountry, "Country created successfully");

    } catch (err) {
        // console.log("An unespected error occur when saving country", err);
        return Reply.errorServer(res, "error while saving the country", 500, "An unespected error occur, try again later", req);

    }
};

exports.index = async (req, res) => {
    try {

        const countries = await svc.listCountries();

        if (countries === null) {
            return Reply.success(res, null, "No country found or saved", 404);
        }
        return Reply.success(res, countries, "List of available countries");
    } catch (err) {

        // console.log("An unespected error occur when retirving all counries", err);
        return Reply.errorServer(res, "An unexpected error orrcur", 500, "impossible to display all countries", req);
    }

};

exports.show = async (req, res) => {
    try {
        const country = await svc.getByGuid(req.params.guid);
        if (!country)
            return Reply.notFound(res, "Not found", "country doens't exist", 404, req);
        // Reply.success(res, country);
        return Reply.success(res, country, "Country data");

    } catch (err) {
        // console.log("An unespected error occur when retreiving country", err);
        return Reply.errorServer(res, "An unexpected error orrcur", 500, "impossible to retrieve the country data, try again later", req);
    }

};

exports.update = async (req, res) => {
    try {
        const oldCountryData = req.validated;
        countryGuid = req.params.guid;

        const errors = await svc.validateFieldsInDB(oldCountryData,countryGuid);
        if (errors.length > 0) {
            return Reply.fail(res, "validation failed", "One or more fields are required", 422, req, errors)
        }

        const newCountry = await svc.updateCountry(countryGuid, oldCountryData);
        if (!newCountry)
            return Reply.notFound(res, "Not found", "country doens't exist", 404, req);

        return Reply.success(res, newCountry, "Country data updated successfully");
    } catch (err) {
        console.log("An unespected error occur when updating country", err);
        return Reply.errorServer(res, "error while updating the country", 500, "An unespected error occur, try again later", req);
    }
};

exports.destroy = async (req, res) => {
    try {
        const n = await svc.deleteCountry(req.params.guid);
        if (!n)
            return Reply.notFound(res, "Not found", "country doens't exist", 404, req);

        return Reply.destroy(res, "Country deleted successfully");
    } catch (err) {
        // console.log("An unespected error occur when updating country", err);
        return Reply.errorServer(res, "error while deleting the country", 500, "An unespected error occur, try again later", req);
    }
};

