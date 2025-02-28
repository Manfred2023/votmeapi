<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

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

    Criteria::_formRequiredCheck([TOKEN, NAME, MBLE, CITY, MANAGR], $data);

    if (!Helper::isCameroonianPhoneNumber($data[MBLE]))
        Reply::_error("mobile_is_not_good");

    $city = City::_get(Criteria::TOKEN, $data[CITY]);
    if (!($city instanceof City)) {
        Reply::_error("City_not_found");
        exit();
    }
    $manager = Contact::_get(Criteria::TOKEN, $data[MANAGR]);
    if (!($manager instanceof Contact)) {
        Reply::_error("Contact_not_found");
        exit();
    }

    $rename = new Structure(null, $data[TOKEN], $data[NAME], $data[MBLE], $city, $manager);


    if ($rename->getToken() != null) {
        $result = Structure::_get(Criteria::TOKEN, $rename->getToken());

        if (!($result instanceof Structure)) {
            Reply::_error('structure_not_exist');
        }

        $result->setName($rename->getName());
        $result->setMobile($rename->getMobile());
        $result->setCity($rename->getCity());
        $result->save();

        Reply::_success($result->toArray());
    } else {

        $resultt = $rename->save();
        if ($resultt instanceof Structure) {
            Reply::_success($resultt->toArray());
        }
    }


} catch (Exception $exception) {
    Reply::_exception($exception);
}