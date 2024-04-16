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


<div class="bg-white text-black w-dvw min-h-screen flex flex-col justify-center items-center p-8">




<div class="container mx-auto p-8 max-w-6xl">
    <h2 class="text-3xl font-bold mb-4">Manage Users</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <?php
                echo '<a href="account.php?uid=' . $row['UserID'] . '" class="block h-full bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<div class="p-6">';
                echo '<span class="items-end pr-2 text-xs">' . $row['UserID'] . '</span>' . '<span class="text-xl font-semibold text-gray-900 dark:text-white">' . $row['Username'] . '</span>';
                echo '<p class="text-gray-700 dark:text-gray-300">Email: ' . $row['Email'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">Role: ' . $row['Role'] . '</p>';
                echo '</div>';
                echo '</a>';
                ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<div class="mt-4 flex gap-2">
                <a href="register.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Create User</a>
            </div>
</div>



<?php
include 'Includes/footer.php';
?>

