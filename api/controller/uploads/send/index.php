<?php
require dirname(__DIR__, 3) . '/conf.php';
require_once dirname(__DIR__, 3) . '/lib/utilities/Auth.php';

/*if (!validateBearerToken()) {
    Reply::_error("Invalid or missing Bearer token", code: 401);
    exit();
}*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Reply::_error("Only POST method is allowed", code: 405);
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    if (!isset($_FILES['image'])) {
        Reply::_error("No image file received", code: 400);
        exit();
    }

    $file = $_FILES['image'];
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));


    $maxSize = 10 * 1024 * 1024; // 10 Mo
    if ($file['size'] > $maxSize) {
        Reply::_error("bad_size", code: 413);
        exit();
    }
    
    if (!in_array($extension, $allowed)) {
        Reply::_error("Extension non autorisée", code: 415);
        exit();
    }

    $uploadDir = dirname(__DIR__, 1) . '/image/';
    $fileName = uniqid('img_') . '.' . $extension;
    $filePath = $uploadDir . $fileName;

        if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $filePath)) {

        $baseUrl = "https://votme.fredcode.net/uploads/image/" . $fileName;
        Reply::_successImage($baseUrl);
    } else {
        Reply::_error("Erreur lors du déplacement du fichier", code: 500);
    }

} catch (Exception $e) {
    Reply::_error($e->getMessage(), code: 500);
}
