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

$category = null; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit_category'])) {
        $categoryId = $_POST['edit_category'];
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ? sort by name ASC");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $category = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }

    if (isset($_POST['delete_category'])) {
        $categoryId = $_POST['delete_category'];
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $categoryId);
        if ($stmt->execute()) {
            $user->redirect("manage_categories.php");
        } else {
            echo "Error deleting category: " . $stmt->error;
        }
    }

    if (isset($_POST['category_name'])) {
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName = bin2hex(random_bytes(8)) . '.' . $ext;
            $targetPath = 'images/' . $fileName;
            if (move_uploaded_file($tmpName, $targetPath)) {
                $imagePath = $targetPath;
            }
        }
        ob_start();
        $categoryName = $_POST['category_name'];
        $featured = $_POST['featured'];
        if (isset($_POST['edit_category'])) {
            $categoryId = $_POST['edit_category'];
            $stmt = $conn->prepare("UPDATE categories SET name = ?, featured = ?, image = ? WHERE id = ?");
            $stmt->bind_param("sisi", $categoryName, $featured, $imagePath, $categoryId);
        } else {
            $stmt = $conn->prepare("INSERT INTO categories (name, featured, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $categoryName, $featured, $imagePath);
        }
        if ($stmt->execute()) {
            if (ob_get_clean() === false) {
                header('Location: manage_categories.php');
                exit();
            }
        } else {
            echo "Error adding/editing category: " . $stmt->error;
        }
    }
}
?>




<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">

<form method="POST" action="" class="container space-y-8 bg-white p-8 shadow-md rounded-lg mt-8">
    <h2 class="text-2xl font-semibold mb-6">Create Category</h2>

    <div class="space-y-6">
        <div>
            <label for="category_name" class="block text-lg font-medium mb-2">Category Name:</label>
            <input type="text" id="category_name" name="category_name" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required placeholder="Enter category name">
        </div>

        <div>
            <label for="featured" class="block text-lg font-medium mb-2">Featured:</label>
            <select id="featured" name="featured" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>

        <div>
            <label for="image" class="block text-lg font-medium mb-2">Image URL:</label>
            <input type="text" id="image" name="image" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required placeholder="Enter image URL">
        </div>
    </div>

    <div class="flex items-center justify-end mt-8">
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">Create Category</button>
    </div>
</form>

<hr class="my-8 border-t border-gray-300">


    <div class="container mx-auto p-8 space-y-8">
        <h2 class="text-3xl font-bold mb-4">Manage Categories</h2>

        <?php if ($categories): foreach ($categories as $category): ?>
            <div class="bg-white shadow-md rounded-lg p-6">
                <form method="POST" action="" class="space-y-6">
                    <input type="hidden" name="edit_category" value="<?php echo $category['id']; ?>">
                    <h3 class="text-xl font-semibold mb-2">Edit <?php echo $category['name']; ?></h3>

                    <div>
                        <label for="category_name" class="block text-sm font-semibold mb-2">Category Name:</label>
                        <input type="text" id="category_name" name="category_name" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="<?php echo $category['name']; ?>" required>
                    </div>

                    <div>
                        <label for="featured" class="block text-sm font-semibold mb-2">Featured:</label>
                        <select id="featured" name="featured" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="1" <?php echo $category['featured'] ? 'selected' : ''; ?>>Yes</option>
                            <option value="0" <?php echo !$category['featured'] ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-semibold mb-2">Image URL or Upload Image:</label>
                        <input type="file" id="image" name="image" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">If you upload an image, it will overwrite the image URL</p>
                        <img src="<?php echo htmlspecialchars($category['image'], ENT_QUOTES); ?>" id="category-image" alt="Category Image" class="mt-2 w-64 h-48 object-cover">
                    </div>

                    <div class="flex items-center justify-end space-x-2">
                        <button type="submit" name="delete_category" value="<?php echo $category['id']; ?>" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">Delete Category</button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">Save Changes</button>
                    </div>
                </form>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>
<?php 

$includePath = 'Includes/footer.php';
if (!file_exists($includePath)) {
    die("The file '$includePath' was not found.");
}
include $includePath; 
?>
