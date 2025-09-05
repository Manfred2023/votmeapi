const Joi = require("joi");

const createCitySchema = Joi.object({
    name: Joi.string().required().messages({
        "string.empty": "City Name is required",
        "any.required": "City Name is required"
    }),
    countryId: Joi.string().required().messages({
        "string.empty": "Country identifier is required",
        "any.required": "Country identifier is required"
    }),
}).required().messages({
    "any.required": "Request body is required",
    "object.base": "Request body must be an object"
});

module.exports = {
    createCitySchema
}