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
        $startTime = time();
        foreach (DB_HOSTS as $host) {
            $conn = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT, null, MYSQLI_CLIENT_FOUND_ROWS);
            if (!$conn->connect_error) {
                $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, DB_CONNECTION_TIMEOUT);
                if ($conn->connect_error && (time() - $startTime < DB_CONNECTION_TIMEOUT)) {
                    continue;
                }
                return $conn;
            }
        }
        throw new Exception("All database hosts failed to connect");
    }


    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $conn = getDatabaseConnection();
    if (!$conn) {
        throw new Exception("Could not get database connection");
    }
    $user = new USER($conn);
