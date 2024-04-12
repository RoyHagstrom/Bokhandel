<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}

if (!isset($_GET["bookid"])) {
    // Redirect if no book id is provided
    header("Location: index.php");
    exit();
}

$bookid = $_GET["bookid"];

$stmt = $conn->prepare("SELECT * FROM Book WHERE BookID = ?");
$stmt->bind_param("i", $bookid);
$stmt->execute();
$result = $stmt->get_result();
$bookData = $result->fetch_assoc();

if (!$bookData) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "DELETE FROM Book WHERE BookID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookid);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting book: " . $stmt->error;
    }
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Delete Book</h2>
        <p>Are you sure you want to delete the book "<?php echo $bookData['Title']; ?>"?</p>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?bookid=" . $bookid); ?>">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md">Delete</button>
            <a href="singlebook.php?id=<?php echo $bookid;?>" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md ml-2">Cancel</a>
        </form>
    </div>
</div>

<?php
include 'Includes/footer.php';
?>
