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

    Criteria::_formRequiredCheck([TOKEN, COMPTTN, CTGRY], $data);

    $competition = Competition::_get(Criteria::TOKEN, $data[COMPTTN]);
    if (!$competition instanceof Competition) Reply::_error('competition_not_found');

    $category = Category::_get(Criteria::TOKEN, $data[CTGRY]);
    if (!$category instanceof Category) Reply::_error('category_not_found');

    $competition = new Competcategory(null, $data[TOKEN], $competition, $category,);


    if ($competition->getToken() != null) {
        $result = Competcategory::_get(Criteria::TOKEN, $competition->getToken());
        if (!$result instanceof Competcategory) Reply::_error('nothing_found');
        $result->setCompetition($competition->getCompetition());
        $result->setCategory($competition->getCategory());
        $resultt = $result->save();
        if ($resultt instanceof Competcategory)
            Reply::_success($resultt->toArray());

    } else {
        $competitions = $competition->save();
        if ($competitions instanceof Competcategory)
            Reply::_success($competitions->toArray());
    }
} catch (Exception $exception) {
    Reply::_exception($exception);
}

