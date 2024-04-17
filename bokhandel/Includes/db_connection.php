<?php

if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
    die('We don\'t have mysqli!!!');
}

define('DB_HOSTS', [
    'primary' => 'novatest.ddns.net',
    'local' => '192.168.1.111',
    'localhost' => '192.168.128.2'
]);
define('DB_PORT', '3306');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'bokhandel');

function connectToDb($host) {
    try {
        $conn = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        if ($conn->connect_error) {
            throw new mysqli_sql_exception($conn->connect_error);
        }
        return $conn;
    } catch (mysqli_sql_exception $e) {
        throw $e;
    }
}

function getDatabaseConnection() {
    try {
        return connectToDb(DB_HOSTS['primary']);
    } catch (mysqli_sql_exception $e) {
        try {
            return connectToDb(DB_HOSTS['local']);
        } catch (mysqli_sql_exception $e) {
            return connectToDb(DB_HOSTS['localhost']);
        }
    }
}

session_start();

try {
    $conn = getDatabaseConnection();
    $user = new USER($conn);
} catch (mysqli_sql_exception $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit;
}

