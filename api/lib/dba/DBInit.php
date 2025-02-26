<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

class DBInit
{
    /**
     * List of table creation SQL statements
     */
    private static $tables = [
        'country' => "
        CREATE TABLE IF NOT EXISTS country (
            id INT AUTO_INCREMENT PRIMARY KEY,
            token VARCHAR(100) NOT NULL UNIQUE, 
            namefr VARCHAR(255) NOT NULL, 
            nameen VARCHAR(255) NOT NULL, 
            alpha2 VARCHAR(255) NOT NULL, 
            alpha3 VARCHAR(100) NOT NULL, 
            dialcode VARCHAR(100) NOT NULL,   
            created TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ); 
    "
    ];


    /**
     * Initialize tables
     */
    public static function initialize(): void
    {
        foreach (self::$tables as $table => $sql) {
            try {
                R::exec($sql);
                //echo "Table '$table' has been created or already exists.\n";
            } catch (Exception $e) {
                //echo "Error creating table '$table': " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * @return string[]
     */
    public static function getTables(): array
    {
        return self::$tables;
    }
}

