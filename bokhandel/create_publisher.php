<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}
elseif($_SESSION["urole"] != "Admin"){
    $user->redirect("login.php");

}

$name = $address = $country = $phone = $email = $website = '';
$successMessage = $errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $website = $_POST['website'];


    $stmt = $conn->prepare("INSERT INTO Publisher (Name, Address, Country, Phone, Email, Website) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $address, $country, $phone, $email, $website);

    if ($stmt->execute()) {
        $successMessage = "Publisher added successfully.";

        $name = $address = $country = $phone = $email = $website = '';
    } else {
        $errorMessage = "Error adding publisher: " . $stmt->error;
    }
}
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Add New Publisher</h2>
        <?php if (!empty($successMessage)) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded-md" role="alert">
                <?php echo $successMessage; ?>
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
                    <input type="text" id="name" name="name" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="address">Address:</label>
                    <input type="text" id="address" name="address" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($address); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="country">Country:</label>
                    <input type="text" id="country" name="country" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($country); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($phone); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="email">Email:</label>
                    <input type="email" id="email" name="email" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2" for="website">Website:</label>
                    <input type="url" id="website" name="website" class="appearance-none border rounded-md py-2 px-4 w-full" value="<?php echo htmlspecialchars($website); ?>">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Add Publisher</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'Includes/footer.php';
?>
