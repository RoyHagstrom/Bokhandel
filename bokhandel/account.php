<?php include 'Includes/header.php'; ?>

<?php
if (!isset($_SESSION["uname"])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION["uid"];

$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">

<div class="container mx-auto p-8">
    <h2 class="text-3xl font-bold mb-4">Welcome, <?php echo $_SESSION["uname"]; ?></h2>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-semibold mb-2">Your Account Information:</h3>
            <div class="grid grid-cols-2 gap-y-4">
                <div class="flex items-center">
                    <span class="font-semibold mr-2">User ID:</span>
                    <span><?php echo $userData['UserID']; ?></span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2">Username:</span>
                    <span><?php echo $userData['Username']; ?></span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2">Email:</span>
                    <span><?php echo $userData['Email']; ?></span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2">Role:</span>
                    <span><?php echo $userData['Role']; ?></span>
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <a href="create_book.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Create Book</a>
                <a href="view_books.php" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">View Books</a>
            </div>
        </div>
    </div>
</div>
</div>


<?php include 'Includes/footer.php'; ?>
