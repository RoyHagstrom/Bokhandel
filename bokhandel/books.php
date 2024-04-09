<?php
include 'db_connection.php';
include 'header.php'; 

$category_id = $_GET['id'] ?? null;

$sql = "SELECT * FROM Book";
if ($category_id) {
    $sql .= " WHERE Category = '$category_id'";
}
$sql .= " ORDER BY PublicationYear DESC";

$result = $conn->query($sql);

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
        <h1 class="text-3xl font-bold mb-6"><?= $title ?></h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <a href="singlebook.php?id=<?= $row['BookID'] ?>" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">
                        <span class="absolute top-2 right-2 bg-white text-gray-900 font-bold px-2 py-1 rounded-lg"><?= $row["Price"] ?>€</span>
                        <img src="<?= $row["Image"] ?>" alt="<?= $row["Title"] ?>" class="w-full h-80 object-cover rounded-t-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white"><?= $row["Title"] ?></h2>
                            <p class="text-gray-700 dark:text-gray-300">Author: <?= $row["Author"] ?></p>
                            <p class="text-gray-700 dark:text-gray-300">Published Year: <?= $row["PublicationYear"] ?></p>
                            <p class="text-gray-700 dark:text-gray-300"><?= substr($row["Description"], 0, 100) ?>...</p>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class='text-lg text-gray-700 dark:text-white'>No books found</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'footer.php';
?>
