// services/countryService.js
const { Country } = require('../models');
const { validateFieldsInDb } = require('../utils/shared/formatters');

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
 * Il guid is provided then the verification should ignore the entry with that uuid
 * */
async function validateFieldsInDB(input, guid = null) {
    return await validateFieldsInDb(Country, input, guid);
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
