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
        $conn = null;
        foreach (DB_HOSTS as $host) {
            $conn = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
            if (!$conn->connect_error) {
                $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, DB_CONNECTION_TIMEOUT);
                return $conn;
            }
            $conn->close();
            $conn = null;
        }
        return null;
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $conn = getDatabaseConnection();
    if (!$conn) {
        throw new Exception("Could not get database connection");
    }
    $user = new USER($conn);

