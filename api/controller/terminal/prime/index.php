<?php

if (!@require realpath(dirname(__DIR__, 3)) . '/conf.php')
    http_response_code(403);
require_once __DIR__ . '/../../../../vendor/autoload.php';

$encryptedHex = "393ea2db13a07055dd08d05ff87fe8e4"; // encryptedData
$ivHex = "61bf4785cb9c48091178028721ce5825";  // iv (qui est la clé dans ton cas)
 
$key = hex2bin($ivHex);
$encrypted = hex2bin($encryptedHex);

// Déchiffrement
$decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $key);
/*$encryptedHex = "393ea2db13a07055dd08d05ff87fe8e4";
$ivHex = "61bf4785cb9c48091178028721ce5825";

$keyHex = "31323334353637383930414243444546";

$encrypted = hex2bin($encryptedHex);
$iv = hex2bin($ivHex);
$key = hex2bin($keyHex);

// Déchiffrement
$decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);*/

// Affichage
if ($decrypted === false) {
    Reply::_error("Erreur lors du déchiffrement.\n");
    echo "Erreur lors du déchiffrement.\n";
} else {
    Reply::_success("Texte déchiffré : " . $decrypted . "\n");

}
?>
