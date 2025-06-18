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

    Criteria::_formRequiredCheck([TOKEN, THEME,  FREE, STARTED, ENDED, STRCTR, PROMOTR], $data);
    $started = new DateTime($data[STARTED]);
    //$formattedDate = $started->format('Y-m-d H:i:s');
    $ended = new DateTime($data[ENDED]);


    $structure = Structure::_get(Criteria::TOKEN, $data[STRCTR]);
    if (!$structure instanceof Structure) Reply::_error("structure_not_found");

    $user = User::_get(Criteria::TOKEN, $data[PROMOTR]);
    if (!$structure instanceof Structure) Reply::_error("user_not_found");
    //if($user->getProfile()->getLabel() == 'admin')
    $status = Status::_get(Criteria::TOKEN, 100001);

    $competition = new Competition(null, $data[TOKEN], $data[THEME], "https://votme.fredcode.net/uploads/image/img_6852c9e868090.png", $data[FREE], $started, $ended, $structure, $user, $status);


    if ($competition->getToken() != null) {
        $result = Competition::_get(Criteria::TOKEN, $competition->getToken());
        if (!$result instanceof Competition) Reply::_error('competition_not_found');
        $result->setTheme($competition->getTheme());
        $result->setLogo($competition->getLogo());
        $result->setStarted($competition->getStarted());
        $result->setEnded($competition->getEnded());
        $result->setStructure($competition->getStructure());
        $resultt = $result->save();
        if ($resultt instanceof Competition)
            Reply::_success($resultt->toArray());

    } else {
        $competitions = $competition->save();
        if ($competitions instanceof Competition)
            Reply::_success($competitions->toArray());
    }
} catch (Exception $exception) {
    Reply::_exception($exception);
}

