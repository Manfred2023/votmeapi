

class ApiError extends Error {
    constructor({
        title, status, detail, instance, errors, req
    }) {
        super(detail);
        this.title = title || "An error occured";
        this.status = status || 500;
        this.success = false;
        this.detail = detail || "Unespected error";
        this.instance = instance || (req ? req.originalUrl : "");
        this.errors = errors || undefined; //optionnal field-level errors
    };

    toJSON() {
        return {
            title: this.title,
            status: this.status,
            success: this.success,
            detail: this.detail,
            instance: this.instance,
            ...(this.errors ? { errors: this.errors } : {}) // only include if present
        };
    }

}

module.exports = ApiError;