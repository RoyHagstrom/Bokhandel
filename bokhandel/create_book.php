<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"] ?? null;
    $description = $_POST["description"] ?? null;
    $illustrator = $_POST["illustrator"] ?? null;
    $ageRecommendation = $_POST["AgeRecommendation"] ?? null;
    $categoryID = $_POST["category"] ?? null;
    $genre = $_POST["Genre"] ?? null;
    $publicationYear = !empty($_POST["PublicationYear"]) ? intval($_POST["PublicationYear"]) : null;
    $series = $_POST["Series"] ?? null;
    $publisher = $_POST["Publisher"] ?? null;
    $price = $_POST["Price"] ?? null;
    $pages = $_POST["Pages"] ?? null;
    $statusID = intval($_POST["status"]) ?? null;
    $featured = isset($_POST["featured"]) ? 1 : 0;

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("File upload failed with error code " . $_FILES['image']['error']);
    }

    $targetDir = "F:/xampp/htdocs/bokhandel/bokhandel/images/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if ($_FILES["image"]["size"] > 500000) {
        die("Sorry, your file is too large.");
    }

    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        die("Error uploading image.");
    }

    $stmt = $conn->prepare("INSERT INTO Book (Title, Description, Author, Illustrator, AgeRecommendation, Category, Genre, PublicationYear, Series, Publisher, Price, Pages, Image, StatusID, Featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param('ssisssssdsdsids', $title, $description, $author, $illustrator, $ageRecommendation, $categoryID, $genre, $publicationYear, $series, $publisher, $price, $pages, $targetFile, $statusID, $featured);

    if ($stmt->execute()) {
        echo "Book created successfully.";
    } else {
        echo "Error creating book: " . $stmt->error;
    }
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Create Book</h2>
        <form class="bg-white shadow-md rounded-lg overflow-hidden" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
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
                    <label class="block text-sm font-semibold mb-2" for="illustrator">Illustrator:</label>
                    <input type="text" id="illustrator" name="illustrator" class="appearance-none border rounded-md py-2 px-4 w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="AgeRecommendation">Age Recommendation:</label>
                    <input type="text" id="AgeRecommendation" name="AgeRecommendation" class="appearance-none border rounded-md py-2 px-4 w-full">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="category">Category:</label>
                    <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                    </select>

                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Genre">Genre:</label>
                    <input type="text" id="Genre" name="Genre" class="appearance-none border rounded-md py-2 px-4 w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="PublicationYear">Publication Year:</label>
                    <input type="number" id="PublicationYear" name="PublicationYear" class="appearance-none border rounded-md py-2 px-4 w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Publisher">Publisher:</label>
                    <input type="text" id="Publisher" name="Publisher" class="appearance-none border rounded-md py-2 px-4 w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Price">Price:</label>
                    <input type="text" id="Price" name="Price" class="appearance-none border rounded-md py-2 px-4 w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Pages">Pages:</label>
                    <input type="text" id="Pages" name="Pages" class="appearance-none border rounded-md py-2 px-4 w-full">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="images/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="status">Status:</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="">Select Status</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo $status['StatusID']; ?>"><?php echo $status['StatusName']; ?></option>
                        <?php endforeach; ?>
                    </select>
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