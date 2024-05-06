<?php
include 'Includes/header.php'; 
?>


<div class="bg-white text-black px-4 py-20 flex flex-col justify-center items-center sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-4 sm:text-3xl">Contact Us</h1>

    <div class="mx-auto w-full max-w-[800px] flex flex-col gap-4 sm:flex-row sm:gap-0 sm:px-8 rounded-lg">
        <div class="w-full flex-grow border-r border-gray-300 p-8 bg-gray-50 sm:w-1/2">
            <h2 class="text-xl font-semibold mb-4">Contact Information</h2>

            <p><strong>Email:</strong> <a href="mailto:roy@gmail.com" class="text-blue-600">roy@gmail.com</a></p>
            <p><strong>Phone:</strong> +358 40324973</p>
            <p><strong>Address:</strong> Pehr Sommars Gata 14, 10600 Ekenäs</p>
            <p class="text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus, ipsum sed blandit efficitur,
                nulla purus auctor turpis, ac ultricies nibh risus eget arcu.</p>

            <h3 class="mt-4 text-xl font-semibold">Customer Service and Business Customers</h3>
            <p>If you have questions, doubts, or thoughts – do not hesitate to contact us by e-mail!</p>
            <p><strong>Customer Service:</strong> kundtjanst@bokhandel.com</p>
            <p><strong>Business Sales:</strong> foretag@bokhandel.com</p>
            <p>If you are not sure to whom you should send your message, please contact us via our website form.</p>

            <h3 class="mt-4 text-xl font-semibold">Other Persons</h3>
            <p><strong>Executive Director:</strong> Marjo Tuomikoski</p>
            <p><strong>Marketing:</strong> markkinointi@bokhandel.com</p>
            <p>For marketing matters, please contact us at the address indicated above.</p>
        </div>
        <div class="w-full flex-grow p-8 bg-gray-50 sm:w-1/2">
            <h2 class="text-xl font-semibold mb-4">Contact Form</h2>
            <p>Contact us if you have questions about our activities. We are happy to help with any questions you may have. Please fill out the contact form and we will get back to you as soon as possible.</p>
            <p>Here you can find answers to the most frequently asked questions from customers or visitors.</p>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-semibold">Name:</label>
                    <input type="text" id="name" name="name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold">Email:</label>
                    <input type="email" id="email" name="email"
                        class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                </div>
                <div>
                    <label for="message" class="block text-sm font-semibold">Message:</label>
                    <textarea id="message" name="message" rows="4"
                        class="w-full border border-gray-300 rounded-md px-3 py-2" required></textarea>
                </div>
                <button type="submit" name="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md">Submit</button>
            </form>
            <?php
            if (isset($_POST['submit'])) {
                $name = htmlspecialchars(trim($_POST['name']));
                $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
                $message = htmlspecialchars(trim($_POST['message']));

                if ($name && $email && $message) {
                    echo "<p class='mt-4'>Thank you for contacting us, {$name}!</p>";
                    echo "<p>We have received your message:</p>";
                    echo "<p><strong>Email:</strong> {$email}</p>";
                    echo "<p><strong>Message:</strong> {$message}</p>";
                } else {
                    echo "<p class='mt-4'>Please fill in all fields.</p>";
                }
            }
            ?>
        </div>
    </div>
</div>

<?php
include 'Includes/footer.php'; 
?>
