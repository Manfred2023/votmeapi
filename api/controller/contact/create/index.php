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

    Criteria::_formRequiredCheck([TOKEN, FRSTNM, LSTNM, GNDR, EMAIL, LCTN, CITY, MBLE, LANG], $data);

    $city = City::_get(Criteria::TOKEN, $data[CITY]);

    if (!$city instanceof City) Reply::_error('City_not_found');

    $contact = new Contact(null, $data[TOKEN], $data[GNDR], $data[FRSTNM], $data[LSTNM], $data[EMAIL], $data[LCTN], $city, $data[MBLE], $data[LANG],);

    if (!Helper::isEmailFormatValid($contact->getEmail()))
        Reply::_error("Email_is_not_good");

    if (!Helper::isCameroonianPhoneNumber($contact->getMobile()))
        Reply::_error("mobile_is_not_good");

    if ($contact->getToken() != null) {
        $result = Contact::_get(Criteria::TOKEN, $contact->getToken());
        if (!$result instanceof Contact) Reply::_error('Contact_not_found');
        $result->setFirstname($contact->getFirstname());
        $result->setLastname($contact->getLastname());
        $result->setGender($contact->getGender());
        $result->setEmail($contact->getEmail());
        $result->setCity($contact->getCity());
        $result->setMobile($contact->getMobile());
        $result->setLang($contact->getLang());
        $resultt = $result->save();
        if ($resultt instanceof Contact)
            Reply::_success($resultt->toArray());

    } else {
        $result = $contact->save();
        if ($result instanceof Contact)
            Reply::_success($result->toArray());
    }
} catch (Exception $exception) {
    Reply::_exception($exception);
}

