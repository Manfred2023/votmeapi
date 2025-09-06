const svc = require('../services/CityService');
const Reply = require('../utils/shared/Reply');

exports.create = async (req, res) => {
    try {
        const reqData = req.validated;

        const errors = await svc.validateFieldsInDB(reqData);
        if (errors.length > 0) {
            return Reply.fail(res, "City data already exist", "One or more fields you provided already exist", 409, req, errors);
        }

        const savedCity = await svc.createCity(reqData);
        return Reply.success(res, savedCity, "City created successfully");

    } catch (err) {
        // console.log("error saving a city", err);
        return Reply.errorServer(res, "error while saving the city", 500, "An unespected error occured, try again later", req);
    }
}

exports.show = async (req, res) => {
    try {
        const cityGuid = req.params.guid;
        const withCountry = req.query.withCountry === "false" ? false : true; // query param → condition 

        const city = await svc.findCity(cityGuid, withCountry);

        if (!city)
            return Reply.notFound(res, "Not found", "city does not exist", 404, req);

        return Reply.success(res, city, "City data");
    } catch (err) {
        return Reply.errorServer(res, "An unespected error occur", 500, "Impossible to retrieve the city data, try again later", req);
    }
}