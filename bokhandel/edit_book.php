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

$ageRecommendationsQuery = $conn->query("SELECT AgeRecommendationID, AgeRange FROM AgeRecommendation ORDER BY CAST(AgeRange AS UNSIGNED) ASC");
$ageRecommendations = $ageRecommendationsQuery->fetch_all(MYSQLI_ASSOC);

$categoriesQuery = $conn->query("SELECT id, name FROM categories");
$categories = $categoriesQuery->fetch_all(MYSQLI_ASSOC);

$seriesQuery = $conn->query("SELECT SeriesID, SeriesName FROM Series");
$series = $seriesQuery->fetch_all(MYSQLI_ASSOC);

$genreQuery = $conn->query("SELECT GenreID, GenreName FROM Genres ORDER BY GenreID ASC");
$genres = $genreQuery->fetch_all(MYSQLI_ASSOC);

$statusesQuery = $conn->query("SELECT StatusID, StatusName FROM Status");
$statuses = $statusesQuery->fetch_all(MYSQLI_ASSOC);

$updateMessage = $updateError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['title'], $_POST['description'], $_POST['illustrator'], $_POST['AgeRecommendation'], $_POST['category'], $_POST['Genre'], $_POST['PublicationYear'], $_POST['Publisher'], $_POST['Price'], $_POST['Pages'], $_POST['status'])) {
        $title = trim($_POST['title']);
        $description = $_POST['description'];
        $ageRecommendation = $_POST['AgeRecommendation'];
        $category = $_POST['category'];
        $genre = $_POST['Genre'];
        $publicationYear = $_POST['PublicationYear'];
        $publisher = $_POST['Publisher'];
        $price = $_POST['Price'];
        $pages = $_POST['Pages'];
        $statusID = $_POST['status'];
        $series = trim($_POST['newSeries']);
        $illustrator = isset($_POST['illustrator']) ? trim($_POST['illustrator']) : ''; 

        $author = $bookData['Author'];

        $series = trim($_POST['SeriesID']); 

        $featured = isset($_POST['featured']) ? 1 : 0; 
        $stmt = $conn->prepare("UPDATE Book SET Title=?, Description=?, Author=?, Illustrator=?, AgeRecommendation=?, Category=?, Genre=?, PublicationYear=?, Series=?, Publisher=?, Price=?, Pages=?, StatusID=?, Featured=? WHERE BookID=?");
        $stmt->bind_param("ssssssssssdisii", $title, $description, $author, $illustrator, $ageRecommendation, $category, $genre, $publicationYear, $series, $publisher, $price, $pages, $statusID, $featured, $_GET["bookid"]);

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "images/";
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $stmtImage = $conn->prepare("UPDATE Book SET Image=? WHERE BookID=?");
                $stmtImage->bind_param("si", $targetFile, $_GET["bookid"]);
                $stmtImage->execute() or die($stmtImage->error);
            }
        }

        if ($stmt->execute()) {
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
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded-md" role="alert">
            <?php echo $updateMessage ?>    
            <button 
                type="button" 
                onclick="window.location.href='singlebook.php?id=<?php echo $_GET["bookid"]; ?>'"
                class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Go to book
                </button>
            </div>
        <?php endif; ?>
        <?php if (!empty($updateError)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded-md" role="alert">
                <?php echo $updateError; ?>
            </div>
        <?php endif; ?>

        <form id="editBookForm" class="bg-white shadow-md rounded-lg overflow-hidden" method="POST" action="" enctype="multipart/form-data">
            <div id="step1" class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="title">Title:</label>
                    <input type="text" id="title" name="title" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($bookData['Title']); ?>">
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
                    <textarea id="description" name="description" class="appearance-none border rounded-md py-2 px-4 w-full" rows="4" required><?php echo htmlspecialchars($bookData['Description']); ?></textarea>
                </div>
                <?php if($_SESSION["urole"] == "Admin"){ ?>
                <div class="mb-4">
                    <input type="checkbox" id="featured" name="featured" <?php if($bookData['Featured'] == 1){ echo 'checked'; } else { echo 'unchecked'; } ?>>
                    <label class="ml-2" for="featured">Featured</label>
                </div>
                <?php } ?>
                <button type="button" onclick="nextStep(2)" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Next</button>
            </div>

            <div id="step2" class="p-6" style="display: none;">
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="illustrator">Illustrator:</label>
                    <input type="text" id="illustrator" name="illustrator" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['illustrator']) ? htmlspecialchars($_POST['illustrator']) : htmlspecialchars($bookData['Illustrator']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="AgeRecommendation">Age Recommendation:</label>
                    <select id="AgeRecommendation" name="AgeRecommendation" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Select Age</option>
                        <?php foreach ($ageRecommendations as $ageRecommendation) : ?>
                            <option value="<?php echo htmlspecialchars($ageRecommendation['AgeRange']); ?>"
                                <?php echo isset($_POST['AgeRecommendation']) && $_POST['AgeRecommendation'] == $ageRecommendation['AgeRange'] ? 'selected' :
                                    ($bookData['AgeRecommendation'] == $ageRecommendation['AgeRange'] ? 'selected' : ''); ?>>
                                <?php echo htmlspecialchars($ageRecommendation['AgeRange']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="category">Category:</label>
                    <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option>Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo isset($_POST['category']) && $_POST['category'] == $category['id'] ? 'selected' : ($bookData['Category'] == $category['id'] ? 'selected' : ''); ?>><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Genre">Genre:</label>
                    <select id="Genre" name="Genre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Select Genre</option>
                        <?php foreach ($genres as $genre): ?>


                            <option value="<?php echo $genre['GenreName']; ?>" <?php echo isset($_POST['genre']) && $_POST['genre'] == $genre['GenreID'] ? 'selected' : (isset($bookData['Genre']) && $bookData['Genre'] == $genre['GenreName'] ? 'selected' : ''); ?>>
                                <?php echo $genre['GenreName']; ?>
                            </option>


                        <?php endforeach; ?>

                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Series">Select Series (or add new):</label>
                    <select id="Series" name="SeriesID" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Select Series</option>
                        <?php foreach ($series as $s): ?>
                            <option value="<?php echo $s['SeriesName']; ?>" <?php echo (isset($_POST['series']) && $_POST['series'] == $s['SeriesName']) || (isset($bookData['SeriesID']) && $bookData['SeriesID'] == $s['SeriesName']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s['SeriesName'], ENT_QUOTES); ?>
                            </option>
                        <?php endforeach; ?> 
                    </select>

                    <input type="text" id="newSeries" name="newSeries" class="hidden bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Add new Series">
                    <button type="button" onclick="toggleInput()" class="inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mt-2">Add new Series</button>
                </div>

                <script>
                    function toggleInput() {
                        const select = document.querySelector("#Series");
                        const input = document.querySelector("#newSeries");
                        select.style.display = 'none';
                        input.classList.remove('hidden');
                        input.focus();
                    }

                    document.querySelector("#newSeries").addEventListener('blur', function() {
                        const select = document.querySelector("#Series");
                        const input = document.querySelector("#newSeries");
                        select.style.display = 'block';
                        input.classList.add('hidden');
                        input.value = '';
                    });
                </script>
                </script>
                <button type="button" onclick="prevStep(1)" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">Previous</button>
                <button type="button" onclick="nextStep(3)" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Next</button>
            </div>
            


            <div id="step3" class="p-6" style="display: none;">
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="PublicationYear">Publication Year:</label>
                    <input type="number" id="PublicationYear" name="PublicationYear" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['PublicationYear']) ? htmlspecialchars($_POST['PublicationYear']) : htmlspecialchars($bookData['PublicationYear']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="Publisher">Publisher:</label>
                    <select id="Publisher" name="Publisher" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <?php
                        $publisherQuery = $conn->query("SELECT PublisherID, Name FROM Publisher");
                        $publishers = $publisherQuery->fetch_all(MYSQLI_ASSOC);
                        ?><option value="">Select Publisher</option><?php
                        foreach ($publishers as $publisher) {
                            $selected = ($publisher['Name'] == $bookData['Publisher']) ? 'selected' : '';
                            echo "<option value='{$publisher['Name']}' {$selected}>{$publisher['Name']}</option>";
                        }
                        ?>
                    </select>
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
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Select Status</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo $status['StatusID']; ?>" <?php echo isset($_POST['status']) && $_POST['status'] == $status['StatusID'] ? 'selected' : ($bookData['StatusID'] == $status['StatusID'] ? 'selected' : ''); ?>><?php echo $status['StatusName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" onclick="prevStep(2)" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">Previous</button>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md float-right m-4">Update</button>
        </form>
    </div>
</div>

<script>
    function nextStep(step) {
        document.getElementById('step' + step).style.display = 'block';
        document.getElementById('step' + (step - 1)).style.display = 'none';
    }

    function prevStep(step) {
        document.getElementById('step' + step).style.display = 'block';
        document.getElementById('step' + (step + 1)).style.display = 'none';
    }
</script>


<?php
include 'Includes/footer.php';
?>
