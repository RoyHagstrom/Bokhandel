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
?>

<div class="dark min-h-screen bg-white text-gray-900 flex flex-col justify-center items-center">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold mb-4">Manage Publishers</h2>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                $result = $conn->query("SELECT * FROM Publisher");
                $publishers = $result->fetch_all(MYSQLI_ASSOC);
                foreach ($publishers as $publisher) :
                ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $publisher['PublisherID']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $publisher['Name']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $publisher['Address']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $publisher['Country']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $publisher['Phone']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $publisher['Email']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $publisher['Website']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="edit_publisher.php?publisher_id=<?php echo $publisher['PublisherID']; ?>"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <a href="create_publisher.php" class="m-4 bg-blue-500 text-white rounded px-4 py-2">Add Publisher</a>

</div>
<?php
include 'Includes/footer.php';
?>
