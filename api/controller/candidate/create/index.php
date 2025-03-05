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

    Criteria::_formRequiredCheck([TOKEN, PHOTO, DSCRPTN, SLOGAN, CONFIRMED, CNTCT, COMPTTN, CRTDBY], $data);

    $contact = Contact::_get(Criteria::TOKEN, $data[CNTCT]);
    if (!$contact instanceof Contact) Reply::_error('contact_not_found');

    $competition = Competition::_get(Criteria::TOKEN, $data[COMPTTN]);
    if (!$competition instanceof Competition) Reply::_error('competition_not_found');

    $user = User::_get(Criteria::TOKEN, $data[CRTDBY]);
    if (!$user instanceof User) Reply::_error('user_not_found');

    $candidate = new Candidate(null, $data[TOKEN], $data[PHOTO], $data[DSCRPTN], $data[SLOGAN], $data[CONFIRMED], $contact, $competition, $user);


    if ($candidate->getToken() != null) {
        $result = Candidate::_get(Criteria::TOKEN, $candidate->getToken());
        if (!$result instanceof Candidate) Reply::_error('candidate_not_found');
        $result->setPhoto($candidate->getPhoto());
        $result->setDescription($candidate->getDescription());
        $result->setSlogan($candidate->getSlogan());
        $result->setConfirmed($candidate->isConfirmed());
        $result->setContact($candidate->getContact());
        $resultt = $result->save();
        if ($resultt instanceof Candidate)
            Reply::_success($resultt->toArray());
    } else {
        $result = $candidate->save();
        if ($result instanceof Candidate)
            Reply::_success($result->toArray());
    }
} catch (Exception $exception) {
    Reply::_exception($exception);
}

