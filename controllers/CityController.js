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