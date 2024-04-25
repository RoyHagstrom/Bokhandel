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
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ? ORDER BY name ASC");
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
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName = bin2hex(random_bytes(8)) . '.' . $ext;
            $targetDirectory = 'images/category';
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            $targetPath = 'images/category' . $fileName;
            if (move_uploaded_file($tmpName, $targetPath)) {
                $imagePath = $targetPath;
            }
        }

        $categoryName = $_POST['category_name'];
        $featured = $_POST['featured'];
        if (isset($_POST['edit_category'])) {
            $categoryId = $_POST['edit_category'];
            if ($imagePath !== null) {
                $stmt = $conn->prepare("UPDATE categories SET name = ?, featured = ?, image = ? WHERE id = ?");
                $stmt->bind_param("sisi", $categoryName, $featured, $imagePath, $categoryId);
            } else {
                $stmt = $conn->prepare("UPDATE categories SET name = ?, featured = ? WHERE id = ?");
                $stmt->bind_param("sii", $categoryName, $featured, $categoryId);
            }
        } else {
            if ($imagePath !== null) {
                $stmt = $conn->prepare("INSERT INTO categories (name, featured, image) VALUES (?, ?, ?)");
                $stmt->bind_param("sis", $categoryName, $featured, $imagePath);
            } else {
                $stmt = $conn->prepare("INSERT INTO categories (name, featured) VALUES (?, ?)");
                $stmt->bind_param("si", $categoryName, $featured);
            }
        }
        if ($stmt->execute()) {
            header('Location: manage_categories.php');
            exit();
        } else {
            echo "Error adding/editing category: " . $stmt->error;
        }
    }
}
?>




<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">

<form method="POST" action="" class="container space-y-8 bg-white p-8 shadow-md rounded-lg mt-8" enctype="multipart/form-data">
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
            <label for="image" class="block text-lg font-medium mb-2">Upload Image:</label>
            <input type="file" id="image" name="image" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" accept="image/*">
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
            <form method="POST" action="" class="container space-y-8 bg-white p-8 rounded-lg mt-8" enctype="multipart/form-data">
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
                        <label for="image" class="block text-sm font-semibold mb-2">Upload Image:</label>
                        <input type="file" id="image" name="image" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        
                        <div class="flex flex-wrap">

                        <?php
                        echo '<div class="m-2 md:m-4 relative rounded-lg">';
                        echo '<a href="books.php?id=' . htmlspecialchars($category['id'], ENT_QUOTES) . '">'; 
                        echo '<img src="' . htmlspecialchars($category['image'], ENT_QUOTES) . '" alt="' . htmlspecialchars($category['name'], ENT_QUOTES) . '" class="mt-2 w-28 h-28 sm:w-40 sm:h-40 lg:w-64 lg:h-64 object-cover rounded-lg opacity-70">';
                        echo '<div class="absolute inset-0 flex items-center justify-center text-white text-sm lg:text-3xl font-semibold bg-black bg-opacity-50 rounded-lg">' . htmlspecialchars($category['name'], ENT_QUOTES) . '</div>';
                        echo '</a>';
                        echo '</div>';
                        ?>
                        </div>

                    <div class="flex items-center justify-end space-x-2">
                        <button type="submit" name="delete_category" value="<?php echo $category['id']; ?>" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">Delete Category</button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">Save Changes</button>
                    </div>
                </form>
            </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
<?php 


include 'Includes/footer.php'; 
?>
