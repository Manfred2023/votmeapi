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
error_reporting(E_ALL);

ini_set('display_errors', 1);
validateBearerToken();
try {
    Reply::_success(Category::_list());
} catch (Exception $e) {
    $contacts = [];
}