const Joi = require("joi");

const createCountrySchema = Joi.object({
    name: Joi.string().required().messages({
        "string.empty": "Name is required",
        "any.required": "Name is required"
    }),
    iso2: Joi.string().required().messages({
        "string.empty": "iso2 is required",
        "any.required": "iso2 is required"
    }),
    iso3: Joi.string().required().messages({
        "string.empty": "iso3 is required",
        "any.required": "iso3 is required"
    }),
    dialCode: Joi.number().integer() // optional: restrict to integers only
        .min(0)    // optional: no negatives
        .max(999)  // optional: max 3 digits
        .required()
        .messages({
            "number.base": "dialCode must be a valid number",
            "number.integer": "dialCode must be an integer",
            "number.max": "dialCode cannot be more than 3 digits",
            "any.required": "dialCode is required"
        }),

}).required().messages({
    "any.required": "Request body is required",
    "object.base": "Request body must be an object"
});

module.exports = {
    createCountrySchema
}