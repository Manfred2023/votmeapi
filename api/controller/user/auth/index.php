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

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    Reply::_error("Only PUT method is allowed", code: 405);
    exit();
}
validateBearerToken();
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    Criteria::_formRequiredCheck([MBLE, CODE], $data);
    //$date = new DateTime('now', new DateTimeZone('Africa/Douala'))->format('Y-m-d H:i:s');
    $date = new DateTime('now', new DateTimeZone('UTC'));
    $date->modify('+1 hour');

    if (!Helper::isCameroonianPhoneNumber($data[MBLE]))
        Reply::_error("user_authentication_failed");

    if (!Helper::isValidCode($data[CODE]))
        Reply::_error("user_authentication_failed");

    $user = User::_get(Criteria::MOBILE, $data[MBLE]);
    if (!$user instanceof User) Reply::_error('user_authentication_failed', code: 401);

    if ($user->getCode() == $data[CODE]) {
        $user->setLastlogin($date->format('Y-m-d H:i:s'));
        $user->save();
        Reply::_success('user_authentication_successful');
    } else {
        Reply::_error("user_authentication_failed");
    }


} catch (Exception $exception) {
    Reply::_exception($exception);
}