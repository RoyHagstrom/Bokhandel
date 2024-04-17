<?php

include 'class.user.php';

include 'db_connection.php';





?>
<!DOCTYPE html>
<html lang="en">


<script>
document.addEventListener("DOMContentLoaded", function() {
  var lazyImages = document.querySelectorAll("img.lazy");
  
  var lazyImageObserver = new IntersectionObserver(function(entries, observer) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        var image = entry.target;
        image.src = image.dataset.src;
        image.classList.remove("lazy");
        lazyImageObserver.unobserve(image);
      }
    });
  });

  lazyImages.forEach(function(image) {
    image.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYVy2bCQAAAH/VJWgAA/AAAABQAAAFgAWAAAAAAgAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=="; // a transparent 1x1 png as a placeholder
    lazyImageObserver.observe(image);
  });
});
</script>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.10.0/dist/full.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/9gyxjs4n2tcjzi38r7oz33d8yyga54xk0fenkwi8hff4v4br/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <title>Bokhandel</title>
</head>




<body class="full bg-gray-800 dark:bg-gray-900">
    
    <header class="py-4 bg-gray-800 dark:bg-gray-900">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <a href="index.php" class="text-3xl font-bold text-white">Bokhandel</a> 
            </div>


            <nav class="hidden md:flex items-center justify-between gap-6">
                <ul class="flex space-x-6">
                    <li><a href="index.php" class="text-white dark:text-gray-200 hover:text-gray-300">Home</a></li>
                    <li><a href="books.php" class="text-white dark:text-gray-200 hover:text-gray-300">Books</a></li>
                    <li><a href="contact.php" class="text-white dark:text-gray-200 hover:text-gray-300">Contact</a></li>
                </ul>
                <?php if(isset($_SESSION["uname"])): ?>
                    <div class="dropdown dropdown-hover">
                        <div tabindex="0" role="button" class="btn text-white dark:text-gray-200 hover:text-gray-300">
                            <a href="account.php?uid=<?= $_SESSION["uid"] ?>"><?= $_SESSION["uname"] ?></a>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a href="account.php?uid=<?= $_SESSION["uid"] ?>" class="text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700">Account</a></li>
                            <li><a href="my_books.php" class="text-gray-400 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">My books</a></li>
                        </ul>
                    </div>
                    <a href="logout.php" class="bg-black-900 text-white dark:text-gray-200 hover:bg-black-800 dark:hover:bg-black-400 px-4 py-2 rounded-md">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="bg-black-900 text-white dark:text-gray-200 hover:bg-black-800 dark:hover:bg-black-400 px-4 py-2 rounded-md">Login</a>
                <?php endif; ?>
            </nav>


            <div class="md:hidden" id="mobileMenuButton">
                <button class="text-white dark:text-gray-200 hover:text-gray-300 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <div class="md:hidden bg-gray-800 dark:bg-gray-900 py-2 hidden" id="mobileMenuDrawer">
        <ul class="flex flex-col items-center space-y-4 p-6">
            <li><a href="index.php" class="text-white dark:text-gray-200 hover:text-gray-300">Home</a></li>
            <li><a href="books.php" class="text-white dark:text-gray-200 hover:text-gray-300">Books</a></li>
            <li><a href="contact.php" class="text-white dark:text-gray-200 hover:text-gray-300">Contact</a></li>
            <?php if(isset($_SESSION["uname"])){echo '<li><a href="account.php?uid=' . $_SESSION["uid"] . '" class="text-white dark:text-gray-200 hover:text-gray-300">' . $_SESSION["uname"] . '</a></li>';}?>
                    <?php if(isset($_SESSION["uname"])){echo '<li><a href="logout.php" class="bg-black-900 text-white dark:text-gray-200 hover:bg-black-800 dark:hover:bg-black-400 px-4 py-2 rounded-md">Logout</a></li>';}
                    else
                    { echo ' <li><a href="login.php" class="bg-black-900 text-white dark:text-gray-200 hover:bg-black-800 dark:hover:bg-black-400 px-4 py-2 rounded-md">Login</a></li>';}?>

        </ul>
    </div>

    <script>
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenuDrawer = document.getElementById('mobileMenuDrawer');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenuDrawer.classList.toggle('hidden');
        });

    </script> 
