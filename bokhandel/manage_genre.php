<?php
include 'Includes/header.php';

if (!isset($_SESSION["uname"])) {
    $user->redirect("login.php");
}

if ($_SESSION["urole"] != "Admin") {
    $user->redirect("index.php");
}

$stmt = $conn->prepare("SELECT * FROM Genres");
$stmt->execute();
$genres = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$genreData = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['edit_genre'])) {
        $genreId = $_POST['edit_genre'];

        $stmt = $conn->prepare("SELECT * FROM Genres WHERE GenreID = ?");
        $stmt->bind_param("i", $genreId);
        $stmt->execute();
        $genreData = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }

    if (isset($_POST['delete_genre'])) {
        $genreId = $_POST['delete_genre'];

        $stmt = $conn->prepare("DELETE FROM Genres WHERE GenreID = ?");
        $stmt->bind_param("i", $genreId);
        if ($stmt->execute()) {
            $user->redirect("manage_genre.php");
        } else {
            echo "Error deleting genre: " . $stmt->error;
        }
    }

    if (isset($_POST['genre_name'])) {
        $genreName = $_POST['genre_name'];
        if (isset($_POST['edit_genre'])) {
            $genreId = $_POST['edit_genre'];
            $stmt = $conn->prepare("UPDATE Genres SET GenreName = ? WHERE GenreID = ?");
            $stmt->bind_param("si", $genreName, $genreId);
        } else {
            $stmt = $conn->prepare("INSERT INTO Genres (GenreName) VALUES (?)");
            $stmt->bind_param("s", $genreName);
        }
        if ($stmt->execute()) {
            $user->redirect("manage_genre.php");
        } else {
            echo "Error adding/editing genre: " . $stmt->error;
        }
    }
}
?>

<div class="bg-white dark:bg-gray-800 flex flex-col justify-center items-center text-black">
    <div class="container mx-auto my-10">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">
            <h2 class="text-2xl font-semibold mb-4 text-center">Manage Genres</h2>
            <p class="leading-7 mb-4">Add, edit or delete genres for books.</p>

            <form action="" method="post" class="mb-4">
                <div class="mt-4">
                    <label for="genre_name" class="block font-medium leading-6">Genre Name:</label>
                    <input type="text" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-600" id="genre_name" name="genre_name" value="<?= isset($genreData) ? $genreData['GenreName'] : '' ?>" placeholder="Enter genre name" required autofocus>
                </div>

                <?php if (isset($genreData)): ?>
                    <input type="hidden" name="edit_genre" value="<?= $genreData['GenreID'] ?>">
                    <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Genre</button>
                <?php else: ?>
                    <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add Genre</button>
                <?php endif; ?>
            </form>

            <table class="w-full mt-6 table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">Genre ID</th>
                        <th class="px-4 py-2">Genre Name</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($genres as $genre): ?>
                        <tr>
                            <td class="px-4 py-2"><?= $genre['GenreID'] ?></td>
                            <td class="px-4 py-2"><?= $genre['GenreName'] ?></td>
                            <td class="px-4 py-2 flex justify-center">
                                <form action="" method="post" class="mr-2">
                                    <input type="hidden" name="edit_genre" value="<?= $genre['GenreID'] ?>">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit</button>
                                </form>
                                <form action="" method="post" class="mr-2" onsubmit="return confirm('Are you sure you want to delete this genre?')">
                                    <input type="hidden" name="delete_genre" value="<?= $genre['GenreID'] ?>">
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

<?php
include 'Includes/footer.php';
?>
