<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 26/09/2025, 20:08
 *   Last modified: 26/09/2025, 20:08
 *
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
            R::setup('mysql:host=localhost:3306;dbname=nege5774_sandbox_votme',
                'nege5774_manfred', 'Manfred500.');

        } catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
    /*  static public function _conn(): void
      {
          try {

              R::setup('mysql:host=localhost:3306;dbname=' . getenv('DB_NAME'),
                  getenv('DB_USERNAME'), getenv('DB_PASSWORD'));

          } catch (Exception $exception) {
              die($exception->getMessage());
          }
      }*/


}