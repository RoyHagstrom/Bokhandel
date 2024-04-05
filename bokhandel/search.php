<?php
require_once 'db_connection.php';

try {
    // Check if a search term is provided
    $searchTerm = $_GET['term'] ?? '';

    // Prepare the SQL statement to search for books by title
    $stmt = $conn->prepare("SELECT * FROM bokhandel.Book WHERE Title LIKE ? OR Author LIKE ?");
    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    // Add wildcards to the search term to perform a partial match
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    // Execute the query
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }

    // Get the result set
    $result = $stmt->get_result();

    // Fetch books matching the search criteria
    $books = $result->fetch_all(MYSQLI_ASSOC);

    // Filter out duplicate records based on Title
    $uniqueBooks = [];
    foreach ($books as $book) {
        if (!isset($uniqueBooks[$book['Title']])) {
            $uniqueBooks[$book['Title']] = $book;
        }
    }

    // Convert the filtered array back to indexed array
    $uniqueBooks = array_values($uniqueBooks);

    // Return unique books as JSON
    header('Content-Type: application/json');
    echo json_encode($uniqueBooks);
} catch (Exception $e) {
    // Output error message
    echo "Failed to search for books. Please try again later.";
}
?>
