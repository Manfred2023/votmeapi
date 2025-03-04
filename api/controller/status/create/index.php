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

    Criteria::_formRequiredCheck([TOKEN, POSITION, LABEL, REFERENCE, DSCRPTN, FAMILY], $data);
    $family = Family::_get(Criteria::TOKEN, $data[FAMILY]);
    $status = new Status(null, $data[TOKEN], $data[POSITION], $data[LABEL], $data[REFERENCE], $data[DSCRPTN], $family,);

    if (!$family instanceof Family) Reply::_error('family_not_found');

    if ($status->getToken() != null) {
        $result = Status::_get(Criteria::TOKEN, $status->getToken());
        if (!$result instanceof Status) Reply::_error('status_not_found');

        $result->setPosition($status->getPosition());
        $result->setLabel($status->getLabel());
        $result->setReference($status->getReference());
        $result->setDescription($status->getDescription());
        $resultt = $result->save();
        if ($resultt instanceof Status) {
            Reply::_success($resultt->toArray());
        } else {
            Reply::_error($resultt);
        }

    } else {


        $result = $status->save();
        if ($result instanceof Status) {
            Reply::_success($result->toArray());
        } else {
            Reply::_error($result);
        }
    }
} catch (Exception $exception) {
    Reply::_exception($exception);
}

