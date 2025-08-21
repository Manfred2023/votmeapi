const Joi = require("joi");
const config = require("../config/config");

//  Son nom
// - Son Identifiant Unique (UUID)
// - Sa version
// - Le code de l’application(ce code lui sera donné par le service technique)

const createClientIdSchema = Joi.object({
    name: Joi.string().required().messages({
        "string.base": "Name must be a text string",
        "string.empty": "Name is required",
        "any.required": "Name is required"
    }),
    UUID: Joi.string().required().messages({
        "string.base": "UUID must be a text string",
        "string.empty": "UUID is required",
        "any.required": "UUID is required"
    }),
    version: Joi.string().valid(config.app.version).required().messages({
        "any.only": "Invalid version",
        "string.empty": "Version is required",
        "any.required": "Version is required"
    }),
    code: Joi.string().valid(config.app.code).required().messages({
        "any.only": "Invalid code",
        "string.empty": "Code is required",
        "any.required": "Code is required"
    })
})

module.exports = { createClientIdSchema };