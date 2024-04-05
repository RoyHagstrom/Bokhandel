<?php
// Include the database connection file
include 'db_connection.php';
include 'header.php'; // Include your header file

// Check if BookID is provided in the URL
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $bookID = $conn->real_escape_string($_GET['id']);

    // Prepare the SQL statement to fetch book details by BookID
    $stmt = $conn->prepare("SELECT * FROM Book WHERE BookID = ?");
    if (!$stmt) {
        die("Failed to prepare SQL statement: " . $conn->error);
    }

    // Bind the parameters and execute the statement
    $stmt->bind_param("i", $bookID);
    if (!$stmt->execute()) {
        die("Failed to execute SQL statement: " . $stmt->error);
    }

    // Get the result set
    $result = $stmt->get_result();

    // Fetch book details
    $book = $result->fetch_assoc();
    
    // Check if the book exists
    if (!$book) {
        die("Book not found");
    }
} else {
    // Redirect if BookID is not provided in the URL
    header("Location: index.php");
    exit();
}
?>
<body class="dark-mode bg-gray-900 text-white h-screen flex justify-center items-center">

<!-- Display book details -->
<div class="container p-8 rounded-lg shadow-md w-full sm:w-96">
    <h1 class="text-2xl font-semibold mb-6">Book Details</h1>
    <div class="book-info mb-4">
        <h2 class="text-xl mb-2"><?php echo $book['Title']; ?></h2>
        <p>Author: <?php echo $book['Author']; ?></p>
        <p>Description: <?php echo $book['Description']; ?></p>
        <p>Illustrator: <?php echo $book['Illustrator']; ?></p>
        <p>Age Recommendation: <?php echo $book['AgeRecommendation']; ?></p>
        <p>Category: <?php echo $book['Category']; ?></p>
        <p>Genre: <?php echo $book['Genre']; ?></p>
        <p>Publication Year: <?php echo $book['PublicationYear']; ?></p>
        <p>Series: <?php echo $book['Series']; ?></p>
        <p>Publisher: <?php echo $book['Publisher']; ?></p>
        <p>Price: $<?php echo $book['Price']; ?></p>
        <p>Pages: <?php echo $book['Pages']; ?></p>
        <!-- Add more book details here -->
    </div>
</div>

