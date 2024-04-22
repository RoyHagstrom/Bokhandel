<?php
require_once 'class.user.php';
require_once 'db_connection.php';






if (!isset($_GET['term'])) {
    echo json_encode([]); 
    return;
}
$searchTerm = trim(strip_tags($_GET['term'])); 
$searchTerm = $conn->real_escape_string($searchTerm); 
echo json_encode($user->searchBooks($searchTerm), JSON_UNESCAPED_UNICODE);
?>
