<?php

    define('DB_HOSTS', [
        'primary' => '172.22.0.2',
        'local' => '192.168.1.111',
        'network' => 'novatest.ddns.net'
    ]);
    define('DB_PORT', '3306');
    define('DB_USERNAME', 'test');
    define('DB_PASSWORD', 'test');
    define('DB_DATABASE', 'bokhandel');
    define('DB_CONNECTION_TIMEOUT', 5);

    function getDatabaseConnection() {
        $hosts = array_reverse(DB_HOSTS);
        $conn = null;
        foreach ($hosts as $host) {
            $conn = connectToDatabase($host);
            if ($conn) {
                break;
            }
        }
        if (!$conn) {
            throw new Exception("Could not get database connection");
        }
        return $conn;
    }

    function connectToDatabase($host) {
        $conn = new mysqli(
            $host,
            DB_USERNAME,
            DB_PASSWORD,
            DB_DATABASE,
            DB_PORT,
            NULL,
            MYSQLI_CLIENT_FOUND_ROWS
        );
        if ($conn->connect_error) {
            throw new mysqli_sql_exception($conn->connect_error);
        }
        $conn->set_charset('utf8mb4');
        return $conn;
    }


    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }


    $conn = getDatabaseConnection();
    $user = new USER($conn);

