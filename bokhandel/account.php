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

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center p-8">
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
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-2">
        <a href="create_book.php" class="btn btn-primary w-full sm:w-auto">Create Book</a>
        <a href="my_books.php?uid=<?= $userData['Username'] ?>" class="btn btn-primary w-full sm:w-auto">View Books</a>

        <?php if($userData['Role'] == "Admin"){ ?>
            <a href="register.php" class="btn btn-success w-full sm:w-auto">Create User</a>
            <a href="manage_users.php" class="btn btn-success w-full sm:w-auto">Manage Users</a>
            <a href="create_publisher.php" class="btn btn-yellow-500 w-full sm:w-auto">Create Publisher</a>
            <a href="manage_publishers.php" class="btn btn-yellow-500 w-full sm:w-auto">Manage Publishers</a>
            <a href="manage_categories.php" class="btn w-full sm:w-auto">Manage Categories</a>
            <a href="manage_age_recommendations.php" class="btn w-full sm:w-auto">Manage Age Recommendations</a>
        
        <?php } ?>
        <?php if($_SESSION['urole'] == "Admin"){ ?>
            <a href="delete_user.php?userid=<?php echo $userData['UserID']; ?>" class="btn btn-error w-full sm:w-auto">Delete User</a>
        <?php } ?>

    </div>
</div>
</div>
<?php include 'Includes/footer.php'; ?>

