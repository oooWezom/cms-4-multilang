<?php
ini_set('display_errors', 'on'); // Display all errors on screen
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
header("Cache-Control: public");
header("Expires: " . date("r", time() + 3600));
header('Content-Type: text/html; charset=UTF-8');
ob_start();
define('MULTI_LANGUAGE', true); // Enable multi language site.
// These parameters are only needed for the multilanguage version on subdomains
define('SITE_DOMAIN', 'multilang.wezom.net'); // Clear site domain name, without sub domains. Need just fo multi language links.
session_set_cookie_params(0, '/', '.' . SITE_DOMAIN); // Set empty cookie param for cross sub domain save cookie.

@session_start();
//    define('DS', DIRECTORY_SEPARATOR);
define('DS', '/');
define('HOST', dirname(__FILE__)); // Root path
define('APPLICATION', 'frontend'); // Choose application - backend|frontend
define('PROFILER', false); // On/off profiler
define('START_TIME', microtime(true)); // For profiler. Don't touch!
define('START_MEMORY', memory_get_usage()); // For profiler. Don't touch!

require_once 'loader.php';

Core\Route::factory()->execute();
\Profiler::view();
