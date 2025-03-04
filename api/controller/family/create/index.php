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

if (!@require realpath(dirname(__DIR__, 3)) . '/conf.php') ;
http_response_code(403);

require_once dirname(__DIR__, 3) . '/lib/utilities/Auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Reply::_error("Only POST method is allowed", code: 405);
    exit();
}

validateBearerToken();
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    Criteria::_formRequiredCheck([TOKEN, FAMILY,], $data);
    $family = new Family(null, $data[TOKEN], $data[FAMILY]);
    if ($family->getToken() != null) {
        $result = Family::_get(Criteria::TOKEN, $family->getToken());
        if (!$result instanceof Family) Reply::_error('family_not_found');

        $result->setFamily($family->getFamily());
        $resultt = $result->save();
        if ($resultt instanceof Family) {
            Reply::_success($resultt->toArray());
        } else {
            Reply::_error($resultt);
        }

    } else {

        $result = $family->save();
        if ($result instanceof Family) {
            Reply::_success($result->toArray());
        } else {
            Reply::_error($result);
        }


    }


} catch (Exception $exception) {
    Reply::_exception($exception);
}

