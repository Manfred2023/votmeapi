<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

require dirname(__DIR__, 3) . '/conf.php';
require_once dirname(__DIR__, 3) . '/lib/utilities/Auth.php';

if (!validateBearerToken()) {
    Reply::_error("Invalid or missing Bearer token", code: 401);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Reply::_error("Only POST method is allowed", code: 405);
    exit();
}
try {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    Criteria::_formRequiredCheck([USER ], $data);

    $userCheck = User::_get(Criteria::TOKEN, $data[USER]);


    if (!$userCheck instanceof User) {
        Reply::_error("User_not_found", code: 404);}

    if($userCheck->getCreatedby() != null){
        Reply::_success(User::getAllAssociate($userCheck));
    }else{
        Reply::_success([]);
    }

} catch (Exception $e) {
    $contacts = [];
}