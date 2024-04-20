<?php

    if (!defined('DB_HOSTS')) {
        define('DB_HOSTS', [
            'primary' => 'novatest.ddns.net',
            'local' => '192.168.1.111',
            'network' => '172.22.0.2'
        ]);
        define('DB_PORT', '3306');
        define('DB_USERNAME', 'test');
        define('DB_PASSWORD', 'test');
        define('DB_DATABASE', 'bokhandel');
    }

    function connectToDb($host) {
        static $conn_cache = []; 
        if (!isset($conn_cache[$host])) {
            $conn_cache[$host] = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
            if ($conn_cache[$host]->connect_error) {
                throw new Exception("Connection failed: " . $conn_cache[$host]->connect_error);
            }
        }
        return $conn_cache[$host];
    }


    function getDatabaseConnection() {
        try {
            return connectToDb(DB_HOSTS['primary']);
        } catch (Exception $e) {
            try {
                return connectToDb(DB_HOSTS['local']);
            } catch (Exception $e) {
                return connectToDb(DB_HOSTS['network']);
            }
        }
    }

    if (!session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $conn = getDatabaseConnection();
    $user = new USER($conn);


