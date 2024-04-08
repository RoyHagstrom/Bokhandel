<?php
// Database connection parameters
$host = 'novatest.ddns.net'; // DDNS hostname
$port = 3306; // Default MySQL port
$username = 'root';
$password = 'Lolipop05';
$database = 'bokhandel'; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    // Log connection error
    $errorLogMessage = "[" . date('Y-m-d H:i:s') . "] Connection failed: " . $conn->connect_error . "\n";
    file_put_contents('C:\xampp\htdocs\bokhandel\connection_error_log.txt', $errorLogMessage, FILE_APPEND);

    // Output connection error
    die("Connection failed: " . $conn->connect_error);
} else {
    // Log successful connection
    $successLogMessage = "[" . date('Y-m-d H:i:s') . "] Connection successful\n";
    file_put_contents('C:\xampp\htdocs\bokhandel\connection_success_log.txt', $successLogMessage, FILE_APPEND);
}

?>
