<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}

$stmt = $conn->prepare("SELECT UserID FROM User WHERE Username = ?");
$stmt->bind_param("s", $_SESSION["uname"]);
$stmt->execute();
$userInfo = $stmt->get_result()->fetch_assoc();
$author = $_SESSION["uname"];

$stmt = $conn->prepare("SELECT id, name FROM categories order by name ASC");
$stmt->execute();
$categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT StatusID, StatusName FROM Status");
$stmt->execute();
$statuses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$ageRecommendationsQuery = $conn->query("SELECT AgeRecommendationID, AgeRange FROM AgeRecommendation ORDER BY CAST(AgeRange AS UNSIGNED) ASC");
if ($ageRecommendationsQuery === false) {
    throw new mysqli_sql_exception($conn->error, $conn->errno);
}
$ageRecommendations = $ageRecommendationsQuery->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : null;
    $description = isset($_POST["description"]) ? htmlspecialchars_decode($_POST["description"]) : null;
        
    $illustrator = isset($_POST["illustrator"]) ? htmlspecialchars($_POST["illustrator"]) : null;
    $ageRecommendation = isset($_POST["AgeRecommendation"]) ? htmlspecialchars($_POST["AgeRecommendation"]) : null;
    $categoryID = isset($_POST["category"]) ? intval($_POST["category"]) : null;
    $genre = isset($_POST["Genre"]) ? htmlspecialchars($_POST["Genre"]) : null;
    $publicationYear = isset($_POST["PublicationYear"]) ? intval($_POST["PublicationYear"]) : null;
    $series = isset($_POST["Series"]) ? htmlspecialchars($_POST["Series"]) : null;
    $publisher = isset($_POST["Publisher"]) ? htmlspecialchars($_POST["Publisher"]) : null;
    $price = isset($_POST["Price"]) ? floatval($_POST["Price"]) : null;
    $pages = isset($_POST["Pages"]) ? intval($_POST["Pages"]) : null;
    $statusID = isset($_POST["status"]) ? intval($_POST["status"]) : null;
    $featured = isset($_POST["featured"]) ? 1 : 0;
    
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("File upload failed with error code " . $_FILES['image']['error']);
    }

    $targetDir = "images/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        die("Error uploading image.");
    }

    $sql = "INSERT INTO Book (Title, Description, Author, Illustrator, AgeRecommendation, Category, Genre, PublicationYear, Series, Publisher, Price, Pages, Image, StatusID, Featured) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssi", $title, $description, $author, $illustrator, $ageRecommendation, $categoryID, $genre, $publicationYear, $series, $publisher, $price, $pages, $targetFile, $statusID, $featured);

    if ($stmt->execute()) {
        echo "Book created successfully.";
        header("Location: my_books.php");
    } else {
        echo "Error creating book: " . $stmt->error;
    }
}

?>


<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Create Book</h2>

        <form class="bg-white shadow-md rounded-lg overflow-hidden" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

            <div id="step1" class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" for="title">Title:</label>
                        <input type="text" id="title" name="title" class="appearance-none border rounded-md py-2 px-4 w-full" required>
                    </div>

                    <script>
                            tinymce.init({
                                selector: '#description',
                                height: 500,
                                plugins: 'advlist lists link image preview',
                                toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat',
                                content_style: "body { padding: 10px; }"
                            });
                    </script>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" for="description">Description:</label>
                        <div id="description" class="appearance-none border rounded-md py-2 px-4 w-full" required></div>
                    </div>
                    <div class="mb-4">
                        <button type="button" onclick="nextStep(2)" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Next</button>
                    </div>
                </div>
            </div>

            <div id="step2" class="bg-white shadow-md rounded-lg overflow-hidden" style="display: none;">
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" for="illustrator">Illustrator:</label>
                        <input type="text" id="illustrator" name="illustrator" class="appearance-none border rounded-md py-2 px-4 w-full">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" for="AgeRecommendation">Age Recommendation:</label>
                        <select id="AgeRecommendation" name="AgeRecommendation" class="appearance-none border rounded-md py-2 px-4 w-full">
                          <option value="">Select Age</option>
                            <?php foreach ($ageRecommendations as $ageRecommendation) : ?>
                                <option value="<?php echo $ageRecommendation['AgeRecommendationID']; ?>"><?php echo htmlspecialchars($ageRecommendation['AgeRange']); ?></option>
                            <?php endforeach; ?>
                        </select>
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
                        <button type="button" onclick="prevStep(1)" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">Previous</button>
                        <button type="button" onclick="nextStep(3)" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Next</button>
                    </div>
                </div>
            </div>

            <div id="step3" class="bg-white shadow-md rounded-lg overflow-hidden" style="display: none;">
                <div class="p-6">
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
                        <select id="Publisher" name="Publisher" class="appearance-none border rounded-md py-2 px-4 w-full">
                        <?php
                        $publisherQuery = $conn->query("SELECT PublisherID, Name FROM Publisher");
                        $publishers = $publisherQuery->fetch_all(MYSQLI_ASSOC);
                        ?><option value="">Select Publisher</option><?php
                        foreach ($publishers as $publisher) {
                            $selected = ($publisher['PublisherID'] == $bookData['Publisher']) ? 'selected' : '';
                            echo "<option value='{$publisher['Name']}' {$selected}>{$publisher['Name']}</option>";
                        }
                        ?>
                    </select>
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
                        <img id="image-preview" src="" alt="Image preview" class="max-h-48 w-full object-contain" style="display: none;">
                    </div>
                    <script>
                        document.getElementById('image').addEventListener('change', function() {
                            const preview = document.getElementById('image-preview');
                            const file = this.files[0];
                            const reader = new FileReader();
                            reader.addEventListener('load', function() {
                                preview.src = reader.result;
                                preview.style.display = 'block';
                            });
                            if (file) {
                                reader.readAsDataURL(file);
                            } else {
                                preview.style.display = 'none';
                            }
                        });
                    </script>
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
                        <button type="button" onclick="prevStep(2)" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">Previous</button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">Create</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include 'Includes/footer.php';
?>

<script>
    tinymce.init({
        selector: '#description',
        height: 500,
        plugins: 'advlist lists link image preview',
        toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat',
        content_style: "body { padding: 10px; }"
    });

    function nextStep(step) {
        document.getElementById('step' + step).style.display = 'block';
        document.getElementById('step' + (step - 1)).style.display = 'none';
    }

    function prevStep(step) {
        document.getElementById('step' + step).style.display = 'block';
        document.getElementById('step' + (step + 1)).style.display = 'none';
    }
</script>