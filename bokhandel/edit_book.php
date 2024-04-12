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

$stmt = $conn->prepare("SELECT id, name FROM categories");
$stmt->execute();
$categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT StatusID, StatusName FROM Status");
$stmt->execute();
$statuses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST["title"]) ? $_POST["title"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;
    $illustrator = isset($_POST["illustrator"]) ? $_POST["illustrator"] : null;
    $ageRecommendation = isset($_POST["AgeRecommendation"]) ? $_POST["AgeRecommendation"] : null;
    $categoryID = isset($_POST["category"]) ? $_POST["category"] : null;
    $genre = isset($_POST["Genre"]) ? $_POST["Genre"] : null;
    $publicationYear = isset($_POST["PublicationYear"]) ? intval($_POST["PublicationYear"]) : null;
    $series = isset($_POST["Series"]) ? $_POST["Series"] : null;
    $publisher = isset($_POST["Publisher"]) ? $_POST["Publisher"] : null;
    $price = isset($_POST["Price"]) ? $_POST["Price"] : null;
    $pages = isset($_POST["Pages"]) ? $_POST["Pages"] : null;
    $statusID = isset($_POST["status"]) ? intval($_POST["status"]) : null;
    $featured = isset($_POST["featured"]) ? 1 : 0;


    $sql = "UPDATE Book 
            SET Title=?, Description=?, Illustrator=?, AgeRecommendation=?, Category=?, Genre=?, PublicationYear=?, Series=?, Publisher=?, Price=?, Pages=?, StatusID=?, Featured=? 
            WHERE BookID=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssissssssi", $title, $description, $illustrator, $ageRecommendation, $categoryID, $genre, $publicationYear, $series, $publisher, $price, $pages, $statusID, $featured, $bookid);

    if ($stmt->execute()) {
        echo "Book updated successfully.";
    } else {
        echo "Error updating book: " . $stmt->error;
    }
}
?>


<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Edit Book</h2>
        <form class="bg-white shadow-md rounded-lg overflow-hidden" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="title">Title:</label>
                    <input type="text" id="title" name="title" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php if (isset($bookData)) {echo $bookData['Title'];} ?>" >
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="description">Description:</label>
                    <textarea id="description" name="description" class="appearance-none border rounded-md py-2 px-4 w-full" rows="4" ><?php if (isset($bookData)) {echo $bookData['Description'];} ?></textarea>
                    <script>
                    tinymce.init({
                        selector: 'textarea',
                        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
                        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                        tinycomments_mode: 'embedded',
                        tinycomments_author: 'Author name',
                        mergetags_list: [
                        { value: 'First.Name', title: 'First Name' },
                        { value: 'Email', title: 'Email' },
                        ],
                        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
                    });
                    </script>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="illustrator">Illustrator:</label>
                    <input type="text" id="illustrator" name="illustrator" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php if (isset($bookData['Illustrator'])) {echo $bookData['Illustrator'];} ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="AgeRecommendation">Age Recommendation:</label>
                    <input type="text" id="AgeRecommendation" name="AgeRecommendation" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php if (isset($bookData['AgeRecommendation'])) {echo $bookData['AgeRecommendation'];} ?>">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="category">Category:</label>
                    <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php if (isset($bookData['Category'])) {echo $bookData['Category'];} ?>" >
                    <option>Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                    </select>

                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Genre">Genre:</label>
                    <input type="text" id="Genre" name="Genre" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php if (isset($bookData['Genre'])) {echo $bookData['Genre'];} ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="PublicationYear">Publication Year:</label>
                    <input type="number" id="PublicationYear" name="PublicationYear" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php if (isset($bookData['PublicationYear'])) {echo $bookData['PublicationYear'];} ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Publisher">Publisher:</label>
                    <input type="text" id="Publisher" name="Publisher" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php if (isset($bookData['Publisher'])) {echo $bookData['Publisher'];} ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Price">Price:</label>
                    <input type="text" id="Price" name="Price" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php if (isset($bookData['Price'])) {echo $bookData['Price'];} ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Pages">Pages:</label>
                    <input type="text" id="Pages" name="Pages" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php if (isset($bookData['Pages'])) {echo $bookData['Pages'];} ?>">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="images/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="status">Status:</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php if (isset($bookData['StatusID'])) {echo $bookData['StatusID'];} ?>" >
                        <option value="">Select Status</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo $status['StatusID']; ?>"><?php echo $status['StatusName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include 'Includes/footer.php';
?>