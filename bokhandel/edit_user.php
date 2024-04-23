<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}

if(!isset($_GET["userid"])){
    echo "<script>alert('Please provide userid');</script>";
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
    $bio = isset($_POST["bio"]) ? htmlspecialchars($_POST["bio"]) : $userInfo["Bio"]; 
    $image = isset($_POST["image"]) ? $_FILES["image"] : $userInfo["Image"]; 
    

    $stmt = $conn->prepare("SELECT COUNT(*) FROM User WHERE Username = ? AND UserID = ?");
    $stmt->bind_param("si", $username, $userid); 
    $stmt->execute();
    $result = $stmt->get_result()->fetch_row();

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["image"]["tmp_name"];
        $name = basename($_FILES["image"]["name"]);
        $imgExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $newName = uniqid() . ".$imgExt";
        $target = "images/$newName";
        if (!move_uploaded_file($tmp_name, $target)) {
            echo "Error uploading image";
        } else {
            $image = $newName;
            $imageDir = "images/$newName";
        }
    } else {
        $imageDir = $userInfo["Image"];
    }
    $stmt = $conn->prepare("UPDATE User SET Username = ?, Email = ?, Role = ?, Bio = ?, Image = ? WHERE UserID = ?");
    $stmt->bind_param("ssssss", $username, $email, $role, $bio, $imageDir, $userid);
    if (!$stmt->execute()) {
        throw new mysqli_sql_exception("Error updating user information: " . $stmt->error);
    }


}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">

    <div class="container mx-auto p-8">
        <h2 class="text-center text-3xl font-bold mb-4">Edit Profile</h2>
        <form class="bg-white shadow-md rounded-lg p-8 mt-8" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?userid=<?php echo $userid; ?>" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block mb-2 text-sm font-semibold" for="username">Username:</label>
                    <input type="text" id="username" name="username" class="border border-gray-400 p-2 w-full rounded-md" value="<?php echo htmlspecialchars($userInfo['Username']); ?>" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold" for="email">Email:</label>
                    <input type="email" id="email" name="email" class="border border-gray-400 p-2 w-full rounded-md" value="<?php echo htmlspecialchars($userInfo['Email']); ?>" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold" for="role">Role:</label>
                    <select id="role" name="role" class="border border-gray-400 p-2 w-full rounded-md">
                        <option value="Admin" <?php echo $userInfo['Role'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="Regular" <?php echo $userInfo['Role'] == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold" for="bio">Bio:</label>
                    <textarea id="bio" name="bio" class="border border-gray-400 p-2 w-full rounded-md" rows="5" maxlength="200"><?php echo $userInfo['Bio']; ?></textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold" for="image">Profile Image:</label>
                    <input type="file" id="image" name="image" class="border border-gray-400 p-2 w-full rounded-md">
                    <?php 
                    if (!empty($userInfo['Image'])): 
                        echo '<img src="Images/users/' . $userInfo['Image'] . '" alt="Profile Image" class="mt-4 max-h-64 mx-auto rounded-md">';
                    endif; 
                    ?>
                </div>
            </div>

            <div class="text-center mt-8">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md shadow-md">Update</button>
            </div>
        </form>
    </div>

</div>

<?php
include 'Includes/footer.php';
?>

