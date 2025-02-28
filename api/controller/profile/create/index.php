<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require dirname(__DIR__, 3) . '/conf.php';

require_once dirname(__DIR__, 3) . '/lib/utilities/Auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Reply::_error("Only POST method is allowed", code: 405);
    exit();
}
if (!validateBearerToken()) {
    Reply::_error("Invalid or missing Bearer token", code: 401);
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);


    Criteria::_formRequiredCheck([TOKEN, LABEL, DSCRPTN], $data);


    $profile = new Profile(null, $data[TOKEN], $data[LABEL], $data[DSCRPTN]);

    $profiles = $profile->save();
    Reply::_success($profiles->toArray());
} catch (Exception $exception) {
    Reply::_exception($exception);
}


