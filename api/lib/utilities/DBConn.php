<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

class DBConn
{

        /**
         * MySQL DB Connector
         * @return void
         */
      static public function _conn(): void
      {
         try {
            R::setup('mysql:host=localhost:3306;dbname=nege5774_votme',
               'nege5774_manfred', 'Manfred500.');

           } catch (Exception $exception) {
               die($exception->getMessage());
           }
      }


}