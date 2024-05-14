<?php
include 'Includes/header.php';

if ($_SESSION["urole"] != "Admin"){
if (isset($_SESSION["uid"])) {
    $user->redirect("account.php");
}
}


if (isset($_POST['register-submit'])) {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

if (empty($role)) {
    $role = "User";
}
else{
        $role = $_POST['role']; 
}
        $registerReturn = $user->register($username, $email, $password, $role);

    } else {
        echo "All fields are required";
    }
}


?>

<section class="bg-gray-50 dark:bg-gray-900 py-4">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:min-h-screen lg:py-0">
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            Bokhandel    
        </a>
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Register an account
                </h1>
                <form class="space-y-4 md:space-y-6" method="POST">
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" minlength="4" maxlength="24" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Username" required="">
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Email" required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" minlength="6" maxlength="24" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                    </div>
                    <div>
                        <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                        <input type="password" name="PasswordConfirm" id="confirm-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                    </div>
                <?php if(isset($_SESSION["urole"]) && $_SESSION["urole"] == "Admin"): ?>
                    <div>
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                            <option value="">Select Role</option>
                            <option value="Regular">Regular</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                <?php endif; ?>


                    <input type="checkbox" id="privacy-policy" name="privacy-policy" required>
                    <label for="privacy-policy" class="text-sm text-gray-700 dark:text-white">By registering, you confirm that you have read the Privacy Policy</label>
                    <p class="bg-gray-950 text-black"><?php if(isset($registerReturn)){echo $registerReturn;} ?></p>
                    <button type="submit" name="register-submit" class="w-full text-white bg-black hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Register User</button>
                </form>

                <button onclick="showPrivacyPolicy()" class="bg-gray-100 hover:bg-grey-300 text-black text-base font-semibold py-1 px-2 rounded">
  Show Privacy Policy
</button>

<div id="privacyPolicy" style="display: none;">
  <small class="text-gray-400 text-xs leading-3">By registering an account with Bokhandel, you expressly consent to the collection, processing, and storage of your personal information in accordance with applicable data protection laws and regulations. This includes but is not limited to the General Data Protection Regulation (GDPR) in the European Union and the California Consumer Privacy Act (CCPA) in the United States. We are committed to complying with these legal requirements and ensuring the protection of your privacy rights.

  You acknowledge that the information provided during registration, such as your name, email address, and contact details, is necessary for the performance of our contractual obligations and the provision of services. We may use this information for purposes such as user authentication, communication regarding account-related matters, customization of content, and improvement of our services.

  Furthermore, we will not disclose your personal information to third parties except as required by law or with your explicit consent. We may share your information with trusted service providers who assist us in operating our platform, processing transactions, or providing other support services, provided that they adhere to strict confidentiality and security measures.

  By registering an account, you affirm that you have read, understood, and accepted the terms of our Privacy Policy, which governs the collection, processing, and storage of your personal information. If you have any questions or concerns about our data practices or your privacy rights, please contact us using the information provided in the "Contact Us" section below. Your continued use of our platform constitutes ongoing consent to the handling of your personal information in accordance with this Privacy Policy and applicable laws.
  </small>
</div>

<script>
  function showPrivacyPolicy() {
    document.getElementById('privacyPolicy').style.display = 'block';
  }
</script>



            </div>
        </div>
    </div>
</section>

<?php
include 'Includes/footer.php';
?>


