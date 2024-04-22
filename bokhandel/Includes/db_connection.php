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
        foreach (DB_HOSTS as $host) {
            $conn = mysqli_init();
            mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, DB_CONNECTION_TIMEOUT);
            mysqli_real_connect($conn, $host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
            if (mysqli_connect_errno() === 0) {
                $socket = mysqli_get_socket($conn);
                if (socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 3, 'usec' => 0])) {
                    return $conn;
                }
                mysqli_close($conn);
            }
            mysqli_close($conn);
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

