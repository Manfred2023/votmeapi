// shared/Reply.js
class Reply {

    static success(res, data = {}, message = 'Opération réussie', status = 200) {
        return res.status(status).json({
            success: true,
            data,
        });
    }
    static bearer(res,  bearer , status = 200) {
        return res.status(status).json({
            success: true,
            bearer,
        });
    }


    static errorServer(res, message = 'Une erreur est survenue', status = 500 ) {
        return res.status(status).json({
            success: false,
            message,

        });
    }
    static notFound(res, message = 'not found', status = 404 ) {
        return res.status(status).json({
            success: false,
            message,

        });
    }


    static fail(res, message = 'Requête invalide', status = 400 ) {
        return res.status(status).json({
            success: false,
            message,

        });
    }
    static destroy(res,   status = 204 ) {
        return res.status(status).json({
            success: false,
        });
    }
}

module.exports = Reply;
