<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

class Jwt
{
    private string $key;
    private string $app;

    public function __construct(string $key, string $app)
    {
        $this->key = $key;
        $this->app = $app;
    }

    // Encode base64url
    private function base64URLEncode(string $text): string
    {
        return rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
    }

    // Decode base64url
    private function base64URLDecode(string $text): string
    {
        $base64 = strtr($text, '-_', '+/');
        $padding = strlen($base64) % 4;
        if ($padding) {
            $base64 .= str_repeat('=', 4 - $padding);
        }
        return base64_decode($base64);
    }

    // Crée le header du JWT
    private function createHeader(): string
    {
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        $jsonHeader = json_encode($header, JSON_THROW_ON_ERROR);
        return $this->base64URLEncode($jsonHeader);
    }

    // Crée le payload du JWT
    private function createPayload(string $clientId, int $validityInSeconds): string
    {
        $payload = [
            "app" => "BuildTrack",
            "cli" => $this->app,
            //"ssi" => $clientId, // Le clientId est maintenant correctement inclus
            "iat" => time(), // Issued At (le temps actuel)
            "exp" => time() + $validityInSeconds // Expiration Time
        ];

        $jsonPayload = json_encode($payload, JSON_THROW_ON_ERROR);
        return $this->base64URLEncode($jsonPayload);
    }

    // Crée la signature du JWT
    private function createSignature(string $header, string $payload): string
    {
        $data = "{$header}.{$payload}";
        $signature = hash_hmac("sha256", $data, $this->key, true);
        return $this->base64URLEncode($signature);
    }

    // Génère le JWT complet
    public function encode(string $clientId, int $validityInSeconds = 86400): string
    {
        $header = $this->createHeader();
        $payload = $this->createPayload($clientId, $validityInSeconds);
        $signature = $this->createSignature($header, $payload);

        return "{$header}.{$payload}.{$signature}";
    }

    /**
     * Décode et vérifie un JWT
     *
     * @param string $token Le token JWT à décoder
     * @return array|null Le payload décodé ou null si le token est invalide
     */
    public function decode(string $token): ?array
    {
        try {
            // Découpe le token en 3 parties : header, payload, signature
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                error_log("Invalid token format");
                return null;
            }

            [$header, $payload, $receivedSignature] = $parts;

            // Vérifie la signature
            $expectedSignature = $this->createSignature($header, $payload);
            if (!hash_equals($receivedSignature, $expectedSignature)) {
                error_log("Invalid signature");
                return null;
            }

            // Décode le payload
            $decodedPayload = json_decode($this->base64URLDecode($payload), true);
            if (!$decodedPayload) {
                error_log("Invalid payload format");
                return null;
            }

            // Vérifie l'expiration
            if (!isset($decodedPayload['exp']) || $decodedPayload['exp'] < time()) {
                error_log("Token expired");
                return null;
            }

            // Vérifie l'application
            if (!isset($decodedPayload['app']) || $decodedPayload['app'] !== "BuildTrack") {
                error_log("Invalid application");
                return null;
            }

            return $decodedPayload;

        } catch (Exception $e) {
            error_log("JWT decode error: " . $e->getMessage());
            return null;
        }
    }
}