<?php
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
    $conn = mysqli_connect($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
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

$conn = @getDatabaseConnection();
if (!$conn) {
    throw new Exception('Could not connect to database');
}
$user = @new USER($conn);

