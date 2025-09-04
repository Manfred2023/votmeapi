
function lowercaseFields(instance, fields = []) {
    fields.forEach((field) => {
        if (instance[field] && typeof instance[field] === "string") {
            instance[field] = instance[field].toLowerCase();
        }
    })
}

module.exports = {
    lowercaseFields
};