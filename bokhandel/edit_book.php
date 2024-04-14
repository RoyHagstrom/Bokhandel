<?php
include 'Includes/header.php';

function redirectToIndex() {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION["uid"]) || !isset($_GET["bookid"]) || !is_numeric($_GET["bookid"])) {
    redirectToIndex();
}

$stmt = $conn->prepare("SELECT * FROM Book WHERE BookID = ?");
$stmt->bind_param("i", $_GET["bookid"]);
$stmt->execute();
$bookData = $stmt->get_result()->fetch_assoc();
if (!$bookData) {
    redirectToIndex();
}

$categoriesQuery = $conn->query("SELECT id, name FROM categories");
$categories = $categoriesQuery->fetch_all(MYSQLI_ASSOC);

$statusesQuery = $conn->query("SELECT StatusID, StatusName FROM Status");
$statuses = $statusesQuery->fetch_all(MYSQLI_ASSOC);

$updateMessage = $updateError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['illustrator']) && isset($_POST['AgeRecommendation']) && isset($_POST['category']) && isset($_POST['Genre']) && isset($_POST['PublicationYear']) && isset($_POST['Publisher']) && isset($_POST['Price']) && isset($_POST['Pages']) && isset($_POST['status'])) {

        $stmt = $conn->prepare("UPDATE Book SET Title=?, Description=?, Illustrator=?, AgeRecommendation=?, Category=?, Genre=?, PublicationYear=?, Publisher=?, Price=?, Pages=?, StatusID=? WHERE BookID=?");
        $stmt->bind_param("ssssisssdiii", $_POST['title'], $_POST['description'], $_POST['illustrator'], $_POST['AgeRecommendation'], $_POST['category'], $_POST['Genre'], $_POST['PublicationYear'], $_POST['Publisher'], $_POST['Price'], $_POST['Pages'], $_POST['status'], $_GET["bookid"]);

        if ($stmt->execute()) {
            $stmt = $conn->prepare("SELECT * FROM Book WHERE BookID = ?");
            $stmt->bind_param("i", $_GET["bookid"]);
            $stmt->execute();
            $bookData = $stmt->get_result()->fetch_assoc();

            $updateMessage = "Book updated successfully.";
        } else {
            $updateError = "Error updating book: " . $stmt->error;
        }
    } else {
        $updateError = "Form data missing.";
    }
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Edit Book</h2>
        <?php if (!empty($updateMessage)) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4" role="alert">
                <?php echo $updateMessage; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($updateError)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4" role="alert">
                <?php echo $updateError; ?>
            </div>
        <?php endif; ?>
        <form class="bg-white shadow-md rounded-lg overflow-hidden" method="POST" action="" enctype="multipart/form-data">
            <div class="p-6">

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="title">Title:</label>
                    <input type="text" id="title" name="title" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($bookData['Title']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="description">Description:</label>
                    <textarea id="description" name="description" class="appearance-none border rounded-md py-2 px-4 w-full" rows="4" required><?php echo htmlspecialchars($bookData['Description']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="illustrator">Illustrator:</label>
                    <input type="text" id="illustrator" name="illustrator" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['illustrator']) ? htmlspecialchars($_POST['illustrator']) : htmlspecialchars($bookData['Illustrator']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="AgeRecommendation">Age Recommendation:</label>
                    <input type="text" id="AgeRecommendation" name="AgeRecommendation" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['AgeRecommendation']) ? htmlspecialchars($_POST['AgeRecommendation']) : htmlspecialchars($bookData['AgeRecommendation']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="category">Category:</label>
                    <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option>Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo isset($_POST['category']) && $_POST['category'] == $category['id'] ? 'selected' : ($bookData['Category'] == $category['id'] ? 'selected' : ''); ?>><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Genre">Genre:</label>
                    <input type="text" id="Genre" name="Genre" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['Genre']) ? htmlspecialchars($_POST['Genre']) : htmlspecialchars($bookData['Genre']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="PublicationYear">Publication Year:</label>
                    <input type="number" id="PublicationYear" name="PublicationYear" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['PublicationYear']) ? htmlspecialchars($_POST['PublicationYear']) : htmlspecialchars($bookData['PublicationYear']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Publisher">Publisher:</label>
                    <input type="text" id="Publisher" name="Publisher" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['Publisher']) ? htmlspecialchars($_POST['Publisher']) : htmlspecialchars($bookData['Publisher']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Price">Price:</label>
                    <input type="text" id="Price" name="Price" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['Price']) ? htmlspecialchars($_POST['Price']) : htmlspecialchars($bookData['Price']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Pages">Pages:</label>
                    <input type="text" id="Pages" name="Pages" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['Pages']) ? htmlspecialchars($_POST['Pages']) : htmlspecialchars($bookData['Pages']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="images/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="status">Status:</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Select Status</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo $status['StatusID']; ?>" <?php echo isset($_POST['status']) && $_POST['status'] == $status['StatusID'] ? 'selected' : ($bookData['StatusID'] == $status['StatusID'] ? 'selected' : ''); ?>><?php echo $status['StatusName']; ?></option>
                        <?php endforeach; ?>                    </select>
                </div>

                <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Update</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'Includes/footer.php';
?>