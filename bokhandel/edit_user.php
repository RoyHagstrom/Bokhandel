<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}

if(!isset($_GET["userid"])){
    $user->redirect("account.php?userid=" . urlencode($_GET["userid"]));
}

$userid = $_GET["userid"]; 


$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();


if($result->num_rows === 0) {
    echo "User not found";
    exit();
}

$userInfo = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : $userInfo["Username"];
    $email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : $userInfo["Email"];
    $role = isset($_POST["role"]) ? htmlspecialchars($_POST["role"]) : $userInfo["Role"]; 


    $stmt = $conn->prepare("SELECT COUNT(*) FROM User WHERE Username = ? AND UserID = ?");
    $stmt->bind_param("si", $username, $userid); 
    $stmt->execute();
    $result = $stmt->get_result()->fetch_row();

    if ($result[0] > 0) {
        echo "Username already exists";
        exit();
    }


    $stmt = $conn->prepare("UPDATE User SET Username = ?, Email = ?, Role = ? WHERE UserID = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $userid); 
    if ($stmt->execute()) {
        echo "<script>alert('User information updated successfully');</script>";
    } else {
        echo "Error updating user information: " . $stmt->error;
    }
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4 text-center">Edit Profile</h2>
        <form class="bg-white shadow-md rounded-lg p-8 mt-8" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?userid=<?php echo $userid; ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-1" for="username">Username:</label>
                    <input type="text" id="username" name="username" class="border border-gray-400 p-2 w-full rounded-md" value="<?php echo htmlspecialchars($userInfo['Username']); ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1" for="email">Email:</label>
                    <input type="email" id="email" name="email" class="border border-gray-400 p-2 w-full rounded-md" value="<?php echo htmlspecialchars($userInfo['Email']); ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1" for="role">Role:</label>
                    <select id="role" name="role" class="border border-gray-400 p-2 w-full rounded-md">
                        <option value="Admin" <?php echo $userInfo['Role'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="Regular" <?php echo $userInfo['Role'] == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                    </select>
                </div>
            </div>

            <div class="text-center mt-6">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">Update</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'Includes/footer.php';
?>



