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

async function findCity(guid) {
    const city = City.findOne({
        where: { guid },
        include: [
            {
                model: Country,
                as: "country", // must match the alias defined in associations
            },
        ],
    })
    return city;
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
    validateFieldsInDB
}