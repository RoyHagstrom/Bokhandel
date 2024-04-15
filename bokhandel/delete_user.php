<?php
ob_start();
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}

if (!isset($_GET["userid"])) {
    $user->redirect("index.php");
}

$userid = $_GET["userid"];

$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();


if (!$userData) {
    $user->redirect("index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "DELETE FROM User WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userid);

    if ($stmt->execute()) {
        $user->redirect("account.php");
    } else {
        echo "Error deleting book: " . $stmt->error;
    }
    ob_end_flush();
    exit();
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Delete User</h2>
        <p class="pb-4">Are you sure you want to delete the user "<?php echo $userData['Username']; ?>"?</p>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?userid=" . $userid); ?>">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md">Delete</button>
            <a href="singlebook.php?id=<?php echo $userid;?>" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md ml-2">Cancel</a>
        </form>
    </div>
</div>

<?php
include 'Includes/footer.php';
ob_end_flush();
exit();
?>
