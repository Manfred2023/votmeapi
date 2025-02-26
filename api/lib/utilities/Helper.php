<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

class Helper
{
    static public function isEmailFormatValid(String $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    static public function isCameroonianPhoneNumber($phoneNumber) {
        $pattern = "/^6[5789][0-9]{7}$/";
        return preg_match($pattern, $phoneNumber) === 1;
    }

    static public function generateApkCode($appName, $env): string
    {
        // Initiales de l'application
        $app = strtoupper(substr($appName, 0, 3));
        // Code de l'environnement
        $environment = strtoupper(substr($env, 0, 1));
        // Date actuelle (année et mois)
        $date = date('ym'); // Exemple : 2412
        // Séquence aléatoire de 4 caractères
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        // Code final
        return $app . $environment . $date . $random;
    }
    static public function isValidUuid($uuid) {
        // Regex pour matcher le format UUID v4
        $uuidRegex = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

        // Retourne true si l'UUID est valide, false sinon
        return preg_match($uuidRegex, $uuid) === 1;
    }

    static public function  CreateReference($input) {

        $transformedValue = preg_replace("/[\s']/", '_', $input);

        $transformedValue = strtolower($transformedValue);

        return substr($transformedValue, 0, 50);
    }



}