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
            namefr VARCHAR(255) NOT NULL UNIQUE, 
            nameen VARCHAR(255) NOT NULL UNIQUE, 
            alpha2 VARCHAR(255) NOT NULL UNIQUE, 
            alpha3 VARCHAR(100) NOT NULL UNIQUE, 
            dialcode VARCHAR(100) NOT NULL UNIQUE,   
            created TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ); 
    ",
        'city' => "
        CREATE TABLE IF NOT EXISTS city (
            id INT AUTO_INCREMENT PRIMARY KEY,
            token VARCHAR(100) NOT NULL UNIQUE, 
            city VARCHAR(100) NOT NULL UNIQUE,    
            country INT NOT NULL,
            FOREIGN KEY (country) REFERENCES country(id) ON DELETE CASCADE  
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

