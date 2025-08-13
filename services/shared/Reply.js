// shared/Reply.js
class Reply {

    static success(res, data = {}, message = 'Opération réussie', status = 200) {
        return res.status(status).json({
            success: true,
            message,
            data,
        });
    }
    static bearer(res,  bearer , status = 200) {
        return res.status(status).json({
            success: true,
            bearer,
        });
    }


    static errorServer(res, message = 'Une erreur est survenue', status = 500, data = {}) {
        return res.status(status).json({
            success: false,
            message,
            data,
        });
    }


    static fail(res, message = 'Requête invalide', status = 400 ) {
        return res.status(status).json({
            success: false,
            message,

        });
    }
}

module.exports = Reply;
