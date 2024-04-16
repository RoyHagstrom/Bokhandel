<?php
include 'Includes/header.php';


if (!isset($_SESSION["uname"])) {
    $user->redirect("login.php");
}


if ($_SESSION["urole"] != "Admin") {
    $user->redirect("index.php");
}


$stmt = $conn->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['category_name'])) {
        $categoryName = $_POST['category_name'];
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $categoryName);
        if ($stmt->execute()) {
            $user->redirect("manage_categories.php");
        } else {
            echo "Error adding category: " . $stmt->error;
        }
    }
}

?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Manage Categories</h2>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2">Add New Category</h3>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" for="category_name">Category Name:</label>
                        <input type="text" id="category_name" name="category_name" class="appearance-none border rounded-md py-2 px-4 w-full">
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">Add Category</button>
                </form>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-4">
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2">Existing Categories</h3>
                <ul class="list-disc list-inside">
                    <?php foreach ($categories as $category): ?>
                        <li><?php echo $category['name']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'Includes/footer.php'; ?>
