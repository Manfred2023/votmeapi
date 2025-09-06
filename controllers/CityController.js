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

exports.update = async (req, res) => {
    try {
        const oldCity = req.validated;
        cityGuid = req.params.guid;

        const errors = await svc.validateFieldsInDB({ name: oldCity.name }, cityGuid);
        if (errors.length > 0)
            return Reply.fail(res, "validation failed", "One or more fields are required", 422, req, errors);

        const cityExist = await svc.findCity(cityGuid);
        if (!cityExist)
            return Reply.notFound(res, "Not found", "city doens't exist", 404, req);

        //if the country guid is provided in the request body
        if (oldCity.countryId) {
            const countryData = await svc.getCountry(oldCity.countryId);
            if (!countryData)
                return Reply.notFound(res, "Not found", "country doens't exist", 404, req, {
                    field: "countryId",
                    message: `country with id : ${oldCity.countryId} doens't exist`
                });

            oldCity.countryId = countryData.id;
        }

        //update now
        const updatedCity = await svc.updateCity(cityGuid, oldCity);
        if (!updatedCity)
            throw new Error("Error while updatting the contact");

        return Reply.success(res, updatedCity, "City data updated successfully");

    } catch (err) {
        console.log("An unespected error occur when updating country", err);
        return Reply.errorServer(res, "error while updating the city", 500, "An unespected error occured");
    }
}