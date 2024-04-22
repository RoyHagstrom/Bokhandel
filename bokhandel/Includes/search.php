<?php
require_once 'class.user.php';
require_once 'db_connection.php';






if (isset($_GET['term'])) {
    $searchTerm = trim(strip_tags($_GET['term'])); 
    $searchTerm = $conn->real_escape_string($searchTerm); 
    $result = $user->searchBooks($searchTerm); 
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([]); 
}
?>
