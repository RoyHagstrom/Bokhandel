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

<div class="mt-4 flex gap-2">
    <a href="edit_user.php?userid=<?php echo $userData['UserID']; ?>" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">Edit user</a>
</div>

<?php include 'Includes/footer.php'; ?>

