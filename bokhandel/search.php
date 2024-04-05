<?php
require_once 'db_connection.php';

try {
    $searchTerm = $_GET['term'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM bokhandel.Book WHERE Title LIKE ? OR Author LIKE ?");
    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $books = $result->fetch_all(MYSQLI_ASSOC);

    $uniqueBooks = [];
    foreach ($books as $book) {
        if (!isset($uniqueBooks[$book['Title']])) {
            $uniqueBooks[$book['Title']] = $book;
        }
    }

    $uniqueBooks = array_values($uniqueBooks);

    header('Content-Type: application/json');
    echo json_encode($uniqueBooks);
} catch (Exception $e) {
    echo "Failed to search for books. Please try again later.";
}
?>
