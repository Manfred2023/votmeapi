const { Op } = require('sequelize');

function lowercaseFields(instance, fields = []) {
    fields.forEach((field) => {
        if (instance[field] && typeof instance[field] === "string") {
            instance[field] = instance[field].toLowerCase();
        }
    })
}

/**
 * Removes specified fields from an object.
 * 
 * @param {Object} obj - The source object
 * @param {Array<string>} fields - List of fields to remove
 * @returns {Object} - New object without the specified fields
 */
function removeFields(obj, fields) {
    const newObj = { ...obj }; // clone to avoid mutating original
    fields.forEach((field) => {
        if (field in newObj) {
            delete newObj[field];
        }
    });
    return newObj;
}

/**
 * Verify each field of an object against the database.
 * @param {Object} input - Object to validate (ex: req.body)
 * @param {Object} models - Map of field->DB model (so we know where to check)
 * @returns {Array} errors - Array of error objects { field, message }
 */
async function validateFieldsInDb(Model, input, guid = null) {
    const errors = [];

    for (const [field, value] of Object.entries(input)) {
        if (value == null) continue; // skip null/undefined

        // const Model = models[field];
        // if (!Model) continue; // no DB mapping for this field

        const whereClause = { [field]: value };

        //if guid is provided exclude it (in the request)
        if (guid !== null) {
            whereClause.guid = { [Op.ne]: guid };
        }

        // const exists = await Country.findOne({ where : {[field]: value }});
        const exists = await Model.findOne({ where: whereClause });
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
    validateFieldsInDb,
    lowercaseFields,
    removeFields
};