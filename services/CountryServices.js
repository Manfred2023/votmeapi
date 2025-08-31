// services/countryService.js
const Country = require('../models/CountryModel');

async function createCountry(payload) {
    return Country.create(payload);
}

async function listCountries() {
    return Country.findAll({ order: [['name', 'ASC']] });
}

async function findCountry(countryName) {
    const country = Country.findOne({ where: { countryName } });
    return country;
}

async function updateCountry(guid, payload) {
    const c = await Country.findOne({ where: { guid } });
    if (!c) return null;
    await c.update(payload);
    return c;
}

async function deleteCountry(guid) {
    const c = await Country.findOne({ where: { guid } });
    if (!c) return 0;
    await c.destroy();
    return 1;
}

async function getByGuid(guid) {
    const country = await Country.findOne({ where: { guid } });
    if (!country) return 0;
    return country;
}

/**
 * Verify each field of an object against the database.
 * @param {Object} input - Object to validate (ex: req.body)
 * @param {Object} models - Map of field->DB model (so we know where to check)
 * @returns {Array} errors - Array of error objects { field, message }
 */
async function validateFieldsInDB(input) {
    const errors = [];

    for (const [field, value] of Object.entries(input)) {
        if (value == null) continue; // skip null/undefined

        // const Model = models[field];
        // if (!Model) continue; // no DB mapping for this field

        const exists = await Country.findOne({ where : {[field]: value }});
        if (exists) {
            errors.push({
                field,
                message: `${field} "${value}" already exists`,
            });
        }
    }

    return errors;
}



module.exports = {
    createCountry,
    findCountry,
    listCountries,
    updateCountry,
    deleteCountry,
    getByGuid,
    validateFieldsInDB
};
