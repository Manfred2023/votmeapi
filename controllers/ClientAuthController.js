const Reply = require("../utils/shared/Reply");
const { generateToken } = require("../middleware/auth");
const config = require("../config/config");

exports.createClientID = async (req, res) => {
    // console.log('validated data', req);
    const clientData = req.validated; // validated input

    const accessToken = generateToken(clientData, config.jwt.access_expireIn_h);
    const refreshToken = generateToken(clientData, config.jwt.refresh_expireIn_d);
    if (accessToken && refreshToken)
        Reply.bearer(res, accessToken, refreshToken);
    else
        Reply.errorServer(res, "An error occur, try later");
}

exports.refreshClientId = async (req, res) => {
    //
    // const = 
    // on recois les informations de lutilisateurs ainsi que le refresh_token token. on compare les informations dans le refresh token avec les informations de l'utilisateur pour se rassurer que c'est le meme utilisateur qui veut refresh. si c'est le cas on refresh.

    //So in this case, you "refresh the refresh token" automatically whenever you refresh the access token.

    //# ecrire un code qui retourne vrai ou faux et utilisable dans le validate schema moddleware...
    const clientData = req.validated; // validated input
    delete clientData?.refresh_token;

    const accessToken = generateToken(clientData, config.jwt.access_expireIn_h);
    const refreshToken = generateToken(clientData, config.jwt.refresh_expireIn_d);
    if (accessToken && refreshToken)
        Reply.bearer(res, accessToken, refreshToken);
    else
        Reply.errorServer(res, "An error occur, try later");

}