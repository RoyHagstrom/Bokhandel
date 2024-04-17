<?php include 'Includes/header.php'; ?>

<?php
if (!isset($_SESSION["uname"])) {
    $user->redirect("login.php");
}

if(isset($_SESSION["uid"]) && $_SESSION["urole"] != "Admin"){
    $userID = $_SESSION["uid"];
} elseif($_SESSION["urole"] == "Admin" && isset($_GET["uid"])){
    $userID = $_GET["uid"];
} elseif(isset($_GET["uid"])){
    $userID = $_SESSION["uid"];
} else{
    $userID = $_SESSION["uid"];
}

$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    $user->redirect("index.php");
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center p-8">
    

    
<div class="container mx-auto bg-white shadow-md rounded-lg p-8">
    <h2 class="text-3xl font-bold mb-4">Welcome, <?php echo $userData["Username"]; ?></h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4">
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-lg">User ID:</span>
            <span class="text-lg"><?php echo $userData['UserID']; ?></span>
        </div>
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-lg">Username:</span>
            <span class="text-lg"><?php echo $userData['Username']; ?></span>
        </div>
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-lg">Email:</span>
            <span class="text-lg"><?php echo $userData['Email']; ?></span>
        </div>
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-lg">Role:</span>

            
            <?php if ($_SESSION['urole'] == "Admin") { 
                echo '<div class="relative">';
                echo '<select class="w-full px-4 py-2 border border-gray-300 rounded-md" id="user-role-select" onchange="updateUserRole()">';
                echo '<option value="Regular" ' . ($userData['Role'] == 'Regular' ? 'selected' : '') . '>Regular</option>';
                echo '<option value="Admin" ' . ($userData['Role'] == 'Admin' ? 'selected' : '') . '>Admin</option>';
                echo '</select>';
                echo '</div>';
                
                ?>
    <script>
        function updateUserRole() {
            const userId = <?= $userData['UserID'] ?>;
            const selectElement = document.getElementById('user-role-select');
            const selectedRole = selectElement.value;

            fetch('updateUserRole.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ userId, selectedRole })
            })
            .then(response => {
                if (response.ok) {
                    alert('Role updated');
                    selectElement.classList.add('hidden');
                    const roleElement = document.getElementById('role');
                    roleElement.innerText = selectedRole;
                } else {
                    throw new Error('Error updating role');
                }
            })
            .catch(error => {
                alert(error.message);
            });
        }
    </script>
<?php } else { 
                echo "<span class=\"text-lg\">" . $userData['Role'] . "</span>";
            } ?>



        </div>
    </div>
    <h3 class="text-2xl font-bold mb-2 mt-8">Actions:</h3>
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
        <a href="create_book.php" class="btn bg-blue-500 text-white w-full sm:w-auto">Create a book</a>
        <a href="my_books.php?uid=<?= $userData['Username'] ?>" class="btn bg-blue-700 text-white w-full sm:w-auto">View my books</a>

        <?php if($userData['Role'] == "Admin"){ ?>
            <a href="register.php" class="btn bg-green-500 text-white w-full sm:w-auto">Add a user</a>
            <a href="manage_users.php" class="btn bg-green-500 text-white w-full sm:w-auto">Manage users</a>
            <a href="create_publisher.php" class="btn bg-yellow-500 text-black w-full sm:w-auto">Add a publisher</a>
            <a href="manage_publishers.php" class="btn bg-yellow-500 text-black w-full sm:w-auto">Manage publishers</a>
            <a href="manage_categories.php" class="btn bg-white text-gray-900 w-full sm:w-auto">Manage categories</a>
            <a href="manage_age_recommendations.php" class="btn bg-white text-gray-900 w-full sm:w-auto">Manage age recommendations</a>
            <a href="manage_series.php" class="btn bg-white text-gray-900 w-full sm:w-auto">Manage series</a>
            <a href="manage_genre.php" class="btn bg-white text-gray-900 w-full sm:w-auto">Manage genre</a>
        <?php } ?>
        
        <?php if($_SESSION['urole'] == "Admin"){ ?>
            <a href="delete_user.php?userid=<?php echo $userData['UserID']; ?>" class="btn bg-red-500 text-white w-full sm:w-auto" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">Delete user</a>
        <?php } ?>

    </div>



<?php
$stmt = $conn->query("SELECT BookID, Title, Price, C.Name AS Category FROM Book B JOIN categories C ON B.Category = C.ID WHERE Author = '".$userData["Username"]."'");
if($stmt && $stmt->num_rows > 0){
    echo '<h3 class="text-2xl font-bold my-4">Author\'s Books:</h3>
    <table class="w-full text-left border-collapse mt-2">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-100">Book ID</th>
                <th class="py-2 px-4 bg-gray-100">Title</th>
                <th class="py-2 px-4 bg-gray-100">Category</th>
                <th class="py-2 px-4 bg-gray-100">Price</th>
            </tr>
        </thead>
        <tbody>';
    while($book = $stmt->fetch_assoc()){
        echo '<tr>
                <td class="py-2 px-4 border-b">'.$book['BookID'].'</td>
                <td class="py-2 px-4 border-b"><a href="singlebook.php?id='.$book['BookID'].'" class="text-blue-500">'.$book['Title'].'</a></td>
                <td class="py-2 px-4 border-b">'.$book['Category'].'</td>
                <td class="py-2 px-4 border-b">'.$book['Price'].'€</td>
            </tr>';
    }
    echo '</tbody></table>';
}else{
    echo '<p class="text-lg my-4 italic">No books found for this author.</p>';
}
?>

</div>
</div>
<?php include 'Includes/footer.php'; ?>

