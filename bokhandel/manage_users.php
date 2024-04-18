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
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white rounded-md shadow-md p-4 sm:p-6 lg:p-8 w-full flex flex-col justify-between gap-4">
                <a href="account.php?uid=<?php echo $row['UserID']; ?>" class="flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo $row['Username']; ?></h3>
                        <p class="text-gray-700 dark:text-gray-300">Email: <?php echo $row['Email']; ?></p>
                        <p class="text-gray-700 dark:text-gray-300">Role: <?php echo $row['Role']; ?></p>
                    </div>
                    <span class="text-xs self-end"><?php echo $row['UserID']; ?></span>
                </a>
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

