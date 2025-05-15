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
validateBearerToken();
try {
    Reply::_success(Family::_list());
} catch (Exception $e) {
    $contacts = [];
}