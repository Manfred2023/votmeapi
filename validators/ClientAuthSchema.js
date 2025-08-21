const Joi = require("joi");

//  Son nom
// - Son Identifiant Unique (UUID)
// - Sa version
// - Le code de l’application(ce code lui sera donné par le service technique)

const createClientIdSchema = Joi.object({
    name: Joi.string().required(),
    UUID: Joi.string().required(),
    version: Joi.string().required(), // add version check
    code: Joi.string().required() // add code check
})

module.exports = { createClientIdSchema };