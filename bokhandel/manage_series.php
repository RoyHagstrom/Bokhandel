<?php
include 'Includes/header.php';

if (!isset($_SESSION["uname"])) {
    $user->redirect("login.php");
}

if ($_SESSION["urole"] != "Admin") {
    $user->redirect("index.php");
}

$stmt = $conn->prepare("SELECT * FROM Series ORDER BY SeriesName ASC");
$stmt->execute();
$series = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$seriesData = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['edit_series'])) {

        $seriesId = $_POST['edit_series'];
        $stmt = $conn->prepare("SELECT * FROM Series WHERE SeriesID = ?");
        $stmt->bind_param("i", $seriesId);
        $stmt->execute();
        $seriesData = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }

    if (isset($_POST['delete_series'])) {

        $seriesId = $_POST['delete_series'];
        $stmt = $conn->prepare("DELETE FROM Series WHERE SeriesID = ?");
        $stmt->bind_param("i", $seriesId);
        if ($stmt->execute()) {
            $user->redirect("manage_series.php");
        } else {
            echo "Error deleting series: " . $stmt->error;
        }
    }

    if (isset($_POST['series_name'])) {

        $seriesName = $_POST['series_name'];
        if (isset($_POST['edit_series'])) {
            $seriesId = $_POST['edit_series'];

            $image = $_FILES['series_image']['tmp_name'] ?? '';
            $image_name = $_FILES['series_image']['name'] ?? '';
            $target_dir = "images/";
            $target_file = $target_dir . basename($image_name);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (move_uploaded_file($image, $target_file)) {
                $stmt = $conn->prepare("UPDATE Series SET SeriesName = ?, SeriesImage = ? WHERE SeriesID = ?");
                $stmt->bind_param("sii", $seriesName, $target_file, $seriesId);
            } else {
                $stmt = $conn->prepare("UPDATE Series SET SeriesName = ? WHERE SeriesID = ?");
                $stmt->bind_param("si", $seriesName, $seriesId);
            }
        } else {
            $image = $_FILES['series_image']['tmp_name'] ?? '';
            $image_name = $_FILES['series_image']['name'] ?? '';
            $target_dir = "images/";
            $target_file = $target_dir . basename($image_name);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (move_uploaded_file($image, $target_file)) {
                $stmt = $conn->prepare("INSERT INTO Series (SeriesName, Series.Image) VALUES (?, ?)");
                $stmt->bind_param("si", $seriesName, $target_file);
            } else {
                $stmt = $conn->prepare("INSERT INTO Series (SeriesName) VALUES (?)");
                $stmt->bind_param("s", $seriesName);
            }
        }
        if ($stmt->execute()) {
            $user->redirect("manage_series.php");
        } else {
            echo "Error adding/editing series: " . $stmt->error;
        }
    }
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8 space-y-8">
        <h2 class="text-3xl font-bold mb-4">Manage Series</h2>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="" class="space-y-6" enctype="multipart/form-data">
                <?php if ($seriesData): ?>
                    <input type="hidden" name="edit_series" value="<?php echo $seriesData['SeriesID']; ?>">
                    <h3 class="text-xl font-semibold mb-2">Edit Series</h3>

                    <div>
                        <label for="series_name" class="block text-sm font-semibold mb-2">Series Name:</label>
                        <input type="text" id="series_name" name="series_name" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="<?php echo htmlspecialchars($seriesData['SeriesName'], ENT_QUOTES); ?>" required>
                    </div>
                <?php else: ?>
                    <h3 class="text-xl font-semibold mb-2">Add New Series</h3>

                    <div>
                        <label for="series_name" class="block text-sm font-semibold mb-2">Series Name:</label>
                        <input type="text" id="series_name" name="series_name" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter Series Name" required>
                    </div>
                <?php endif; ?>

                <div>
                    <label for="series_image" class="block text-sm font-semibold mb-2">Series Image:</label>
                    <input type="file" id="series_image" name="series_image" accept="image/*" class="border border-gray-300 rounded-md py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="flex items-center justify-end space-x-2">
                    <?php if ($seriesData): ?>
                        <button type="submit" name="delete_series" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">Delete Series</button>
                    <?php endif; ?>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo $seriesData ? 'Save Changes' : 'Add Series'; ?></button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-right text-sm font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($series as $seriesItem): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($seriesItem['SeriesID'], ENT_QUOTES); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($seriesItem['SeriesName'], ENT_QUOTES); ?></td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="">
                                    <input type="hidden" name="edit_series" value="<?php echo $seriesItem['SeriesID']; ?>">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Edit</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="">
                                    <input type="hidden" name="delete_series" value="<?php echo $seriesItem['SeriesID']; ?>">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'Includes/footer.php'; ?>
