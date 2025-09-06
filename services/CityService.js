const { City, Country } = require('../models');
const { validateFieldsInDb } = require('../utils/shared/formatters');
const countryService = require('./CountryServices');

async function createCity(city) {
    const country = await countryService.getByGuid(city.countryId);
    if (!country || !country.id)
        return 0;
    city.countryId = country.id;
    const savedCity = await City.create(city);
    if (!savedCity || !savedCity.guid)
        return 0;
    const fullCityData = await findCity(savedCity.guid);
    return fullCityData;
}

async function findCity(guid, withCountry = true) {
    const city = City.findOne({
        where: { guid },
        include: withCountry ? [
            {
                model: Country,
                as: "country", // must match the alias defined in associations
            },
        ] : [],
    })
    return city;
}

async function getCountry(countryGuid) {
    return await countryService.getByGuid(countryGuid);
}

async function updateCity(guid, payload) {

    const [updated] = await City.update(payload, { where: { guid } });
    if (!updated) return null;
    return await findCity(guid);

}


/**
 * verify that the data we want to create or update is unique
 */
async function validateFieldsInDB(input, guid = null) {
    return await validateFieldsInDb(City, input, guid);
}

module.exports = {
    createCity,
    findCity,
    updateCity,
    validateFieldsInDB,
    getCountry
}