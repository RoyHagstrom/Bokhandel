<?php
include 'Includes/header.php'; 

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



    $featured_books_sql = "SELECT * FROM Book WHERE Featured = 1 ORDER BY RAND() LIMIT 7";
    $featured_books_result = $conn->query($featured_books_sql);


$new_books_sql = "SELECT * FROM Book ORDER BY BookID DESC LIMIT 7";
$new_books_result = $conn->query($new_books_sql);
?>
<div class="bg-white text-black w-dvw min-h-screen flex flex-col justify-center items-center ">
<h1 class="text-3xl font-bold mt-8"><?php echo $book['Title']; ?></h1>
<p class=""><span class="font-semibold mb-4">Author:</span> <?php echo $book['Author']; ?></p>

<div class="container p-4 lg:p-8 sm:p-4 rounded-lg shadow-lg flex flex-col md:flex-row">
    <div class="md:w-3/12 mb-4 lg:mb-0 lg:pr-4">
        <div class="relative">
            <?php if ($book['Image']): ?>
                <div class="mb-8 relative">
                    <span class="absolute top-0 left-0 m-2 px-3 py-1 bg-gray-800 text-white rounded-lg"><?= $book['StatusName'] ?></span>
                    <img id="bookImage" src="<?= $book['Image'] ?>" alt="<?= $book['Title'] ?>" class="w-full h-auto rounded-lg">
                    <span class="absolute top-0 right-0 m-2 px-3 py-1 bg-green-500 text-white rounded-lg font-bold"><?= $book['Price'] ?>€</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="md:w-7/12 mb-4 lg:mb-0 md:pl-4">
        <div class="book-info">
            <div class="flex flex-col">
                <div class="max-h-48 overflow-hidden">
                    <p><span class="font-semibold">Description:</span> <?php echo $book['Description']; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="md:w-3/12 pl-4">
        <div class="book-info">
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



    <div class="w-full container rounded-b-lg p-8 shadow-md flex">
        <div class="book-info">
            <p><span class="font-semibold"></span> <?php echo $book['Description']; ?></p>
        </div>
    </div>


    <div class="p-8 rounded-lg w-full sm:w-116 mt-8">
    <h1 class=" font-semibold mb-6 text-center text-black text-2xl sm:text-base">Featured Books</h1>

    <div id="carousel" class="daisy-carousel grid grid-cols-1 sm:grid-cols-2 text-xs sm:text-base md:grid-cols-<?php echo min($featured_books_result->num_rows, 3); ?> lg:grid-cols-<?php echo min($featured_books_result->num_rows, 4); ?> xl:grid-cols-<?php echo min($featured_books_result->num_rows, 7); ?> gap-4">
        <?php
        if ($featured_books_result->num_rows > 0) {
            while ($row = $featured_books_result->fetch_assoc()) {
                echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-full h-80 object-cover">';
                echo '<div class="p-6">';
                echo '<h2 class="text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">' . substr($row['Description'], 0, 20) . '...</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo 'No featured books available';
        }
        ?>
    </div>
</div>




    <div class=" p-8 rounded-lg w-full text-center sm:w-116 mt-8">
    <h1 class="text-2xl font-semibold mb-6">New Books</h1>

    <div id="carousel" class="daisy-carousel grid grid-cols-1 sm:grid-cols-2 md:grid-cols-<?php echo min($new_books_result->num_rows, 3); ?> lg:grid-cols-<?php echo min($new_books_result->num_rows, 4); ?> xl:grid-cols-<?php echo min($new_books_result->num_rows, 7); ?> gap-4">
        <?php
        if ($new_books_result->num_rows > 0) {
            while ($row = $new_books_result->fetch_assoc()) {
                echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-full h-80 object-cover">';
                echo '<div class="p-6">';
                echo '<h2 class="text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">' . substr($row['Description'], 0, 50) . '...</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo 'No new books available';
        }
        ?>
    </div>
</div>


</div>




<?php
include 'Includes/footer.php';
?>
