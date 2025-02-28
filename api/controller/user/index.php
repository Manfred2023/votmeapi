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
try {
    Reply::_success(User::_list());
} catch (Exception $e) {
    $contacts = [];
}