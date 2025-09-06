// services/countryService.js
const { Country, City } = require('../models');
const { validateFieldsInDb } = require('../utils/shared/formatters');

async function createCountry(payload) {
    return Country.create(payload);
}

async function listCountries(withCity = false) {
    return Country.findAll({
        order: [['name', 'ASC']],
        include: withCity ? [{
            model: City,
            as: "cities"
        }] : [],
    });
}

async function findCountry(countryName, withCity = false) {
    const country = Country.findOne({
        where: { countryName },
        include: withCity ? [{
            model: City,
            as: "cities" // must match the alias defined in associations
        }] : [],
    });
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

async function getByGuid(guid, withCity = false) {
    const country = await Country.findOne({
        where: { guid },
        include: withCity ? [{
            model: City,
            as: "cities" // must match the alias defined in associations
        }] : [],
    });
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
