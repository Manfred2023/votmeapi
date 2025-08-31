// services/countryService.js
const Country = require('../models/CountryModel');

async function createCountry(payload) {
    return Country.create(payload);
}

async function listCountries() {
    return Country.findAll({ order: [['name', 'ASC']] });
}

async function findCountry(countryName) {
    const country = Country.findOne({ where: { name: countryName } });
    return country;
}

async function updateCountry(id, payload) {
    const c = await Country.findByPk(id);
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


module.exports = {
    createCountry,
    findCountry,
    listCountries,
    updateCountry,
    deleteCountry,
    getByGuid
};
