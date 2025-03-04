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

    Criteria::_formRequiredCheck([TOKEN, NAMEEN, NAMEFR, ALPHA2, ALPHA3, DIAL], $data);
    $country = new Country(null, $data[TOKEN], $data[NAMEFR], $data[NAMEEN], $data[ALPHA2], $data[ALPHA3], $data[DIAL],);
    if ($country->getToken() != null) {
        $result = Country::_get(Criteria::TOKEN, $country->getToken());
        if (!$result instanceof Country) Reply::_error('no_country_found');

        $result->setNameen($country->getNameen());
        $result->setNamefr($country->getNamefr());
        $result->setAlpha2($country->getAlpha2());
        $result->setAlpha3($country->getAlpha3());
        $result->setDialcode($country->getDialcode());
        $resultt = $result->save();
        if ($resultt instanceof Country) {
            Reply::_success($resultt->toArray());
        } else {
            Reply::_error($resultt);
        }

    } else {

        $result = $country->save();
        if ($result instanceof Country) {
            Reply::_success($result->toArray());
        } else {
            Reply::_error($result);
        }


    }


} catch (Exception $exception) {
    Reply::_exception($exception);
}

