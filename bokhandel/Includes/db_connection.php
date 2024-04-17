<?php
define('DB_HOSTS', ['primary' => 'novatest.ddns.net', 'local' => '192.168.1.111', 'network' => '172.22.0.2']);
define('DB_PORT', '3306');
define('DB_CREDENTIALS', ['test', 'test']);
define('DB_DATABASE', 'bokhandel');

$conn = null;
$hosts = DB_HOSTS;
$credentials = DB_CREDENTIALS;
$database = DB_DATABASE;

function connectToDb()
{
    global $conn, $hosts, $credentials, $database;
    if ($conn === null) {
        foreach ($hosts as $host) {
            $conn = new mysqli($host, $credentials[0], $credentials[1], $database, DB_PORT);
            if (!$conn->connect_error) {
                break;
            }
        }
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
    }
    return $conn;
}

session_start();
$conn = connectToDb();
$user = new USER($conn);