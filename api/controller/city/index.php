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

error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    Reply::_success(City::_list());
} catch (Exception $e) {
    $contacts = [];
}