<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 01/10/2025, 17:39
 *   Last modified: 01/10/2025, 17:39
 *
 */

use JetBrains\PhpStorm\NoReturn;

class JsonResponse
{
    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return void
     */
    public static function success($data = null, string $message = 'Success', int $code = 200): void
    {
        self::send([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @param $errors
     * @return void
     */
    public static function error(string $message, int $code = 400, $errors = null): void
    {
        self::send([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * @param array $data
     * @param int $code
     * @return void
     */
    #[NoReturn] private static function send(array $data, int $code): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}

