<?php
include 'db_connection.php';
include 'header.php'; 

if (isset($_GET['id'])) {
    $bookID = $conn->real_escape_string($_GET['id']);

    $stmt = $conn->prepare("SELECT b.*, c.name AS CategoryName FROM Book b JOIN categories c ON b.Category = c.id WHERE b.BookID = ?");
    if (!$stmt) {
        die("Failed to prepare SQL statement: " . $conn->error);
    }

    $stmt->bind_param("i", $bookID);
    if (!$stmt->execute()) {
        die("Failed to execute SQL statement: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $book = $result->fetch_assoc();
    
    if (!$book) {
        die("Book not found"); 
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<div class="bg-white text-black min-h-screen flex flex-col justify-center items-center">
    <div class="container p-8 rounded-lg shadow-md max-w-lg w-full">
        <h1 class="text-3xl font-semibold mb-8 text-center">Book Details</h1>
        <?php if ($book['Image']): ?>
        <div class="mb-8">
            <img src="<?= $book['Image'] ?>" alt="<?= $book['Title'] ?>" class="w-full h-auto rounded-lg">
        </div>
        <?php endif; ?>
        <div class="book-info">
            <h2 class="text-xl font-semibold mb-4"><?php echo $book['Title']; ?></h2>
            <p><span class="font-semibold">Author:</span> <?php echo $book['Author']; ?></p>
            <p><span class="font-semibold">Description:</span> <?php echo $book['Description']; ?></p>
            <p><span class="font-semibold">Illustrator:</span> <?php echo $book['Illustrator']; ?></p>
            <p><span class="font-semibold">Age Recommendation:</span> <?php echo $book['AgeRecommendation']; ?></p>
            <p><span class="font-semibold">Category:</span> <?php echo $book['CategoryName']; ?></p> 
            <p><span class="font-semibold">Genre:</span> <?php echo $book['Genre']; ?></p>
            <p><span class="font-semibold">Publication Year:</span> <?php echo $book['PublicationYear']; ?></p>
            <p><span class="font-semibold">Series:</span> <?php echo $book['Series']; ?></p>
            <p><span class="font-semibold">Publisher:</span> <?php echo $book['Publisher']; ?></p>
            <p><span class="font-semibold">Price:</span> <?php echo $book['Price']; ?>€</p>
            <p><span class="font-semibold">Pages:</span> <?php echo $book['Pages']; ?></p>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
