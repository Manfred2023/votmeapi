
const { Contact, City } = require('../models');
const { validateFieldsInDb } = require('../utils/shared/formatters');
const cityService = require('./CityService');

async function createContact(contact) {
    const city = await cityService.findCity(contact.cityId, false);
    if (!city || !city.id)
        return 0;

    contact.cityId = city.id;
    const savedContact = await Contact.create(contact);
    if (!savedContact || !savedContact.guid)
        return 0;
    const fullContactData = await findContact(savedContact.guid);
    return fullContactData;
}

async function findContact(guid, withCity = true) {
    const contact = Contact.findOne({
        where: { guid },
        include: withCity ? [
            {
                model: City,
                as: "city", // must match the alias defined in associations
            }
        ] : [],
    })
    return contact;
}

async function updateContact(guid, payload) {
    const [updated] = await Contact.update(payload, { where: { guid } });
    if (!updated) return null;
    return await findContact(guid);
}

async function deleteContact(guid) {
    const deleted = await Contact.destroy({
        where: { guid }
    });
    if (!deleted) return 0;
    return 1; //true
}

/**
 * verify that the data we want to create or update is unique
 */
async function validateFieldsInDB(input, guid = null) {
    return await validateFieldsInDb(Contact, input, guid);
}

module.exports = {
    createContact,
    findContact,
    updateContact,
    deleteContact,
    validateFieldsInDB
}