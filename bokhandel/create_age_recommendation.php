<?php
include 'Includes/header.php';


if (!isset($_SESSION["uname"])) {
    $user->redirect("login.php");
}


if ($_SESSION["urole"] != "Admin") {
    $user->redirect("index.php");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['age_recommendation'])) {

        $ageRecommendation = $_POST['age_recommendation'];
        $stmt = $conn->prepare("INSERT INTO AgeRecommendation (AgeRange) VALUES (?)");
        $stmt->bind_param("s", $ageRecommendation);
        if ($stmt->execute()) {

            $user->redirect("manage_age_recommendations.php");
        } else {

            echo "Error adding age recommendation: " . $stmt->error;
        }
    }
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Create Age Recommendation</h2>
        <form method="POST" class="bg-gray-100 rounded-lg p-8 shadow-md">
            <div class="mb-4">
                <label for="age_recommendation" class="block text-gray-700 font-semibold mb-2">Age Range:</label>
                <input type="range" id="age_recommendation" name="age_recommendation" min="0" max="120" class="w-full h-2 bg-gray-200 rounded-md appearance-none cursor-pointer" step="1" list="age_recommendation_ticks">
                <datalist id="age_recommendation_ticks">
                    <option value="0">0</option>
                    <option value="6">6</option>
                    <option value="12">12</option>
                    <option value="18">18</option>
                    <option value="21">21</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                    <option value="60">60</option>
                    <option value="120">120</option>
                </datalist>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
<?php include 'Includes/footer.php'; ?>
