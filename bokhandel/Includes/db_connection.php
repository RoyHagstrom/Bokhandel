<?php

    define('DB_HOSTS', [
        'primary' => '172.22.0.4',
        'local' => '192.168.1.111',
        'network' => 'novatest.ddns.net'
    ]);
    define('DB_PORT', '3306');
    define('DB_USERNAME', 'test');
    define('DB_PASSWORD', 'test');
    define('DB_DATABASE', 'bokhandel');
    define('DB_CONNECTION_TIMEOUT', 3);

    function getDatabaseConnection() {
        foreach (DB_HOSTS as $host) {
            $conn = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
            if (!$conn->connect_error) {
                $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, DB_CONNECTION_TIMEOUT);
                return $conn;
            } else {
                $conn->close();
                throw new Exception("Could not get database connection");
            }
        }
        return null;
    }

    
ini_set('opcache.enable_cli', 1);
ini_set('opcache.validate_timestamps', 1);
ini_set('opcache.save_comments', 0);
ini_set('opcache.enable_file_override', 0);
ini_set('opcache.fast_shutdown', 1);

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $conn = getDatabaseConnection();
    if (!$conn) {
        throw new Exception("Could not get database connection");
    }
    $user = new USER($conn);

