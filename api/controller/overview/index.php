<?php

require dirname(__DIR__, 2) . '/conf.php';
require_once dirname(__DIR__, 2) . '/lib/utilities/Auth.php';
validateBearerToken();
try {
    $competition = Competition::_list();
    $candidate = Candidate::_list();
    $contact = Contact::_list();
    $structure = Structure::_list();
    $user = User::_list();
    Reply::_success(
        [
            "competition" => count($competition),
            "candidait" => count($candidate),
            "contact" => count($contact),
            "structure" => count($structure),
            "promoteur" => count($user),
        ]
    );
} catch (Exception $e) {
    $contacts = [];
}