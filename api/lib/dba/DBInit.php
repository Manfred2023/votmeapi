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
    ",
        'contact' => "
        CREATE TABLE IF NOT EXISTS contact (
            id INT AUTO_INCREMENT PRIMARY KEY,
            token VARCHAR(100) NOT NULL UNIQUE, 
            gender VARCHAR(1) NOT NULL CHECK (gender IN ('m', 'f')),
            firstname VARCHAR(150) NULL ,
            lastname VARCHAR(150) NOT NULL ,
            email VARCHAR(255) NULL UNIQUE ,
            location VARCHAR(255) NOT NULL ,
            city INT NOT NULL,
            mobile VARCHAR(255) NOT NULL UNIQUE,
            lang VARCHAR(255) NOT NULL ,
            created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (city) REFERENCES city(id) ON DELETE CASCADE
        );
    ",
        'profile' => "
    CREATE TABLE IF NOT EXISTS profile (
        id INT AUTO_INCREMENT PRIMARY KEY,
        token VARCHAR(100) NOT NULL UNIQUE,  
        label VARCHAR(100) NOT NULL,
        description VARCHAR(150) NULL, 
        created TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
    );
",
        'user' => "
        CREATE TABLE IF NOT EXISTS user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        token VARCHAR(100) NOT NULL UNIQUE,   
        mobile VARCHAR(255) NOT NULL UNIQUE,
        code VARCHAR(255) NOT NULL,
        blocked BOOLEAN DEFAULT FALSE,
        contact INT NOT NULL, 
        createdby INT NULL,
        profile INT NOT NULL,
        created TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
        FOREIGN KEY (contact) REFERENCES contact(id) ON DELETE CASCADE,
        FOREIGN KEY (createdby) REFERENCES user(id) ON DELETE SET NULL,
        FOREIGN KEY (profile) REFERENCES profile(id) ON DELETE CASCADE
    ); 
 ",
        'structure' => "
        CREATE TABLE IF NOT EXISTS structure (
        id INT AUTO_INCREMENT PRIMARY KEY,
        token VARCHAR(100) NOT NULL UNIQUE,   
        name VARCHAR(255) NOT NULL,
        mobile VARCHAR(255) NOT NULL UNIQUE,
        city INT NOT NULL, 
        manager INT NOT NULL,  
        created TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
        FOREIGN KEY (manager) REFERENCES contact(id) ON DELETE CASCADE, 
        FOREIGN KEY (city) REFERENCES city(id) ON DELETE CASCADE
    ); 
 ",
        'competition' => "
        CREATE TABLE IF NOT EXISTS competition (
        id INT AUTO_INCREMENT PRIMARY KEY,
        token VARCHAR(100) NOT NULL UNIQUE,   
        theme VARCHAR(255) NOT NULL,
        logo VARCHAR(255) NOT NULL ,
        free BOOLEAN DEFAULT FALSE,
        started DATETIME NOT NULL,  
        ended DATETIME NOT NULL, 
        structure INT NOT NULL,
        promoter INT NOT NULL,
        created TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
        FOREIGN KEY (structure) REFERENCES structure(id) ON DELETE CASCADE, 
        FOREIGN KEY (promoter) REFERENCES user(id) ON DELETE CASCADE
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

