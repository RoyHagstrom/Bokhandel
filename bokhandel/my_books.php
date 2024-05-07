<?php
include 'Includes/header.php';







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


/*$stmt_rating = $conn->prepare("SELECT Rating FROM Book WHERE Author = ?");
$stmt_rating->bind_param("s", $author);
$stmt_rating->execute();
$result_rating = $stmt_rating->get_result();
*/

?>
<div class="bg-white text-black w-dvw min-h-screen flex flex-col justify-center items-center p-8">

<div class="container mx-auto sm:p-8 w-full md:max-w-6xl">
    <h2 class="text-3xl font-bold mb-4"><?php echo $author; ?>'s books</h2>

    <?php if (!empty($user_bio["Bio"])): ?>
    <div class="p-4 rounded-lg w-full my-4 bg-gray-100 min-h-64 shadow-md">
        <a href="my_books.php?uid=<?php echo $user_bio['Username']; ?>" class="flex flex-col items-center">
            <h1 class="mt-4 font-semibold text-3xl text-center text-black sm:mt-6"><?php echo $user_bio['Username'] ?></h1>

            <?php if (!empty($user_bio['Image'])): ?>
                <img src="<?php echo $user_bio['Image']; ?>" alt="<?php echo $user_bio['Username']; ?>" class="mt-2 w-64 h-64 object-cover rounded-full">
            <?php endif; ?>

            <?php 
            $total_rating = 0;
            $total_ratings = 0;

            while($rating = $result->fetch_assoc()){
                $total_rating += $rating['Rating'];
                $total_ratings++;
            }

            if($total_ratings != 0){
                $avg_rating = round($total_rating/$total_ratings, 1);
                echo '<p class="mt-4 text-lg text-center leading-relaxed sm:mt-6 sm:text-xl">Average Rating: '.$avg_rating.'</p>';
            }
            ?>
            
            <div class="mt-4 max-w-[60ch] text-lg text-center leading-relaxed sm:mt-6 sm:text-xl">
                <?php echo $user_bio["Bio"]; ?>
            </div>
        </a>
    </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-4">
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
