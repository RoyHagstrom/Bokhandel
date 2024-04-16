<?php
include 'Includes/header.php';


if (!isset($_SESSION["uname"])) {
    $user->redirect("login.php");
}


if ($_SESSION["urole"] != "Admin") {
    $user->redirect("index.php");
}


if (!isset($_GET["publisher_id"]) || empty($_GET["publisher_id"])) {
    $user->redirect("manage_publishers.php");
}

$publisherID = $_GET["publisher_id"];


$stmt = $conn->prepare("SELECT * FROM Publisher WHERE PublisherID = ?");
$stmt->bind_param("i", $publisherID);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows == 0) {
    $user->redirect("manage_publishers.php");
}

$publisherData = $result->fetch_assoc();


$name = $address = $country = $phone = $email = $website = '';
$updateMessage = $errorMessage = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $website = $_POST['website'];


    $stmt = $conn->prepare("UPDATE Publisher SET Name=?, Address=?, Country=?, Phone=?, Email=?, Website=? WHERE PublisherID=?");
    $stmt->bind_param("ssssssi", $name, $address, $country, $phone, $email, $website, $publisherID);

    if ($stmt->execute()) {
        $updateMessage = "Publisher updated successfully.";

        $user->redirect("manage_publishers.php");
    } else {
        $errorMessage = "Error updating publisher: " . $stmt->error;
    }
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Edit Publisher</h2>
        <?php if (!empty($updateMessage)) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded-md" role="alert">
                <?php echo $updateMessage; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded-md" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form class="bg-white shadow-md rounded-lg overflow-hidden" method="POST" action="">
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="name">Name:</label>
                    <input type="text" id="name" name="name" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($publisherData['Name']); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="address">Address:</label>
                    <input type="text" id="address" name="address" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($publisherData['Address']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="country">Country:</label>
                    <input type="text" id="country" name="country" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($publisherData['Country']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($publisherData['Phone']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="email">Email:</label>
                    <input type="email" id="email" name="email" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($publisherData['Email']); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="website">Website:</label>
                    <input type="text" id="website" name="website" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($publisherData['Website']); ?>">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Update Publisher</button>
            </div>
        </form>
    </div>
</div>

<?php include 'Includes/footer.php'; ?>

