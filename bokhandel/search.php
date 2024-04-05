<?php
require_once 'db_connection.php';

try {
    // Check if a search term is provided
    $searchTerm = $_GET['term'] ?? '';

    // Prepare the SQL statement to search for books by title
    $stmt = $conn->prepare("SELECT * FROM Book WHERE Title LIKE ?");
    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    // Add wildcards to the search term to perform a partial match
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);

    // Execute the query
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }

    // Get the result set
    $result = $stmt->get_result();

    // Fetch books matching the search criteria
    $books = $result->fetch_all(MYSQLI_ASSOC);

    // Return books as JSON
    header('Content-Type: application/json');
    echo json_encode($books);
} catch (Exception $e) {
    // Output error message
    echo "Failed to search for books. Please try again later.";
}
?>
