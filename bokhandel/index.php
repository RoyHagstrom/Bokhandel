<?php
// Include the database connection file
include 'db_connection.php';

// Query to retrieve the text from the database
$sql = "SELECT text FROM messages"; // Assuming you have a table named 'messages' with a column named 'text'
$result = $conn->query($sql);

// Fetch the result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $helloWorldText = $row['text'];
}

// Close the database connection
$conn->close();
?>

<?php echo $helloWorldText; ?>