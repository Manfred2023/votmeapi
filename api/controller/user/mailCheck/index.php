<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */


if (!@require realpath(dirname(__DIR__, 3)) . '/conf.php')
    http_response_code(403);

require_once dirname(__DIR__, 3) . '/lib/utilities/Auth.php';
require_once dirname(__DIR__, 3) . '/lib/utilities/Mailer.php';

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

    Criteria::_formRequiredCheck([TOKEN], $data);

    $date = new DateTime('now', new DateTimeZone('UTC'));
    $date->modify('+1 hour');

    $currentUser = LoginToken::_get(Criteria::TOKEN, $data[TOKEN]);
    if(!$currentUser instanceof LoginToken) Reply::_error('Bad_token');

    $user = clone $currentUser->getUser();
    $user->setLastlogin($date->format('Y-m-d H:i:s'));
    $user->save();
    Reply::_success($currentUser->getUser()->toArray());




} catch (Exception $exception) {
    Reply::_exception($exception);
}