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
        $user->redirect("index.php");
    }
} else {
    header("Location: index.php");
    exit();
}



    $featured_books_sql = "SELECT * FROM Book WHERE Featured = 1 ORDER BY RAND() LIMIT 6";
    $featured_books_result = $conn->query($featured_books_sql);


$new_books_sql = "SELECT * FROM Book ORDER BY BookID DESC LIMIT 6";
$new_books_result = $conn->query($new_books_sql);

$user_bio_sql = "SELECT * FROM User WHERE Username = '{$book['Author']}'";
$user_bio_result = $conn->query($user_bio_sql);
$user_bio = $user_bio_result->fetch_assoc();


$other_books_sql = "SELECT * FROM Book WHERE Author = '{$book['Author']}' AND BookID <> {$book['BookID']} ORDER BY BookID DESC LIMIT 12";
$other_books_result = $conn->query($other_books_sql);

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
                    <?php if (isset($book['AgeRecommendation'])){  ?>
                    <span class="absolute bottom-0 right-0 m-2 px-3 py-1 bg-red-900 text-white rounded-lg font-black"><?= $book['AgeRecommendation'] ?></span>
                    <?php } ?>
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


                <?php if (isset($_SESSION["uname"]) && $_SESSION["uname"] == $book['Author']){  ?>
                <div class="mt-4 flex gap-2">
                    <a href="edit_book.php?bookid=<?php echo $book['BookID']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Edit Book</a>
                    <a href="delete_book.php?bookid=<?php echo $book['BookID']; ?>" class="bg-red-700 hover:bg-red-900 text-white py-2 px-4 rounded-md">Delete Book</a>
                </div>
            <?php } elseif(isset($_SESSION["uname"]) && $_SESSION["urole"] == "Admin"){ ?>
                <div class="mt-4 flex gap-2">
                <a href="edit_book.php?bookid=<?php echo $book['BookID']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Edit Book</a>
                <a href="delete_book.php?bookid=<?php echo $book['BookID']; ?>" class="bg-red-700 hover:bg-red-900 text-white py-2 px-4 rounded-md">Delete Book</a>
            </div>
            <?php } ?>



<?php
                    
?>

        <?php if (isset($book['Author']) && $other_books_result->num_rows > 0){  ?>
                <div class="p-4 rounded-lg w-full sm:w-116 mt-2 container justify-center items-center">
                    
                <h2 class="font-semibold mb-3 text-center text-black sm:text-xl text-lg">Other books by <?php echo $book['Author']; ?>:</h2>

                    <div class="flex justify-center items-center grid gap-2 grid-cols-3 lg:grid-cols-6">
                        <?php 
                    while($other_book = $other_books_result->fetch_assoc()){


                echo '<a href="singlebook.php?id=' . $other_book['BookID'] . '"class="block h-auto bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-1 right-1 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $other_book['Price'] . '€</span>';
                echo '<img src="' . $other_book['Image'] . '" alt="' . $other_book['Title'] . '" class="w-full md:w-full h-36 md:h-48 object-cover">';
                echo '</a>';


                     } ?>
                </div>
                </div>
            <?php } ?>


            
            </div>



        </div>
    </div>
    <div class="md:w-3/12 pl-4">
        <div class="book-info">
            <?php if (!empty($book['Illustrator'])): ?>
            <p><span class="font-semibold">Illustrator:</span> <?php echo $book['Illustrator']; ?></p>
            <?php endif; ?>
            <?php if (!empty($book['AgeRecommendation'])): ?>
            <p><span class="font-semibold">Age Recommendation:</span> <?php echo $book['AgeRecommendation']; ?></p>
            <?php endif; ?>
            <?php if (!empty($book['CategoryName'])): ?>
            <p><span class="font-semibold">Category:</span> <?php echo $book['CategoryName']; ?></p> 
            <?php endif; ?>
            <?php if (!empty($book['Genre'])): ?>
            <p><span class="font-semibold">Genre:</span> <?php echo $book['Genre']; ?></p>
            <?php endif; ?>
            <?php if (!empty($book['PublicationYear'])): ?>
            <p><span class="font-semibold">Publication Year:</span> <?php echo $book['PublicationYear']; ?></p>
            <?php endif; ?>
            <?php if (!empty($book['Series'])): ?>
            <p><span class="font-semibold">Series:</span> <?php echo $book['Series']; ?></p>
            <?php endif; ?>
            <?php if (!empty($book['Publisher'])): ?>
            <p><span class="font-semibold">Publisher:</span> <?php echo $book['Publisher']; ?></p>
            <?php endif; ?>
            <?php if (!empty($book['Pages'])): ?>
            <p><span class="font-semibold">Pages:</span> <?php echo $book['Pages']; ?></p>
            <?php endif; ?>
        </div>
        <?php
    ?>
    <?php if (!empty($user_bio["Bio"])): ?>
    <div class="p-8 rounded-lg w-full sm:w-116 mt-8 bg-gray-100">
        <?php if (!empty($user_bio['Image'])): ?>
            <img src="<?php echo $user_bio['Image']; ?>" alt="<?php echo $user_bio['Username']; ?>" class="w-full h-auto rounded-lg mt-4">
        <?php endif; ?>
        <h1 class=" font-semibold mb-6 text-center text-black sm:text-3xl text-2xl"><?php echo $user_bio['Username'] ?></h1>
        <p><?php echo $user_bio["Bio"]; ?></p>
    </div>
    <?php endif; ?>
    </div>
</div>



    <div class="w-full container rounded-b-lg p-8 shadow-md flex">
        <div class="book-info">
        <p><?php echo $book['Description']; ?></p>
        </div>
    </div>










    <div class="p-8 rounded-lg w-full sm:w-116 mt-8">
    <h1 class=" font-semibold mb-6 text-center text-black sm:text-3xl text-2xl">Featured Books</h1>



    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2 md:gap-4">
        <?php
        if ($featured_books_result->num_rows > 0) {
            while ($row = $featured_books_result->fetch_assoc()) {
                echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-30 md:w-full h-30 md:h-80 object-cover">';
                echo '<div class="p-3 md:p-6 text-sm md:text-md">';
                echo '<h2 class="md:text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">' . htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 100)) . '...</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo 'No new books available';
        }
        ?>
    </div>
</div>




    <div class=" p-8 rounded-lg w-full text-center sm:w-116 mt-8">
    <h1 class="font-semibold mb-6 text-center text-black sm:text-3xl text-2xl">New Books</h1>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2 md:gap-4">
        <?php
        if ($new_books_result->num_rows > 0) {
            while ($row = $new_books_result->fetch_assoc()) {
                echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-30 md:w-full h-30 md:h-80 object-cover">';
                echo '<div class="p-3 md:p-6 text-sm md:text-md">';
                echo '<h2 class="md:text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">' . htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 50)) . '...</p>';
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
