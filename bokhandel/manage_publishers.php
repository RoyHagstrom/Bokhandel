<?php
include 'Includes/header.php';

if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}
elseif($_SESSION["urole"] != "Admin"){
    $user->redirect("login.php");

}

$result = $conn->query("SELECT * FROM Publisher");
$publishers = $result->fetch_all(MYSQLI_ASSOC);




$name = $address = $country = $phone = $email = $website = '';
$successMessage = $errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == $_SERVER["PHP_SELF"]) {
    if (isset($_POST['name'], $_POST['address'], $_POST['country'], $_POST['phone'], $_POST['email'], $_POST['website'])) {
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
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_delete'])) {
    $publisherId = $_POST['delete_publisher'];

    $stmt = $conn->prepare("DELETE FROM Publisher WHERE PublisherID = ?");
    $stmt->bind_param("i", $publisherId);
    if ($stmt->execute()) {
        $successMessage = "Publisher deleted successfully.";
    } else {
        $errorMessage = "Error deleting publisher: " . $stmt->error;
    }
}


?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">


<div class="flex">
    <div class="w-1/2 mr-4">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-3xl font-medium text-center text-gray-900 mb-4">Add New Publisher</h2>
            <?php if ($successMessage) : ?><div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded-md" role="alert"><?php echo $successMessage; ?></div><?php endif; ?>
            <?php if ($errorMessage) : ?><div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded-md" role="alert"><?php echo $errorMessage; ?></div><?php endif; ?>

            <form method="POST" action="" class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700" for="name">Name:</label>
                    <input type="text" id="name" name="name" class="border border-gray-300 px-4 py-2 rounded-md w-full" required>

                    <label class="block text-sm font-semibold text-gray-700" for="address">Address:</label>
                    <input type="text" id="address" name="address" class="border border-gray-300 px-4 py-2 rounded-md w-full">

                    <label class="block text-sm font-semibold text-gray-700" for="country">Country:</label>
                    <input type="text" id="country" name="country" class="border border-gray-300 px-4 py-2 rounded-md w-full">

                    <label class="block text-sm font-semibold text-gray-700" for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" class="border border-gray-300 px-4 py-2 rounded-md w-full">

                    <label class="block text-sm font-semibold text-gray-700" for="email">Email:</label>
                    <input type="email" id="email" name="email" class="border border-gray-300 px-4 py-2 rounded-md w-full">

                    <label class="block text-sm font-semibold text-gray-700" for="website">Website:</label>
                    <input type="url" id="website" name="website" class="border border-gray-300 px-4 py-2 rounded-md w-full">
                </div>

                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded-md w-full">Add Publisher</button>
            </form>
        </div>
    </div>
    <div class="w-1/2">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-3xl font-medium text-center text-gray-900 mb-4">Manage Publishers</h2>
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($publishers as $publisher) : ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $publisher['PublisherID']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $publisher['Name']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $publisher['Address']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $publisher['Country']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $publisher['Phone']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $publisher['Email']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $publisher['Website']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="edit_publisher.php?publisher_id=<?php echo $publisher['PublisherID']; ?>"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                        <input type="hidden" name="delete_publisher" value="<?php echo $publisher['PublisherID']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900" name="submit_delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($publishers)) : ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">No publishers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>



<?php
include 'Includes/footer.php';
?>
