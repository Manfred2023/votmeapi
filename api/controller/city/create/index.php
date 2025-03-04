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

    Criteria::_formRequiredCheck([TOKEN, CITY, COUNTRY], $data);
    $country = Country::_get(Criteria::TOKEN, $data[COUNTRY]);
    if (!$country instanceof Country) Reply::_error('Country_not_found');
    $city = new City(null, $data[TOKEN], $data[CITY], $country,);

    if ($city->getToken() != null) {
        $result = City::_get(Criteria::TOKEN, $city->getToken());
        if (!$result instanceof City) Reply::_error('City_not_found');

        $result->setCity($city->getCity());
        $result->setCountry($country);
        $resultt = $result->save();
        if ($resultt instanceof City) {
            Reply::_success($resultt->toArray());
        } else {
            Reply::_error($resultt);
        }

    } else {

        $result = $city->save();
        if ($result instanceof City) {
            Reply::_success($result->toArray());
        } else {
            Reply::_error($result);
        }
    }
} catch (Exception $exception) {
    Reply::_exception($exception);
}

