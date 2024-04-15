<?php
include 'Includes/header.php'; 

$search_term = trim($_GET['search'] ?? ''); 
$category_id = $_GET['id'] ?? '';

$conditions = [];

if (!empty($search_term)) {
    $search_term = str_replace(' ', '|', $search_term);
    $conditions[] = "`Title` REGEXP '^(.*)(" . $search_term . ")(.*)$'";
}

if (!empty($category_id)) {
    $conditions[] = "`Category` = '$category_id'";
}

$sql = "SELECT * FROM `Book` ";
$conditions_string = implode(' AND ', $conditions);
$sql .= !empty($conditions_string) ? "WHERE $conditions_string" : "WHERE 1=1";

$order = $_GET['sort'] ?? null;
if ($order) {
    $orderMappings = [
        'title_asc' => '`Title` ASC',
        'title_desc' => '`Title` DESC',
        'pub_year_asc' => '`PublicationYear` ASC',
        'pub_year_desc' => '`PublicationYear` DESC',
        'added_asc' => '`BookID` ASC',
        'added_desc' => '`BookID` DESC',
        'Price_asc' => '`Price` ASC',
        'Price_desc' => '`Price` DESC',
    ];
    $sql .= " ORDER BY " . ($orderMappings[$order] ?? '`BookID` DESC');
} else {
    $sql .= " ORDER BY `BookID` DESC";
}

$result = $conn->query($sql) or die($conn->error); 
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
    $featured_books_sql = "SELECT * FROM Book WHERE Featured = 1 ORDER BY RAND() LIMIT 6";
    $featured_books_result = $conn->query($featured_books_sql);
?>
<div class="w-full bg-white">
    <div class="mx-auto p-8 rounded-lg shadow-md w-full sm:w-130 mt-8">
        <h1 class="text-2xl font-semibold mb-6 text-center text-black">Featured Books</h1>

        <div id="carousel" class="daisy-carousel grid grid-cols-3 sm:grid-cols-3 md:grid-cols-<?php echo min($featured_books_result->num_rows, 3); ?> lg:grid-cols-<?php echo min($featured_books_result->num_rows, 4); ?> xl:grid-cols-<?php echo min($featured_books_result->num_rows, 7); ?> gap-2 md:gap-4">
            <?php
            if ($featured_books_result->num_rows > 0) {
                while ($row = $featured_books_result->fetch_assoc()) {
                    echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                    echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                    echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-full object-cover md:h-64">';
                    echo '<div class="p-4 md:p-6">';
                    echo '<h2 class="text-sm md:text-base font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                    echo '<p class="text-sm text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                    echo '<p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2 md:line-clamp-3">' . htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 50)) . '...</p>';
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
    <div class="container p-8 mx-auto ">
        <div style="overflow: auto" class="mx-auto m-8 p-8 border border-gray-300 rounded-lg shadow-md w-full sm:w-130 mt-8">
        <div class="mx-auto mb-8 p-8 flex flex-wrap items-center justify-center gap-4">
        <form method="get" class="flex flex-col sm:flex-row items-center">
            <label for="category-select" class="text-sm font-medium mx-2 mb-2 sm:mb-0 sm:ml-2">Category:</label>
            <select id="category-select" name="id" onchange="this.form.submit()" class="block border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs w-full mt-2 sm:mt-0">
                <option value="">All Books</option>
                <?php
                $categories_sql = "SELECT id, name FROM categories ORDER BY name";
                $categories_result = $conn->query($categories_sql);
                while ($category_row = $categories_result->fetch_assoc()) {
                    $selected = $category_row['id'] == ($_GET['id'] ?? '') ? 'selected' : '';
                    echo '<option value="' . $category_row['id'] . '" ' . $selected . '>' . htmlspecialchars($category_row['name']) . '</option>';
                }
                ?>
            </select>
        </form>
        <form id="sort-form" method="get" class="flex flex-col sm:flex-row items-center">
            <label for="sort-select" class="text-sm font-medium mx-2 mb-2 sm:mb-0 sm:ml-2">Sort:</label>
            <select id="sort-select" name="sort" onchange="document.getElementById('sort-form').submit()" class="block border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs w-full mt-2 sm:mt-0">
                <option value="">Default</option>
                <option value="title_asc" <?php echo ($_GET['sort'] ?? '') == 'title_asc' ? 'selected' : '' ?>>Title (A-Z)</option>
                <option value="title_desc" <?php echo ($_GET['sort'] ?? '') == 'title_desc' ? 'selected' : '' ?>>Title (Z-A)</option>
                <option value="pub_year_asc" <?php echo ($_GET['sort'] ?? '') == 'pub_year_asc' ? 'selected' : '' ?>>Publication Year (Oldest First)</option>
                <option value="pub_year_desc" <?php echo ($_GET['sort'] ?? '') == 'pub_year_desc' ? 'selected' : '' ?>>Publication Year (Newest First)</option>
                <option value="added_asc" <?php echo ($_GET['sort'] ?? '') == 'added_asc' ? 'selected' : '' ?>>Added (Oldest First)</option>
                <option value="added_desc" <?php echo ($_GET['sort'] ?? '') == 'added_desc' ? 'selected' : '' ?>>Added (Newest First)</option>
                <option value="Price_asc" <?php echo ($_GET['sort'] ?? '') == 'Price_asc' ? 'selected' : '' ?>>Price (Cheapest First)</option>
                <option value="Price_desc" <?php echo ($_GET['sort'] ?? '') == 'Price_desc' ? 'selected' : '' ?>>Price (Expensive First)</option>
            </select>
        </form>
    </div>
    <div id="search-form" class="flex justify-center w-full mt-8">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="get" class="flex items-center gap-2 max-w-xl">
            <input type="text" name="search" id="search-input" placeholder="Search" class="w-full border border-gray-300 px-4 py-2 rounded-lg text-sm bg-gray-100 dark:bg-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:bg-gray-700">
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-600 dark:text-white dark:hover:bg-blue-700 dark:focus:bg-blue-700">
                Search
            </button>
            <button type="button" id="clear-search" class="px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Clear
            </button>
        </form>
        <script>
            document.getElementById('search-form').addEventListener('submit', function(e) {
                var searchTerm = document.getElementById('search-input').value;
                if (searchTerm !== '') {
                    e.preventDefault();
                    var url = new URL(window.location.href);
                    url.searchParams.set('search', encodeURIComponent(searchTerm)); 
                    window.location.href = url.toString(); 
                }
            });
            document.getElementById('clear-search').addEventListener('click', function(e) {
                var url = new URL(window.location.href);
                url.searchParams.delete('search');
                window.location.href = url.toString(); 
            });
        </script>
    </div>
        </div>


        <h1 class="text-3xl font-bold mb-6"><?= htmlspecialchars($title) ?></h1>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6 md:gap-8">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-entry bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">
                        <a href="singlebook.php?id=<?= htmlspecialchars($row['BookID']) ?>" class="block">
                            <span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg"><?= htmlspecialchars($row["Price"]) ?>€</span>
                            <img src="<?= htmlspecialchars($row["Image"]) ?>" alt="<?= htmlspecialchars($row["Title"]) ?>" class="w-full h-48 object-cover rounded-t-lg sm:h-64 md:h-80 lg:h-96 xl:h-112">
                            <div class="p-2 sm:p-4 md:p-6">
                                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars(substr(strip_tags(html_entity_decode($row["Title"])), 0, 100)) ?></h2>
                                <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300">Author: <?= htmlspecialchars($row["Author"]) ?></p>
                                <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300"><?= htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 100)) ?>...</p>
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


