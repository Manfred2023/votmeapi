<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 29/09/2025, 10:39
 *   Last modified: 29/09/2025, 10:39
 *
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // Database connection
    $pdo = Database::getConnection();

    // Repositories
    $countryRepository = new CountryRepository($pdo);
   // $userRepository = new UserRepository($pdo);

    // Services
    $countryService = new CountryService($countryRepository);
   // $authService = new AuthService($userRepository);

    // Middleware
   // $authMiddleware = new AuthMiddleware($authService);

    // Controllers
    $countryController = new CountryController($countryService);
   // $authController = new AuthController($authService);

    // Routing
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');

    // Routes publiques (pas d'authentification)
    if ($method === 'POST' && $uri === '/api/auth/register') {
       // $authController->register();
    }
    elseif ($method === 'POST' && $uri === '/api/auth/login') {
       // $authController->login();
    }
    // Routes protégées (authentification requise)
    elseif ($method === 'GET' && $uri === '/api/auth/me') {
       // $user = $authMiddleware->handle();
       // $authController->me($user);
    }
    elseif ($method === 'GET' && $uri === '/api/countries') {
       // $authMiddleware->handle();
        $countryController->index();
    }
    elseif ($method === 'GET' && preg_match('#^/api/countries/(\d+)$#', $uri, $matches)) {
       // $authMiddleware->handle();
        $countryController->show((int) $matches[1]);
    }
    elseif ($method === 'POST' && $uri === '/api/countries') {
       // $authMiddleware->handle();
        $countryController->store();
    }
    elseif ($method === 'PUT' && preg_match('#^/api/countries/(\d+)$#', $uri, $matches)) {
       // $authMiddleware->handle();
        $countryController->update((int) $matches[1]);
    }
    elseif ($method === 'DELETE' && preg_match('#^/api/countries/(\d+)$#', $uri, $matches)) {
      //   $authMiddleware->handle();
        $countryController->destroy((int) $matches[1]);
    }
    else {
        JsonResponse::error('Route not found', 404);
    }

} catch (Exception $e) {
    JsonResponse::error('Internal server error: ' . $e->getMessage(), 500);
}

