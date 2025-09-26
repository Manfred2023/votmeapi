<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

@session_start();
try {

    //header("Access-Control-Allow-Origin: https://enm.buildtrack-app.com");
    //header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, PUT,POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    //Header always set Access-Control-Allow-Credentials "true"

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        error_log('OPTIONS request handled.');
        http_response_code(204);
        exit();
    }

    # SET TIME ZONE
    date_default_timezone_set('Africa/Douala');

    # SET DEVELOPMENT MODE
    define('_DEVMODE_', false);
    ini_set('display_errors', _DEVMODE_);
    ini_set('display_startup_errors', _DEVMODE_);
    error_reporting(_DEVMODE_ ? E_ALL ^ E_NOTICE : 0);

    # COMMON EXTENSIONS
    define('_PHP', '.php');

    # BASIC PATHS
    define('_SEP', '/');
    define('_ROOT', dirname(__FILE__) . _SEP);
    define('_ROOTVEN', dirname(__FILE__) . DIRECTORY_SEPARATOR);

    # COMMON DIRECTORIES
    define('_VDRS', 'vendors');
    define('AUTO', 'autoload');
    define('_LIB', 'lib');
    define('_MODEL', 'models');
    define('_DBA', 'dba');
    define('_UTIL', 'utilities');
    define('_ALIAS', _ROOT . 'alias' . _PHP);
    define('_ENV', _ROOT . 'env' . _PHP);

    # ORM SET
    define('_ORM', _ROOT . _VDRS . _SEP . 'rb' . _PHP);
    if (!(file_exists(_ORM) || is_readable(_ORM)) || !@require(_ORM))
        throw new Exception ("unabl3 to reach to datda mapping: '" . _ORM . "'");

    # AUTOLOAD DB ADAPTERS
    spl_autoload_register(function ($Cn) {
        $Fn = _ROOT . join(_SEP, [_LIB, _DBA, $Cn . _PHP]);
        if (@file_exists($Fn)) include_once $Fn;
    });

    # AUTOLOAD MODELS CLASSES
    spl_autoload_register(function ($Cn) {
        $Fn = _ROOT . join(_SEP, [_LIB, _MODEL, $Cn . _PHP]);
        if (@file_exists($Fn)) include_once $Fn;
    });

    # AUTOLOAD UTILITIES CLASSES
    spl_autoload_register(function ($Cn) {
        $Fn = _ROOT . join(_SEP, [_LIB, _UTIL, $Cn . _PHP]);
        if (@file_exists($Fn)) include_once $Fn;
    });

   
    if (!@require _ALIAS)
        throw new Exception("Unable to read alias file!");

    if (!@require _ENV)
        throw new Exception("Unable to read environment file!");


    # Connect to DB
    DBConn::_conn();
    # Initialize the database
    DBInit::initialize();

} catch (Exception $exception) {
    die($exception->getMessage());
}