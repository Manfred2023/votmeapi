<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

if (!@require realpath(dirname(__DIR__, 2)) . '/conf.php')
    http_response_code(403);

require_once dirname(__DIR__, 2) . '/lib/utilities/Constant.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    Criteria::_formRequiredCheck([APP, VRSN, IDNTFR], $data);

    if (ConstantApp::APP_KEY != $data[APP])
        Reply::_error("Invalid_application_key", code: 400);
    if (ConstantApp::APP_VERSION != $data[VRSN])
        Reply::_error("you_need_to_upgrade_your_apk", code: 400);
    if (ConstantApp::APP_IDENTIFIER != $data[IDNTFR])
        Reply::_error("Invalid_application_identifier", code: 400);

    $JwtController = new Jwt(ConstantApp::APP_IDENTIFIER, $data[APP]);

    Reply::_successBearer($JwtController->encode(), type: 'bearer');

} catch (Exception $exception) {
    Reply::_exception($exception);
}

?>
