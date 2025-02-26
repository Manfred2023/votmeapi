<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/24/25, 5:56 PM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/24/25, 5:56 PM
 */

class W
{
    /**
     * Vérifie si la requête entrante est de type GET.
     *
     * @return bool
     */
    public static function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Vérifie si la requête entrante est de type POST.
     *
     * @return bool
     */
    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Vérifie si la requête entrante est de type PUT.
     *
     * @return bool
     */
    public static function isPut(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }

    /**
     * Vérifie si la requête entrante est de type DELETE.
     *
     * @return bool
     */
    public static function isDelete(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }

    /**
     * Vérifie si la requête entrante correspond à un type spécifié.
     *
     * @param string $method Le type de requête attendu (GET, POST, PUT, DELETE, etc.).
     * @return bool
     */
    public static function isMethod(string $method): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === strtoupper($method);
    }

    /**
     * Récupère les données de la requête en fonction de son type.
     *
     * @return array|string
     */
    public static function getRequestData()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $_GET;
            case 'POST':
                return $_POST;
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents("php://input"), $data);
                return $data;
            default:
                return [];
        }
    }
}

?>
