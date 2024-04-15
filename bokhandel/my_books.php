<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}




if(isset($_SESSION["uname"]) && $_SESSION["urole"] != "Admin"){
    $author = $_SESSION["uname"];    
    } 
    elseif($_SESSION["urole"] == "Admin" && isset($_GET["uname"])){
    $author = $_GET["uname"];    
    }
    elseif(isset($_GET["uid"])){
        $author = $_GET["uid"];
    }


$stmt = $conn->prepare("SELECT * FROM Book WHERE Author = ?");
$stmt->bind_param("s", $author);
$stmt->execute();
$result = $stmt->get_result();

?>
<div class="bg-white text-black w-dvw min-h-screen flex flex-col justify-center items-center p-8">

<div class="container mx-auto p-8 max-w-6xl">
    <h2 class="text-3xl font-bold mb-4"><?php echo $author; ?>'s books</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <?php
                echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block h-full bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-full h-80 object-cover">';
                echo '<div class="p-6">';
                echo '<h2 class="text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">' . substr($row['Description'], 0, 50) . '...</p>';
                echo '</div>';
                echo '</a>';
                ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<div class="mt-4 flex gap-2">
                <a href="create_book.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Create Book</a>
            </div>
</div>

<?php
include 'Includes/footer.php';
?>
