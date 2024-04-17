<?php


if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '127.0.0.1' && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'http://novatest.ddns.net') === 0) {
    http_response_code(403);
    die('Error: Too many redirects');
}
    define('DB_HOSTS', [
        'primary' => 'novatest.ddns.net',
        'local' => '192.168.1.111',
        'network' => '172.22.0.2'
    ]);
    define('DB_PORT', '3306');
    define('DB_USERNAME', 'test');
    define('DB_PASSWORD', 'test');
    define('DB_DATABASE', 'bokhandel');
    
    function connectToDb($host) {
        static $conn = null;
        if ($conn === null) {
            $conn = new mysqli($host, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
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
                return connectToDb(DB_HOSTS['network']);
            }
        }
    }
    
    session_start();
    $conn = getDatabaseConnection();
    $user = new USER($conn);
