<?php
define('DB_HOST_PRIMARY', 'novatest.ddns.net');
define('DB_HOST_LOCAL', '192.168.1.111');
define('DB_PORT', '3306');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'bokhandel');

try {
    $conn = new mysqli(DB_HOST_PRIMARY, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection to primary server failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    try {
        $conn = new mysqli(DB_HOST_LOCAL, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        if ($conn->connect_error) {
            throw new Exception("Connection to localhost failed: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        die("Failed to connect to database: " . $e->getMessage());
    }
}
?>
