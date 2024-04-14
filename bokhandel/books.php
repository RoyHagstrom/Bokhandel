<?php
include 'Includes/header.php'; 

$category_id = $_GET['id'] ?? null;

$sql = "SELECT * FROM Book";
if ($category_id) {
    $sql .= " WHERE Category = '$category_id'";
}
$sql .= " ORDER BY BookID DESC";

$result = $conn->query($sql);
?>

<div class="bg-gray-800 p-2 md:p-8 w-full sm:w-130 mt-8">
            <h1 class="text-center text-2xl font-semibold mb-6 text-white">Featured Categories:</h1>

            <div class="flex flex-wrap justify-center items-center">
                <?php
                $featured_categories_sql = "SELECT id, name, image FROM categories WHERE featured = 1";
                $featured_categories_result = $conn->query($featured_categories_sql);

                if ($featured_categories_result->num_rows > 0) {
                    while ($row = $featured_categories_result->fetch_assoc()) {
                        $featured_category_id = $row['id']; 
                        $featured_category_name = $row['name'];
                        $featured_category_image = $row['image'];

                        echo '<div class="m-2 md:m-4 relative rounded-lg">';
                        echo '<a href="books.php?id=' . $featured_category_id . '">'; 
                        echo '<img src="' . $featured_category_image . '" alt="' . $featured_category_name . '" class="w-28 h-28 sm:w-40 sm:h-40 lg:w-64 lg:h-64 object-cover rounded-lg opacity-70">';
                        echo '<div class="absolute inset-0 flex items-center justify-center text-white text-sm lg:text-3xl font-semibold bg-black bg-opacity-50 rounded-lg">' . $featured_category_name . '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-lg text-gray-500">No featured categories available</p>';
                }
                ?>
            </div>
        </div>



<?php
if (!$category_id) {
    $featured_books_sql = "SELECT * FROM Book WHERE Featured = 1 ORDER BY RAND() LIMIT 7";
    $featured_books_result = $conn->query($featured_books_sql);
?>
<div class="w-full bg-white">
    <div class="mx-auto p-8 rounded-lg shadow-md w-full sm:w-130 mt-8">
        <h1 class="text-2xl font-semibold mb-6 text-center text-black">Featured Books</h1>

        <div id="carousel" class="daisy-carousel grid grid-cols-2 sm:grid-cols-2 md:grid-cols-<?php echo min($featured_books_result->num_rows, 3); ?> lg:grid-cols-<?php echo min($featured_books_result->num_rows, 4); ?> xl:grid-cols-<?php echo min($featured_books_result->num_rows, 7); ?> gap-2 md:gap-4">
            <?php
            if ($featured_books_result->num_rows > 0) {
                while ($row = $featured_books_result->fetch_assoc()) {
                    echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                    echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                    echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-30 md:w-full h-30 md:h-80 object-cover">';
                    echo '<div class="p-6">';
                    echo '<h2 class="text-md md:text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                    echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                    echo '<p class="text-gray-700 dark:text-gray-300">' . htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 100)) . '...</p>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo 'No featured books available';
            }
            ?>
        </div>
    </div>
</div>
<?php
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById('carousel');
        new Glider(carousel, {
            slidesToShow: 'auto',
            slidesToScroll: 1,
            draggable: true,
            dots: '.dots',
            arrows: {
                prev: '.glider-prev',
                next: '.glider-next'
            }
        });
    });
</script>



    

<?php
if ($category_id) {
    $category_name_sql = "SELECT name FROM categories WHERE id = $category_id";
    $category_name_result = $conn->query($category_name_sql);
    if ($category_name_result->num_rows > 0) {
        $category_name_row = $category_name_result->fetch_assoc();
        $title = $category_name_row['name'];
    } else {
        $title = "Category Not Found";
    }
} else {
    $title = "All Books";
}
?>





<div class="bg-white text-black">
    <div class="container p-8 mx-auto">
        <h1 class="text-3xl font-bold mb-6"><?= htmlspecialchars($title) ?></h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-8">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-entry bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">
                        <a href="singlebook.php?id=<?= htmlspecialchars($row['BookID']) ?>" class="block">
                            <span class="absolute top-2 right-2 bg-white text-gray-900 font-bold md:px-2 md:py-1 rounded-lg text-sm md:text-md"><?= htmlspecialchars($row["Price"]) ?>€</span>
                            <img src="<?= htmlspecialchars($row["Image"]) ?>" alt="<?= htmlspecialchars($row["Title"]) ?>" class="w-30 md:w-full h-30 md:h-80 object-cover rounded-t-lg">
                            <div class="p-2 md:p-6">
                                <h2 class="text-md md:text-xl font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($row["Title"]) ?></h2>
                                <p class="text-gray-700 dark:text-gray-300">Author: <?= htmlspecialchars($row["Author"]) ?></p>
                                <p class="text-gray-700 dark:text-gray-300"><?= htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 100)) ?>...</p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class='text-lg text-gray-700 dark:text-white'>No books found</p>
            <?php endif; ?>
        </div>
    </div>
</div>








<?php
$conn->close();
include 'Includes/footer.php';
?>
