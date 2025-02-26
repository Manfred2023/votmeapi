<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */


require 'DBConn.php';

class DatabaseSchema {
    public static function createTables() {
        // Table 'phone'
        $phone = R::dispense('phone');
        $phone->name = 'Exemple Phone';
        $phone->marque = 'Exemple Marque';
        $phone->space = 128;
        R::store($phone);

            }
}

