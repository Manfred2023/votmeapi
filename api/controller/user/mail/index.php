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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Reply::_error("Only PUT method is allowed", code: 405);
    exit();
}
validateBearerToken();
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    Criteria::_formRequiredCheck([EMAIL], $data);
    $date = new DateTime('now', new DateTimeZone('UTC'));
    $date->modify('+1 hour');

    if (!Helper::isEmailFormatValid($data[EMAIL]))
        Reply::_error("bad_email_format");


    $contact = Contact::_get(Criteria::EMAIL, $data[EMAIL]);
    if (!$contact instanceof Contact) Reply::_error('email_dont_exist', code: 401);

    $user = User::_get(Criteria::MOBILE, $contact->getMobile());
    if (!$user instanceof User) Reply::_error('this_user_dont_exist', code: 401);

    $token = LoginToken::generateLoginCode();

    $alreadyExiste = LoginToken::_get(Criteria::USER, $user->getId());

    if($alreadyExiste != null) {
        $alreadyExiste->setToken($token);
        $alreadyExiste->setDatetime($date->format('Y-m-d H:i:s'));
        $alreadyExiste->save();
    }
    else {
        $loginToken = new LoginToken(null,$token,$user,$date->format('Y-m-d H:i:s'));
        $loginToken->save();
    }

    sendLoginTokenMail($user->getContact()->getEmail(), $token,$date->format('Y-m-d H:i:s'),$user->getContact()->getLastname());
    Reply::_success("successful");


} catch (Exception $exception) {
    Reply::_exception($exception);
}