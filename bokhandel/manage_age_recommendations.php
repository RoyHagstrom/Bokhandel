<?php
include 'Includes/header.php';


if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}
elseif($_SESSION["urole"] != "Admin"){
    $user->redirect("login.php");

}

$ageRecommendationsQuery = $conn->query("SELECT * FROM AgeRecommendation ORDER BY CAST(AgeRange AS UNSIGNED) ASC");
$ageRecommendations = $ageRecommendationsQuery->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save_age_recommendation'])) {
        $ageRecommendationID = $_POST['age_recommendation_id'];
        $ageRange = $_POST['age_range'];

        if (!empty($ageRecommendationID)) {

            $stmt = $conn->prepare("UPDATE AgeRecommendation SET AgeRange = ? WHERE AgeRecommendationID = ?");
            $stmt->bind_param("si", $ageRange, $ageRecommendationID);

        } else {

            $stmt = $conn->prepare("INSERT INTO AgeRecommendation (AgeRange) VALUES (?)");
            $stmt->bind_param("s", $ageRange);
        }

        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } 
    } elseif (isset($_POST['edit_age_recommendation'])) {
        $ageRecommendationID = $_POST['age_recommendation_id'];
        $stmt = $conn->prepare("SELECT AgeRange FROM AgeRecommendation WHERE AgeRecommendationID = ?");
        $stmt->bind_param("i", $ageRecommendationID);
        $stmt->execute();
        $result = $stmt->get_result();
        $ageRecommendation = $result->fetch_assoc();
        $_POST['age_range'] = $ageRecommendation['AgeRange'];
        $_POST['age_recommendation_id'] = $ageRecommendationID;
    } elseif (isset($_POST['delete_age_recommendation'])) {
        $ageRecommendationID = $_POST['age_recommendation_id'];

        $stmt = $conn->prepare("DELETE FROM AgeRecommendation WHERE AgeRecommendationID = ?");
        $stmt->bind_param("i", $ageRecommendationID);
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } 
    }
}

?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold mb-4">Manage Age Recommendations</h2>
 

        <form class="mb-4" method="POST" action="">
            <div class="flex items-center mb-4">
                <input type="hidden" name="age_recommendation_id" value="<?php echo isset($_POST['age_recommendation_id']) ? $_POST['age_recommendation_id'] : ''; ?>">
                <label for="age_range" class="mr-2 font-semibold">Age Range:</label>
                <input type="text" id="age_range" name="age_range" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo isset($_POST['age_range']) ? $_POST['age_range'] : ''; ?>" required>
                <button type="submit" name="save_age_recommendation" class="bg-green-500 hover:bg-green-600 text-white py-1 px-2 ml-2 rounded-md">Save</button>
            </div>
        </form>

        <h3 class="text-xl font-semibold mb-2">Existing Age Recommendations:</h3>
        <ul>
            <?php foreach ($ageRecommendations as $ageRecommendation) : ?>
                <li class="flex items-center justify-between mb-2">
                    <span><?php echo $ageRecommendation['AgeRange']; ?></span>
                    <form method="POST" action="">
                        <input type="hidden" name="age_recommendation_id" value="<?php echo $ageRecommendation['AgeRecommendationID']; ?>">
                        <button type="submit" name="edit_age_recommendation" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 rounded-md">Edit</button>
                        <button type="submit" name="delete_age_recommendation" class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 ml-2 rounded-md">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include 'Includes/footer.php'; ?>
