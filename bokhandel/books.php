<?php
include 'Includes/header.php'; 

$search_term = $_GET['search'] ?? ''; 
$category_id = $_GET['id'] ?? '';

$conditions = [];
$bind_params = [];
$bind_types = '';

if (!empty($search_term)) {
    $search_term = preg_replace('/[^A-Za-z0-9\s]/u', '', $search_term); 
    $search_term = trim($search_term); 

    $words = array_map('preg_quote', explode(' ', $search_term));

    $pattern = '%' . implode('%', $words) . '%';

    $placeholders = str_repeat('?,', count($words));
    $bind_types = str_repeat('s', count($words));
    $bind_params = array_merge($bind_params, array_fill(0, count($words), $pattern));

    $conditions[] = "`Title` LIKE ? OR `Author` LIKE ? OR `Series` LIKE ?";
    $bind_params[] = $pattern;
    $bind_params[] = $pattern;
    $bind_params[] = $pattern;
}

if (!empty($category_id)) {
    $conditions[] = "`Category` = '$category_id'";
}

$sql = "SELECT * FROM `Book` ";
$conditions_string = implode(' AND ', $conditions);
$sql .= !empty($conditions_string) ? "WHERE $conditions_string" : "WHERE 1=1";

$order = $_GET['sort'] ?? null;
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
if ($order && array_key_exists($order, $orderMappings)) {
    $sql .= " ORDER BY " . $orderMappings[$order];
} else {
    $sql .= " ORDER BY `BookID` DESC";
}

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die($conn->error);
}

if (!empty($bind_params)) {
    $stmt->bind_param($bind_types, ...$bind_params);
}

$stmt->execute();
$result = $stmt->get_result(); 

$title = "All Books"; 
if ($category_id) {
    $category_name_sql = "SELECT name FROM categories WHERE id = $category_id";
    $category_name_result = $conn->query($category_name_sql);
    if ($category_name_result->num_rows > 0) {
        $category_name_row = $category_name_result->fetch_assoc();
        $title = $category_name_row['name'];
    } else {
        $title = "Category Not Found";
    }
} elseif(isset($_GET['search'])) {
    $title = '"'.$search_term.'"';
}

?>





<div class="bg-gray-800 p-2 md:p-8 w-full sm:w-130">
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

<div class="w-full bg-white">
<?php if (empty($search_term) && empty($category_id) && empty($_GET['sort'])): ?>
        <div class="mx-auto p-8 rounded-lg shadow-md w-full sm:w-130 mt-8">
            <h1 class="text-2xl font-semibold mb-6 text-center text-black">Featured Books</h1>

            <div id="carousel" class="daisy-carousel grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2 md:gap-4">
                <?php
                $featured_books_sql = "SELECT * FROM `Book` WHERE `Featured` = 1 ORDER BY RAND() LIMIT 6";
                $featured_books_result = $conn->query($featured_books_sql);

                if ($featured_books_result && $featured_books_result->num_rows > 0) {
                    while ($row = $featured_books_result->fetch_assoc()) {
                        echo '<a href="singlebook.php?id=' . htmlspecialchars($row['BookID']) . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">
                            <span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . htmlspecialchars($row['Price']) . '€</span>
                            <img src="' . htmlspecialchars($row['Image']) . '" alt="' . htmlspecialchars($row['Title']) . '" class="w-full object-cover md:h-72">
                            <div class="p-4 sm:p-4 md:p-6">
                                <h2 class="text-sm md:text-base font-semibold text-gray-900 dark:text-white">' . htmlspecialchars(mb_substr(strip_tags(html_entity_decode($row["Title"])), 0, 50)) . '</h2>
                                <p class="text-sm text-gray-700 dark:text-gray-300">Author: ' . htmlspecialchars($row['Author']) . '</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2 md:line-clamp-3">' . htmlspecialchars(mb_substr(strip_tags(html_entity_decode($row["Description"])), 0, 50)) . '...</p>
                            </div>
                        </a>';
                        echo '</a>';
                    }
                } else {
                    echo 'No featured books available';
                }
                ?>
            </div>
        </div>
<?php endif; ?>


<div class="bg-white text-black">
    <div class="container p-8 mx-auto">
        <div class="flex row items-center justify-center">
        <div class="mx-auto mb-2 p-4 flex row items-center justify-center gap-4">
            <form method="get" class="flex flex-col sm:flex-row items-center max-w-xl">
                <div class="sm:flex sm:flex-row items-center sm:space-x-4">
                    <div class="sm:w-1/2">
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
                    </div>
                    <div class="sm:w-1/2">
                        <label for="sort-select" class="text-sm font-medium mx-2 mb-2 sm:mb-0 sm:ml-2">Sort:</label>
                        <select id="sort-select" name="sort" onchange="this.form.submit()" class="block border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs w-full mt-2 sm:mt-0">
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
                    </div>
                </div>
            </form>
        </div>

        <div id="search-form" class="flex justify-center w-full my-2 p-2">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="get" class="flex items-center gap-2 max-w-xl">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" <?php if(isset($_GET['search'])) { echo 'value="' . htmlspecialchars(urldecode($_GET['search'])) . '"'; } ?>  id="default-search" name="search" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Mockups, Logos..." required />
                </div>
                <button type="submit" class="text-white ml-2 bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Search
                </button>
                <button type="button" id="clear-search" class="ml-2 px-4 py-2 rounded-lg bg-blue-200 hover:bg-blue-300 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium text-sm text-white dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Clear
                </button>
            </form>
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

<script>
    document.getElementById('search-form').addEventListener('submit', function(e) {
        var searchTerm = document.getElementById('default-search').value;
        if (searchTerm !== '') {
            e.preventDefault();
            var url = new URL(window.location.href);
            url.searchParams.set('search', encodeURIComponent(searchTerm)); 
            window.location.href = url.toString(); 
        }
    });
    document.getElementById('clear-search').addEventListener('click', function(e) {
        window.location.href = "books.php"; 
    });
</script>

<?php
$conn->close();
include 'Includes/footer.php';
?>
