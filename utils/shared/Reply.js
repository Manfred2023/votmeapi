const config = require("../../config/config");
const ApiError = require("./errors");

// shared/Reply.js
class Reply {

    static success(res, data = {}, message = 'Opération réussie', status = 200) {
        return res.status(status).json({
            success: true,
            message: message,
            data: data,
        });
    }
    static bearer(res, message, accessToken, refreshToken, status = 200) {
        return res.status(status).json({
            success: true,
            message: message,
            data: {
                access_token: accessToken,
                token_type: "Bearer",
                expires_in: config.jwt.access_expireIn_s,
                refresh_token: refreshToken
            }
        });
    }

    static errorServer(res, message = 'Une erreur est survenue', status = 500, errorMessage, req) {
        return res.status(status).json(new ApiError({
            title: message,
            status: status,
            detail: errorMessage,
            req
        }));
    }

    static notFound(res, message = 'not found', errorMessage, status = 404, req) {
        return res.status(status).json(new ApiError({
            title: message,
            status: status,
            detail: errorMessage,
            req
        }));
        // return res.status(status).json({
        //     success: false,
        //     message,

        // });
    }


    static fail(res, message = 'Requête invalide', errorMessage, status = 400, req, errors = null) {
        return res.status(status).json(new ApiError({
            title: message,
            status: status,
            detail: errorMessage,
            errors: errors,
            req
        }));
    }
    static destroy(res, message,status = 204) {
        return res.status(status).json({
            success: false,
            message : message
        });
    }
}

module.exports = Reply;
