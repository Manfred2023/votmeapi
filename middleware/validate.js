const ApiError = require("../utils/shared/errors");

const validate = (schema) => (req, res, next) => {
    const result = schema.validate(req.body, { abortEarly: false }); // or req.params/query where needed //// With abortEarly: false → returns ALL errors

    if (result.error) {

        const errors = result.error.details.map((detail) => ({
            field: detail.path.join("."),
            message: detail.message.replace(/\"/g, "") //remove special characters in the error message
        }));

        // return res.status(400).json({ error: result.error.details.map(d => d.message) });

        return next(new ApiError({
            title: "validation failed",
            status: 422,
            detail: "One or more fields are invalid.",
            instance: req.originalUrl,
            errors
        }))
    }
    req.validated = result.value;
    next();
};

module.exports = validate;