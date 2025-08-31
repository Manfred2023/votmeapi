const Joi = require("joi");
const jwt = require('jsonwebtoken');
const config = require("../config/config");

//  Son nom
// - Son Identifiant Unique (UUID)
// - Sa version
// - Le code de l’application(ce code lui sera donné par le service technique)

const createClientIdSchema = Joi.object({
    name: Joi.string().valid(config.app.name).required().messages({
        // "string.base": "Name must be a text string",
        "any.only": "Invalid app name",
        "string.empty": "Name is required",
        "any.required": "Name is required"
    }),
    uuid: Joi.string().required().messages({
        "string.base": "uuid must be a text string",
        "string.empty": "uuid is required",
        "any.required": "uuid is required"
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
    }).required().messages({
        "any.required": "Request body is required",
        "object.base": "Request body must be an object"
    })
})

const refreshClientIdSchema = Joi.object({
    name: Joi.string().valid(config.app.name).required().messages({
        // "string.base": "Name must be a text string",
        "any.only": "Invalid app name",
        "string.empty": "Name is required",
        "any.required": "Name is required"
    }),
    uuid: Joi.string().required().messages({
        "string.base": "uuid must be a text string",
        "string.empty": "uuid is required",
        "any.required": "uuid is required"
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
    }),
    refresh_token: Joi.string()
        .custom((value, helpers) => {
            try {
                const data = jwt.verify(value, config.jwt.secret);
                if (data?.code !== config.app.code || data?.version !== config.app.version || data?.name !== config.app.name) {
                    return helpers.error("any.invalid");
                }
                // If valid, return the original token value
                return value;
            } catch (error) {
                //if token verification fail
                return helpers.error("any.fail");
            }
        }).required()
        .messages({
            "string.base": "Refresh token must be a string",
            "any.invalid": "User data do not match the refresh token data",
            "any.fail": "Invalid : Refresh token is expired or revoked",
            "any.required": "refresh roken is required"
        }).required().messages({
            "any.required": "Request body is required",
            "object.base": "Request body must be an object"
        })
})


module.exports = { createClientIdSchema, refreshClientIdSchema };