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

    Criteria::_formRequiredCheck([CTGRY, USER], $data);

    $category = Competcategory::_get(Criteria::TOKEN, $data[CTGRY]);
    if (!$category instanceof Competcategory) Reply::_error('category_not_found');

    $user = User::_get(Criteria::TOKEN, $data[USER]);
    if (!$user instanceof User) Reply::_error('user_not_found');

    if ($user->getToken() != $category->getCompetition()->getPromoter()->getToken()) {
        Reply::_error('you_are_not_authorized_to_do_this_action', 401);
    }

    if ($category->getCompetition()->getStatus()->getToken() != 100001) Reply::_error('the_status_does_not_allow_to_continue');
    $category->delete();
    Reply::_success('operation_was_good');

} catch (Exception $exception) {
    Reply::_exception($exception);
}

