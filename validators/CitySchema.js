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

const updateCitySchema = Joi.object({
    name: Joi.string().optional().messages({
        "string.empty": "City Name can't be null",
    }),
    countryId: Joi.string().optional().messages({
        "string.empty": "Country identifier can't be null",
    }),
}).min(1).required().messages({
    "any.required": "Request body is required",
    "object.base": "Request body must be an object",
    "object.min": "At least one field is required",
});

module.exports = {
    createCitySchema,
    updateCitySchema
}