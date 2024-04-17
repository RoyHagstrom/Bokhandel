<?php

if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
    die('We don\'t have mysqli!!!' . PHP_EOL);
}

define('DB_HOSTS', [
    'primary' => 'novatest.ddns.net',
    'local' => '192.168.1.111',
    'localhost' => '172.22.0.2'
]);
define('DB_PORT', '3306');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'bokhandel');

function connectToDb($host) {
    $conn = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function getDatabaseConnection() {
    try {
        return connectToDb(DB_HOSTS['primary']);
    } catch (Exception $e) {
        try {
            return connectToDb(DB_HOSTS['local']);
        } catch (Exception $e) {
            return connectToDb(DB_HOSTS['localhost']);
        }
    }
}

ob_start();
session_start();
$conn = getDatabaseConnection();
$user = new USER($conn);

