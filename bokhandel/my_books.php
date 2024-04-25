<?php
include 'Includes/header.php';







    $author = $_GET["uid"];    



$stmt = $conn->prepare("SELECT * FROM Book WHERE Author = ? ORDER BY BookID DESC");
$stmt->bind_param("s", $author);
$stmt->execute();
$result = $stmt->get_result();


$user_bio_sql = "SELECT * FROM User WHERE Username = '{$author}'";
$user_bio_result = $conn->query($user_bio_sql);
$user_bio = $user_bio_result->fetch_assoc();


?>
<div class="bg-white text-black w-dvw min-h-screen flex flex-col justify-center items-center p-8">

<div class="container mx-auto sm:p-8 w-full md:max-w-6xl">
    <h2 class="text-3xl font-bold mb-4"><?php echo $author; ?>'s books</h2>

    <?php if (!empty($user_bio["Bio"])): ?>
    <div class="p-4 rounded-lg w-full m-4 bg-gray-100">
        <a href="my_books.php?uid=<?php echo $user_bio['Username']; ?>">
            <h1 class=" font-semibold mb-6 text-center text-black sm:text-3xl text-2xl"><?php echo $user_bio['Username'] ?></h1>

            <?php if (!empty($user_bio['Image'])): ?>
                <img src="<?php echo $user_bio['Image']; ?>" alt="<?php echo $user_bio['Username']; ?>" class="float-left w-auto h-40 rounded-lg mr-2 mb-2">
            <?php endif; ?>
            <p class="h-40"><?php echo $user_bio["Bio"]; ?></p>
        </a>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <a href="singlebook.php?id=<?php echo $row['BookID']; ?>" class="block h-full bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300">
                <div class="relative h-auto">
                    <img src="<?php echo $row['Image']; ?>" alt="<?php echo $row['Title']; ?>" class="object-cover w-full h-auto max-h-80">
                    <div class="absolute top-0 right-0 p-2">
                        <span class="bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg"><?php echo $row['Price']; ?>€</span>
                    </div>
                </div>
                <div class="p-2 sm:p-6">
                        <h2 class="sm:text-xl md:text-2xl font-semibold text-gray-900 dark:text-white"><?php echo $row['Title']; ?></h2>
                        <p class="text-sm md:text-base text-gray-700 dark:text-gray-300">Author: <?php echo $row['Author']; ?></p>
                        <p class="text-sm md:text-base text-gray-700 dark:text-gray-300"><?php echo substr($row['Description'], 0, 50) . '...'; ?></p>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="mt-4 flex gap-2">
        <a href="create_book.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Create Book</a>
    </div>
</div>
</div>
</div>
</div>

<?php
include 'Includes/footer.php';
?>
