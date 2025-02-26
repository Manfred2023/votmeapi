<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

function validateBearerToken(): bool
{
    // Vérification de l'existence du header Authorization
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        Reply::_error("No HTTP_AUTHORIZATION header found", code: 401);
        error_log("No HTTP_AUTHORIZATION header found");
        return false;
    }

    // Extraction du token Bearer du header Authorization
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];

    // Vérification si le header commence par "Bearer "
    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        Reply::_error("Invalid Bearer token format", code: 401);
        error_log("Invalid Bearer token format");
        return false;
    }

    $token = $matches[1];
    return validateToken($token);
}

function validateToken(string $token): bool
{
    try {
        // Créer une instance de Jwt avec la même clé que celle utilisée pour l'encodage
        $jwt = new Jwt('c2f07e2c-5a51-4368-970d-26fdd192eae0', 'BuildTrack');

        // Décoder le token
        $decoded = $jwt->decode($token);

        // Si decode() retourne null, le token est invalide
        if ($decoded === null) {
            Reply::_error("Token validation failed: invalid token",code: 401);
            error_log("Token validation failed: invalid token");
            return false;
        }

        // Vérifications supplémentaires si nécessaire
        if ($decoded['app'] !== 'BuildTrack') {
            Reply::_error("Token validation failed: invalid app",code: 401);
            error_log("Token validation failed: invalid app");
            return false;
        }

        return true;
    } catch (Exception $e) {
        error_log("Token validation error: " . $e->getMessage());
        return false;
    }
}