<?php
require_once 'db_connection.php';

try {
    $searchTerm = $_GET['term'] ?? '';

    if (!empty($searchTerm)) {
        $stmt = $conn->prepare("SELECT * FROM bokhandel.Book WHERE (Title LIKE ? OR Author LIKE ?) LIMIT 12");
        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement: " . $conn->error);
        }
        $searchTerm = '%' . $searchTerm . '%';
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    } else {
        echo json_encode([]);
        exit; 
    }

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $books = $result->fetch_all(MYSQLI_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($books);
} catch (Exception $e) {
    echo "Failed to search for books. Please try again later.";
}
?>
