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

    Criteria::_formRequiredCheck([TOKEN, MBLE, CODE, CNTCT, PRFLE, CRTDBY], $data);

    if (!Helper::isCameroonianPhoneNumber($data[MBLE]))
        Reply::_error("mobile_is_not_good");

    if (!Helper::isValidCode($data[CODE]))
        Reply::_error("code_invalid");

    if ($data[CRTDBY] != null) {
        $userCheck = User::_get(Criteria::TOKEN, $data[CRTDBY]);
        if (!$userCheck instanceof User) {
            Reply::_error("User_not_found", code: 404);
        } else {
            if ($userCheck->getProfile()->getLabel() == "associate") {
                Reply::_error('you_are_not_authorized_to_take_this_action');
            }
        }
    } else {
        $userCheck = null;
    }


    $profile = Profile::_get(Criteria::TOKEN, $data[PRFLE]);
    if (!($profile instanceof Profile)) {
        Reply::_error("Profile_not_found");
        exit();
    }

    $contact = Contact::_get(Criteria::TOKEN, $data[CNTCT]);
    if (!($contact instanceof Contact)) {
        Reply::_error("Contact_not_found");
        exit();
    }

    $user = new User(null, $data[TOKEN], $data[MBLE], $data[CODE], false, $contact, $userCheck, $profile, null);


    if ($user->getToken() != null) {
        $result = User::_get(Criteria::TOKEN, $user->getToken());

        if (!($result instanceof User)) {
            Reply::_error('user_not_exist');
        }

        $result->setCode($user->getCode());
        $result->setMobile($user->getMobile());
        $result->setProfile($user->getProfile());
        $result->save();

        Reply::_success($result->toArray());
    } else {

        $resultt = $user->save();
        if ($resultt instanceof User) {
            Reply::_success($resultt->toArray());
        }
    }


} catch (Exception $exception) {
    Reply::_exception($exception);
}