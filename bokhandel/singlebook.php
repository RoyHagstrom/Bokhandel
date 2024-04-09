<?php
include 'db_connection.php';
include 'header.php'; 

if (isset($_GET['id'])) {
    $bookID = $conn->real_escape_string($_GET['id']);

    $stmt = $conn->prepare("SELECT b.*, c.name AS CategoryName, s.StatusName FROM Book b JOIN categories c ON b.Category = c.id LEFT JOIN Status s ON b.StatusID = s.StatusID WHERE b.BookID = ?");
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
<div class="bg-white text-black w-dvw min-h-screen flex flex-col justify-center items-center">
<h1 class="text-xl font-bold mb-4"><?php echo $book['Title']; ?></h1>

    <div class="container p-8 rounded-lg shadow-md flex">
        <div class="w-3/12 pr-4">
            <div class="relative">
                <?php if ($book['Image']): ?>
                    <div class="mb-8 relative">
                        <span class="absolute top-0 left-0 m-2 px-3 py-1 bg-gray-800 text-white rounded-md"><?= $book['StatusName'] ?></span>
                        <img id="bookImage" src="<?= $book['Image'] ?>" alt="<?= $book['Title'] ?>" class="w-full h-auto rounded-lg">
                        <span class="absolute top-0 right-0 m-2 px-3 py-1 bg-green-500 text-white rounded-md"><?= $book['Price'] ?>€</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="w-9/12 pl-4">
            <div class="book-info">
                <p><span class="font-semibold">Description:</span> <?php echo $book['Description']; ?></p>
            </div>
        </div>
        <div class="w-1/4 pl-4">
            <div class="book-info">
                <p class=""><span class="font-semibold">Author:</span> <?php echo $book['Author']; ?></p>
                <p><span class="font-semibold">Illustrator:</span> <?php echo $book['Illustrator']; ?></p>
                <p><span class="font-semibold">Age Recommendation:</span> <?php echo $book['AgeRecommendation']; ?></p>
                <p><span class="font-semibold">Category:</span> <?php echo $book['CategoryName']; ?></p> 
                <p><span class="font-semibold">Genre:</span> <?php echo $book['Genre']; ?></p>
                <p><span class="font-semibold">Publication Year:</span> <?php echo $book['PublicationYear']; ?></p>
                <p><span class="font-semibold">Series:</span> <?php echo $book['Series']; ?></p>
                <p><span class="font-semibold">Publisher:</span> <?php echo $book['Publisher']; ?></p>
                <p><span class="font-semibold">Pages:</span> <?php echo $book['Pages']; ?></p>
            </div>
        </div>
    </div>
</div>




<?php
include 'footer.php';
?>
