const Reply = require("../utils/shared/Reply");
const { generateToken } = require("../middleware/auth");

exports.createClientID = async (req, res) => {
    // console.log('validated data', req);
    const clientData = req.validated; // validated input
    

    const bearerToken = generateToken(clientData);
    if (bearerToken)
        Reply.bearer(res, bearerToken);
    else
        Reply.errorServer(res, "An error occur, try later");
}