
<?php
define('DB_HOST', 'novatest.ddns.net');
define('DB_PORT', '3306');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'bokhandel');

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

if ($conn->connect_error) {
    $errorLogMessage = "[" . date('Y-m-d H:i:s') . "] Connection failed: " . $conn->connect_error . "\n";
    file_put_contents('C:\xampp\htdocs\bokhandel\connection_error_log.txt', $errorLogMessage, FILE_APPEND);
    die("Connection failed: " . $conn->connect_error);
} else {
    $successLogMessage = "[" . date('Y-m-d H:i:s') . "] Connection successful\n";
    file_put_contents('C:\xampp\htdocs\bokhandel\connection_success_log.txt', $successLogMessage, FILE_APPEND);
}
?>
