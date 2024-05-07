<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}
elseif($_SESSION["urole"] != "Admin"){
    $user->redirect("login.php");

}


$sql = "SELECT * FROM User";

$result = $conn->query($sql);

?>

<div class="bg-white text-black w-dvw min-h-screen flex flex-col justify-center items-center lg:p-8">

<div class="container flex mx-auto p-8 max-w-6xl">
    <h2 class="text-3xl font-bold mb-4">Manage Users</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div href="account.php?uid=<?php echo $row['UserID']; ?>" class="bg-white border rounded-lg shadow-md p-4 sm:p-6 lg:p-8 w-full flex flex-col justify-between gap-4 mb-8 sm:mb-12 md:mb-16 cursor-pointer">

            <a href="account.php?uid=<?php echo $row['UserID']; ?>" class="flex flex-col sm:flex-row justify-between items-center h-full sm:h-auto">
                <div class="mb-4 sm:mb-0 sm:mr-8">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo $row['Username']; ?></h3>
                    <p class="text-gray-700 dark:text-gray-300">Email: <?php echo $row['Email']; ?></p>
                    <p class="text-gray-700 dark:text-gray-300">Role: <?php echo $row['Role']; ?></p>
                </div>
                <span class="self-end"><?php echo $row['UserID']; ?></span>
            </a>
            
            <?php
            $stmt = $conn->prepare("SELECT BookID, Title, Price, C.Name AS Category FROM Book B JOIN categories C ON B.Category = C.ID WHERE Author = ?");
            $stmt->bind_param("s", $row['Username']);
            $stmt->execute();
            $books = $stmt->get_result();
            if ($stmt->error) {
                echo "<p>Error: " . $stmt->error . "</p>";
            } else {
                if ($books->num_rows > 0) {
                    echo '<h3 class="text-2xl font-bold mt-4 sm:mt-8 md:mt-12 text-center">' . $row['Username'] . '\'s Books:</h3>
                    <div class="overflow-x-auto md:overflow-x-visible">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Book ID</th>
                                <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">';
                    while ($book = $books->fetch_assoc()) {
                        echo '<tr>
                                <td class="py-4 px-4 whitespace-nowrap"><a href="singlebook.php?id=' . $book['BookID'] . '" class="text-blue-500">' . $book['BookID'] . '</a></td>
                                <td class="py-4 px-4 whitespace-nowrap"><a href="singlebook.php?id=' . $book['BookID'] . '" class="text-blue-500">' . $book['Title'] . '</a></td>
                                <td class="py-4 px-4 whitespace-nowrap">' . $book['Category'] . '</td>
                                <td class="py-4 px-4 whitespace-nowrap">' . $book['Price'] . '€</td>
                            </tr>';
                    }
                    echo '</tbody></table></div>';
                } else {
                    echo '<p class="text-lg my-4 sm:my-8 md:my-12 italic">No books found for this user.</p>';
                }
            }
            ?>
        </div>

    <?php endwhile; ?>
    <div class="mt-4 flex gap-2">
        <a href="register.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Create User</a>
    </div>
</div>
</div>

<?php
include 'Includes/footer.php';
?>

