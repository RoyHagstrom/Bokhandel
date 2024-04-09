<?php
include 'header.php'; 
?>

<div class="bg-white text-black min-h-screen flex flex-col justify-center items-center">
    <h1 class="text-2xl font-bold mb-4">Contact Us</h1>

    <div class="container mx-auto p-8 rounded-lg shadow-md flex">
        <div class="w-1/2 pr-4">
            <h2 class="text-xl font-semibold mb-4">Contact Information</h2>
            <p><strong>Email:</strong> roy@gmail.com</p>
            <p><strong>Phone:</strong> +358 40324973</p>
            <p><strong>Address:</strong> Pehr Sommars Gata 14, 10600 Ekenäs</p>
        </div>
        <div class="w-1/2 pl-4">
            <h2 class="text-xl font-semibold mb-4">Contact Form</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold">Name:</label>
                    <input type="text" id="name" name="name" class="w-full border rounded-md px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold">Email:</label>
                    <input type="email" id="email" name="email" class="w-full border rounded-md px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-sm font-semibold">Message:</label>
                    <textarea id="message" name="message" rows="4" class="w-full border rounded-md px-3 py-2" required></textarea>
                </div>
                <button type="submit" name="submit" class="bg-gray-900 hover:bg-gray-900 text-white font-semibold px-4 py-2 rounded-md">Submit</button>
            </form>
            <?php
            if (isset($_POST['submit'])) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $message = $_POST['message'];

                echo "<p class='mt-4'>Thank you for contacting us, $name!</p>";
                echo "<p>We have received your message:</p>";
                echo "<p><strong>Email:</strong> $email</p>";
                echo "<p><strong>Message:</strong> $message</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php
include 'footer.php'; 
?>
