<?php
include 'Includes/header.php';

if (!isset($_SESSION["uname"])) {
    $user->redirect("login.php");
}

$stmt = $conn->prepare("SELECT UserID, Username FROM User");
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $authorID = $_POST["author"];

}

?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Create Book</h2>
        <form class="bg-white shadow-md rounded-lg overflow-hidden" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="title">Title:</label>
                    <input type="text" id="title" name="title" class="appearance-none border rounded-md py-2 px-4 w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="description">Description:</label>
                    <textarea id="description" name="description" class="appearance-none border rounded-md py-2 px-4 w-full" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="author">Author:</label>
                    <?php if ($_SESSION["urole"] == "Admin"): ?>
                        <select id="author" name="author" class="appearance-none border rounded-md py-2 px-4 w-full" required>
                            <option value="">Select Author</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['UserID']; ?>"><?php echo $user['Username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                    <?php if ($_SESSION["urole"] !== "Admin"): ?>
                        <input type="hidden" id="author" name="author" value="<?php echo $author; ?>">
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="illustrator">Illustrator:</label>
                    <input type="text" id="illustrator" name="illustrator" class="appearance-none border rounded-md py-2 px-4 w-full">
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>


<?php
include 'Includes/footer.php';
?>
