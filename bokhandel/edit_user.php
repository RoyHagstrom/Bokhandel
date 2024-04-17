<?php

include 'Includes/header.php';

$userid = $_GET["userid"];
$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    $user->redirect("index.php");
}
?>
<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">

<div class="mt-4 flex gap-2">
    <form action="edit_user_db.php?userid=<?php echo $userData['UserID']; ?>" method="POST" class="flex flex-col gap-4">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $userData['Username']; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $userData['Email']; ?>" required>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="Customer" <?php echo $userData['Role'] == "Customer" ? "selected" : ""; ?>>Customer</option>
            <option value="Admin" <?php echo $userData['Role'] == "Admin" ? "selected" : ""; ?>>Admin</option>
        </select>

        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">Submit</button>
    </form>
</div>

</div>


<?php include 'Includes/footer.php'; ?>

