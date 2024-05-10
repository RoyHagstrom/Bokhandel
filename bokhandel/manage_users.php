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

<h2 class="text-3xl font-bold mb-4">Manage Users</h2>

<div class="container mx-auto p-8 max-w-6xl grid grid-cols-2 lg:grid-cols-4 gap-4">
    <?php while ($row = $result->fetch_assoc()): ?>
<a href="account.php?uid=<?php echo $row['UserID']; ?>" class="bg-white border rounded-lg shadow-md p-4 sm:p-6 lg:p-8 w-full md:w-64 flex flex-col justify-between gap-4 mb-8 sm:mb-12 md:mb-16 cursor-pointer">
  <div class="flex flex-col sm:flex-row justify-between items-center">
    <div class="mb-4 sm:mb-0 sm:mr-8">
      <?php if(!empty($row['Image'])): ?>
        <img src="<?php echo $row['Image']; ?>" alt="<?php echo $row['Username']; ?>" class="w-32 h-32 object-cover rounded-full mb-4 sm:mb-0 sm:w-40 sm:h-40 lg:w-48 lg:h-48">
      <?php else: ?>
        <img src="images/default.png" alt="<?php echo $row['Username']; ?>" class="w-32 h-32 object-cover rounded-full mb-4 sm:mb-0 sm:w-40 sm:h-40 lg:w-48 lg:h-48">
      <?php endif; ?>
      <h3 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-gray-900 dark:text-white"><?php echo $row['Username']; ?></h3>
      <p class="text-gray-700 dark:text-gray-300 text-sm"><?php echo $row['Email']; ?></p>
      <p class="text-gray-700 dark:text-gray-300 <?php if($row['Role'] == "Admin"){echo "text-red-600";} ?> text-sm">Role: <?php echo $row['Role']; ?></p>
    </div>
  </div>
</a>


    <?php endwhile; ?>
    
</div>
<div class="mt-4 flex gap-2">
        <a href="register.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Create User</a>
    </div>
</div>

<?php
include 'Includes/footer.php';
?>

