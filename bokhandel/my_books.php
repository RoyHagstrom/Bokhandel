<?php
include 'Includes/header.php';

if (!isset($_GET["uid"])){
    $user->redirect("index.php");
}





    $author = $_GET["uid"];    



$stmt = $conn->prepare("SELECT * FROM Book WHERE Author = ? ORDER BY BookID DESC");
$stmt->bind_param("s", $author);
$stmt->execute();
$result = $stmt->get_result();


$user_bio_sql = "SELECT * FROM User WHERE Username = ?";
$stmt_bio = $conn->prepare($user_bio_sql);
$stmt_bio->bind_param("s", $author);
$stmt_bio->execute();
$user_bio_result = $stmt_bio->get_result();
$user_bio = $user_bio_result->fetch_assoc();


?>
<div class="bg-white text-black w-dvw min-h-screen flex flex-col justify-center items-center p-8">

<div class="container mx-auto sm:p-8 w-full md:max-w-6xl">
    <h2 class="text-3xl font-bold mb-4"><?php echo $author; ?>'s books</h2>

<?php if (!empty($user_bio["Bio"]) || !empty($user_bio["Image"])): ?>

    <div class="p-4 rounded-lg w-full my-4 bg-gray-100 min-h-64 shadow-md">
        <a href="my_books.php?uid=<?php echo $user_bio['Username']; ?>" class="flex flex-col items-center">
            <h1 class="mt-4 font-semibold text-3xl text-center text-black sm:mt-6"><?php echo $user_bio['Username'] ?></h1>

            <?php if (!empty($user_bio['Image'])): ?>
                <img src="<?php echo $user_bio['Image']; ?>" alt="<?php echo $user_bio['Username']; ?>" class="mt-2 w-64 h-64 object-cover rounded-full">
            <?php endif; ?>


<p class="mt-4 text-lg text-center leading-relaxed sm:mt-6 sm:text-xl">
    Rating: <?php echo $user_bio["Rating"]; ?>
</p>


            
            <div class="mt-4 max-w-[60ch] text-lg text-center leading-relaxed sm:mt-6 sm:text-xl">
                <?php echo $user_bio["Bio"]; ?>
            </div>
        </a>
    </div>
    </div>
    <?php endif; ?>


    <div class="mx-auto mb-2 p-4 flex row items-center justify-center gap-4">
    <form method="get" class="flex flex-col sm:flex-row items-center max-w-xl">
    <input type="hidden" name="uid" value="<?php echo htmlspecialchars($_GET['uid'] ?? '', ENT_QUOTES); ?>">
                <div class="sm:flex sm:flex-row items-center sm:space-x-4">
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
                            <option value="rating_asc" <?php echo ($_GET['sort'] ?? '') == 'rating_asc' ? 'selected' : '' ?>>Rating (Low to High)</option>
                            <option value="rating_desc" <?php echo ($_GET['sort'] ?? '') == 'rating_desc' ? 'selected' : '' ?>>Rating (High to Low)</option>
                            <option value="pages_asc" <?php echo ($_GET['sort'] ?? '') == 'pages_asc' ? 'selected' : '' ?>>Pages (Least to Most)</option>
                            <option value="pages_desc" <?php echo ($_GET['sort'] ?? '') == 'pages_desc' ? 'selected' : '' ?>>Pages (Most to Least)</option>

                        </select>
                    </div>
                </div>
            </form>
    </div>

    <div class="container grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 md:gap-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <a href="singlebook.php?id=<?php echo $row['BookID']; ?>" class="block h-full bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300">
                <div class="relative h-auto">
                    <img src="<?php echo $row['Image']; ?>" alt="<?php echo $row['Title']; ?>" class="object-cover w-full h-auto max-h-96">
                    <div class="absolute top-0 right-0 p-2">
                        <span class="bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg"><?php echo $row['Price']; ?>€</span>
                    </div>
                </div>
                <div class="p-2 sm:p-6">
                        <h2 class="sm:text-xl md:text-2xl font-semibold text-gray-900 dark:text-white"><?php echo $row['Title']; ?></h2>
                        <p class="text-sm md:text-base text-gray-700 dark:text-gray-300">Author: <?php echo $row['Author']; ?></p>
                        <p class="text-sm md:text-base text-gray-700 dark:text-gray-300"><?php echo htmlspecialchars(strip_tags(html_entity_decode(substr($row['Description'], 0, 100)))) . '...'; ?></p>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="mt-4 flex gap-2">
    </div>
</div>
</div>
</div>
</div>

<?php



include 'Includes/footer.php';
?>
