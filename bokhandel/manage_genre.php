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



