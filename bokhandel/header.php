<?php
include 'class.user.php';

include 'db_connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.10.0/dist/full.css" rel="stylesheet">
    <title>Bokhandel</title>
</head>

<body class="full">
    
    <header class="py-4 bg-gray-800 dark:bg-gray-900">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <a href="index.php" class="text-3xl font-bold">Bokhandel</a>
            </div>


            

            
            <nav class="hidden md:block">
                <ul class="flex space-x-6">
                    <li><a href="index.php" class="text-white dark:text-gray-200 hover:text-gray-300">Home</a></li>
                    <li><a href="books.php" class="text-white dark:text-gray-200 hover:text-gray-300">Books</a></li>
                    <li><a href="#" class="text-white dark:text-gray-200 hover:text-gray-300">Services</a></li>
                    <li><a href="contact.php" class="text-white dark:text-gray-200 hover:text-gray-300">Contact</a></li>
                    <li><a href="login.php" class="bg-blue-900 text-white dark:text-gray-200 hover:bg-blue-800 dark:hover:bg-blue-400 px-4 py-2 rounded-md">Login</a></li>

                </ul>
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
            <li><a href="#" class="text-white dark:text-gray-200 hover:text-gray-300">Services</a></li>
            <li><a href="contact.php" class="text-white dark:text-gray-200 hover:text-gray-300">Contact</a></li>
            <li><a href="login.php" class="bg-blue-900 text-white dark:text-gray-200 hover:bg-blue-800 dark:hover:bg-blue-400 px-4 py-2 rounded-md">Login</a></li>

        </ul>
    </div>

    <script>
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenuDrawer = document.getElementById('mobileMenuDrawer');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenuDrawer.classList.toggle('hidden');
        });
    </script>
