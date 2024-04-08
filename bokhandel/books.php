<?php
include 'db_connection.php';
include 'header.php'; 



$sql = "SELECT * FROM Book";
$result = $conn->query($sql);
?>


    <h1 class="text-3xl font-bold mb-8">All Books</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='bg-white rounded-lg shadow-md p-6'>";
                echo "<h2 class='text-xl font-semibold mb-2'>" . $row["title"] . "</h2>";
                echo "<p class='text-gray-700 mb-4'>Author: " . $row["author"] . "</p>";
                echo "<p class='text-gray-700 mb-4'>Published Year: " . $row["published_year"] . "</p>";
                echo "<p class='text-gray-700 mb-4'>Description: " . $row["description"] . "</p>";
                echo "</div>";
            }
        } else {
            echo "No books found";
        }
        ?>
    </div>

<?php
$conn->close();
?>


<?php
include 'footer.php';
?>
