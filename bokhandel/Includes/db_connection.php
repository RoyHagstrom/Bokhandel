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
    define('DB_CONNECTION_TIMEOUT', 3);

    function getDatabaseConnection() {
        $hosts = array_reverse(DB_HOSTS);
        $conn = null;
        foreach ($hosts as $host) {
            $conn = connectToDatabase($host);
            if ($conn) {
                break;
            }
        }
        return $conn;
    }

    function connectToDatabase($host) {
        $conn = new mysqli(
            $host,
            DB_USERNAME,
            DB_PASSWORD,
            DB_DATABASE,
            DB_PORT
        );
        if ($conn->connect_error) {
            return null;
        }
        $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, DB_CONNECTION_TIMEOUT);
        $conn->set_charset('utf8mb4');
        return $conn;
    }


    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }


    $conn = getDatabaseConnection();
    if (!$conn) {
        throw new Exception("Could not get database connection");
    }
    $user = new USER($conn);


