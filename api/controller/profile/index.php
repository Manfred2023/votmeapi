<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

require dirname(__DIR__, 2) . '/conf.php';
require_once dirname(__DIR__, 2) . '/lib/utilities/Auth.php';

if (!validateBearerToken()) {
    Reply::_error("Invalid or missing Bearer token", code: 401);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    Reply::_success(Profile::_list());
} catch (Exception $e) {
    $contacts = [];
}