<?php include 'Includes/header.php'; ?>

<?php
if (!isset($_SESSION["uname"])) {
    $user->redirect("login.php");
}

if(isset($_SESSION["uid"]) && $_SESSION["urole"] != "Admin"){
    $userID = $_SESSION["uid"];
} elseif($_SESSION["urole"] == "Admin" && isset($_GET["uid"])){
    $userID = $_GET["uid"];
} elseif(isset($_GET["uid"])){
    $userID = $_SESSION["uid"];
} else{
    $userID = $_SESSION["uid"];
}

$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    $user->redirect("index.php");
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center p-8 text-black">
    

    
<div class="container mx-auto bg-white shadow-md rounded-lg p-8">
    <h2 class="text-3xl font-bold mb-4">Welcome, <?php echo $userData["Username"]; ?></h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4">
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-lg">User ID:</span>
            <span class="text-lg"><?php echo $userData['UserID']; ?></span>
        </div>
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-lg">Username:</span>
            <span class="text-lg"><?php echo $userData['Username']; ?></span>
        </div>
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-lg">Email:</span>
            <span class="text-lg"><?php echo $userData['Email']; ?></span>
        </div>
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-lg">Role:</span>
            <span class="text-lg"><?php echo $userData['Role']; ?></span>
        </div>
    </div>
    <h3 class="text-2xl font-bold mb-2 mt-8">Actions:</h3>
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
        <a href="create_book.php" class="btn bg-blue-500 text-white w-full sm:w-auto">Create a book</a>
        <a href="my_books.php?uid=<?= $userData['Username'] ?>" class="btn bg-blue-700 text-white w-full sm:w-auto">View my books</a>

        <?php if($userData['Role'] == "Admin"){ ?>
            <a href="register.php" class="btn bg-green-500 text-white w-full sm:w-auto">Add a user</a>
            <a href="manage_users.php" class="btn bg-green-500 text-white w-full sm:w-auto">Manage users</a>
            <a href="manage_publishers.php" class="btn bg-yellow-500 text-black w-full sm:w-auto">Manage publishers</a>
            <a href="manage_categories.php" class="btn bg-white text-gray-900 w-full sm:w-auto">Manage categories</a>
            <a href="manage_age_recommendations.php" class="btn bg-white text-gray-900 w-full sm:w-auto">Manage age recommendations</a>
            <a href="manage_series.php" class="btn bg-white text-gray-900 w-full sm:w-auto">Manage series</a>
            <a href="manage_genre.php" class="btn bg-white text-gray-900 w-full sm:w-auto">Manage genre</a>
        <?php } ?>
        
        <?php if($_SESSION['urole'] == "Admin"){ ?>
            <div class="mt-8 grid">
            <a href="edit_user.php?userid=<?php echo $userData['UserID']; ?>" class="btn bg-green-500 text-white w-full sm:w-auto mb-2">Edit user</a>
            <a href="delete_user.php?userid=<?php echo $userData['UserID']; ?>" class="btn bg-red-500 text-white w-full sm:w-auto mb-2" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">Delete user</a>
            </div>
            <?php } ?>

    </div>



<?php
$stmt = $conn->query("SELECT BookID, Title, Price, C.Name AS Category FROM Book B JOIN categories C ON B.Category = C.ID WHERE Author = '".$userData["Username"]."'");
if($stmt && $stmt->num_rows > 0){
    echo '<h3 class="text-2xl font-bold my-4 sm:mb-8 md:mb-12">Author\'s Books:</h3>
    <div class="overflow-x-auto md:overflow-x-visible">
    <table class="w-full text-sm sm:text-base border-collapse mt-2 text-black">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-100">Book ID</th>
                <th class="py-2 px-4 bg-gray-100">Title</th>
                <th class="py-2 px-4 bg-gray-100">Category</th>
                <th class="py-2 px-4 bg-gray-100">Price</th>
            </tr>
        </thead>
        <tbody>';
    while($book = $stmt->fetch_assoc()){
        echo '<tr>
                <td class="py-2 px-4 border-b">'.$book['BookID'].'</td>
                <td class="py-2 px-4 border-b"><a href="singlebook.php?id='.$book['BookID'].'" class="text-blue-500">'.$book['Title'].'</a></td>
                <td class="py-2 px-4 border-b">'.$book['Category'].'</td>
                <td class="py-2 px-4 border-b">'.$book['Price'].'€</td>
            </tr>';
    }
    echo '</tbody></table>
    </div>';
}else{
    echo '<p class="text-lg my-4 sm:my-8 md:my-12 italic">No books found for this author.</p>';
}
    
    if($userData['Role'] == "Admin"){ ?>


    <?php
    error_log("Start of toggle feature code");
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
        error_log("Request method is POST and submit button was clicked");
        if (!isset($books) || !is_array($books)) {
            $books = array();
            error_log("Variable books is not set or is not an array, initializing it as an empty array");
        }

        foreach ($books as $book) {
            error_log("Looping through books");
            $bookID = $book['BookID'];
            if (isset($_POST['featured'][$bookID])) {
                error_log("Book " . $bookID . " is set to featured");
                $featured = 1;
            } else {
                error_log("Book " . $bookID . " is not set to featured");
                $featured = 0;
            }
            
            $stmtFeature = $conn->prepare("UPDATE Book SET Featured = ? WHERE BookID = ?");
            error_log("Preparing statement to update book " . $bookID . " to " . ($featured == 1 ? "featured" : "not featured"));
            $stmtFeature->bind_param("ii", $featured, $bookID);
            $stmtFeature->execute();
            error_log("Statement executed");
            $stmtFeature->close();
        }
    }
    
    
    $stmt = $conn->query("SELECT Book.BookID, Title, Price, C.Name AS Category, Book.Featured FROM Book JOIN categories C ON Book.Category = C.ID");
    if ($stmt->num_rows > 0) {
        error_log("Found " . $stmt->num_rows . " books");
        echo '<h3 class="text-2xl font-bold my-4 sm:mb-8 md:mb-12 mt-8">All Books:</h3>
        <form method="post" action="?action=toggle">
            <div class="overflow-x-auto md:overflow-x-visible mt-2">
            <table class="w-full text-sm sm:text-base border-collapse mt-2 text-black">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-100">Book ID</th>
                        <th class="py-2 px-4 bg-gray-100">Title</th>
                        <th class="py-2 px-4 bg-gray-100">Category</th>
                        <th class="py-2 px-4 bg-gray-100">Price</th>
                        <th class="py-2 px-4 bg-gray-100">Featured</th>
                    </tr>
                </thead>
                <tbody>';
        while ($book = $stmt->fetch_assoc()) {
            error_log("Looping through book " . $book['BookID']);
            echo '<tr>
                    <td class="py-2 px-4 border-b">' . $book['BookID'] . '</td>
                    <td class="py-2 px-4 border-b"><a href="singlebook.php?id=' . $book['BookID'] . '" class="text-blue-500">' . $book['Title'] . '</a></td>
                    <td class="py-2 px-4 border-b">' . $book['Category'] . '</td>
                    <td class="py-2 px-4 border-b">' . $book['Price'] . '€</td>
                    <td class="py-2 px-4 border-b"><input type="checkbox" name="featured[' . $book['BookID'] . ']" value="1" ' . ($book['Featured'] == 1 ? 'checked' : '') . '></td></tr>';
        }
        echo '</tbody></table></div>
            <button type="submit" class="btn mt-2" name="submit">Submit</button>
        </form>';
    } else {
        error_log("Did not find any books");
        echo '<p class="text-lg my-4 sm:my-8 md:my-12 italic">No featured books found.</p>';
    }
    error_log("End of toggle feature code");
    ?>


    <?php } ?>
    

</div>
</div>
<?php include 'Includes/footer.php'; ?>

