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

    Criteria::_formRequiredCheck([CNDDT, CTGRY], $data);

    $candidat = Candidate::_get(Criteria::TOKEN, $data[CNDDT]);
    if (!$candidat instanceof Candidate) Reply::_error('candidat_not_found');

    $category = Competcategory::_get(Criteria::TOKEN, $data[CTGRY]);
    if (!$category instanceof Competcategory) Reply::_error('category_not_found');


    $competcategorycandidat = new Competcategorycandidat(null, null, $candidat, $category);


    if ($competcategorycandidat->getToken() != null) {
        $result = Candidate::_get(Criteria::TOKEN, $competcategorycandidat->getToken());
        if (!$result instanceof Candidate) Reply::_error('nothing_found');

    } else {
        $result = $competcategorycandidat->save();
        if ($result instanceof Competcategorycandidat)
            Reply::_success($result->toArray());
    }
} catch (Exception $exception) {
    Reply::_exception($exception);
}

