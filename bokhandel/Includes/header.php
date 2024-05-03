<?php

if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $_SERVER["REQUEST_URI"])) {
    header("Cache-Control: public, max-age=31536000, immutable");
}

ob_start(); 


include 'class.user.php';
include 'db_connection.php';





?>

<!DOCTYPE html>
<html lang="en">



<head>



<link rel="preload" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" integrity="sha256-bzr+qe38ORs1tU0Ry/5V7ZqWnt31cc/oBq7W7oJw4Wo= sha384-bzr+qe38ORs1tU0Ry/5V7ZqWnt31cc/oBq7W7oJw4Wo= sha512-tq2XQC7duQPnpdenPuR6Z5IE773aRSGjkcutnfUJuTI=" crossorigin="anonymous" as="style" />
<link rel="preload" href="https://cdn.jsdelivr.net/npm/daisyui@1.10.0/dist/full.css" integrity="sha256-tq2XQC7duQPnpdenPuR6Z5IE773aRSGjkcutnfUJuTI= sha384-tq2XQC7duQPnpdenPuR6Z5IE773aRSGjkcutnfUJuTI= sha512-tq2XQC7duQPnpdenPuR6Z5IE773aRSGjkcutnfUJuTI=" crossorigin="anonymous" as="style" />



<meta http-equiv="Cache-Control" content="max-age=3600, must-revalidate">


    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <meta http-equiv="x-preferred-color-scheme" content="dark">
    <meta http-equiv="x-color-scheme" content="dark">
    <meta http-equiv="x-color-scheme-dark" content="dark">

    <meta http-equiv="x-content-type-options" content="nosniff">

    <meta http-equiv="x-xss-protection" content="1; mode=block">
    <meta http-equiv="x-frame-options" content="deny">
    <meta http-equiv="x-permitted-cross-domain-policies" content="none">

    <meta http-equiv="x-referrer-policy" content="strict-origin-when-cross-origin">
    <meta http-equiv="x-strict-transport-security" content="max-age=31536000; includeSubDomains; preload">
    <meta http-equiv="x-content-security-policy" content="default-src 'self'; img-src 'self' data:; font-src 'self' data:; style-src 'self' 'unsafe-inline' data:; script-src 'self' 'unsafe-inline' 'unsafe-eval' data:; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; upgrade-insecure-requests">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">


    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">


    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/daisyui@1.10.0/dist/full.css" as="style">
    <link rel="preload" href="https://cdn.tiny.cloud/1/9gyxjs4n2tcjzi38r7oz33d8yyga54xk0fenkwi8hff4v4br/tinymce/7/tinymce.min.js" as="script">
    <link rel="preconnect" href="https://www.google-analytics.com">
    <link rel="preconnect" href="https://www.googleapis.com">
    <link rel="preconnect" href="https://www.google.com">
 


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="google-site-verification" content="google-site-verification=OeRmKrBSYFfoOcLtFa8JUzeNRagyLFA1Qaxb2FnGxBI">

    <meta property="og:title" content="Bokhandel">
    <meta property="og:description" content="A simple bookstore">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.novatest.ddns.net">
    <meta property="og:image" content="https://www.novatest.ddns.net/images/bokhandel.png">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.10.0/dist/full.css" rel="stylesheet">

    <script src="https://cdn.tiny.cloud/1/9gyxjs4n2tcjzi38r7oz33d8yyga54xk0fenkwi8hff4v4br/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <title>Bokhandel</title>

    
    <meta name="description" content="Bookstore">
    <meta name="keywords" content="bookstore">
    <meta name="robots" content="index,follow">
    <meta name="revisit-after" content="1 days">
    <meta name="expires" content="never">
    <meta name="distribution" content="worldwide">
    <meta name="rating" content="General">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "https://www.novatest.ddns.net",
        "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.novatest.ddns.net/search_results.php?q={search_term_string}",
        "query-input": "required name=search_term_string"
        }
    }
    </script>





    
</head>




<body class="full bg-gray-800 dark:bg-gray-900">
    <div id="testScreen" class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center" style="background-color: rgba(1, 1, 1, 0.8); backdrop-filter: blur(10px); z-index: 9999; color: rgb(255, 255, 255); display: none;">
        <p class="text-6xl font-bold">Yrkesprov: Bokhanddel</p>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {

        var testScreenDisplayed = localStorage.getItem("testScreenDisplayed");
        
        if (!testScreenDisplayed) {

            setTimeout(function() {

                var testScreen = document.getElementById("testScreen");

                testScreen.style.opacity = 1;
                testScreen.style.transition = "opacity 1s ease-in-out";

                setTimeout(function() {

                    testScreen.style.opacity = 0;

                    setTimeout(function() {
                        testScreen.style.display = "none";

                        localStorage.setItem("testScreenDisplayed", "true");
                    }, 1000);
                }, 1000);
            }, 0);
        }
    });
    </script>





    
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
                            <li><a href="my_books.php?uid=<?= $_SESSION["uname"] ?>" class="text-gray-400 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">My books</a></li>
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
            <li><a href="About.php" class="text-white dark:text-gray-200 hover:text-gray-300">About us</a></li>
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


        <?php if(isset($_SESSION['urole']) && $_SESSION['urole'] == "Admin"){ ?>

            <header class="xl:block hidden bg-gray-700 dark:bg-gray-700 py-4 text-sm xl:text-base sticky top-0 z-10">
            

                <nav class="text-sm md:flex justify-center items-center">
                    <ul class="flex justify-center space-x-6 md:space-x-8 lg:space-x-10">
                        <li>
                            <a href="register.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/532379/user-plus.svg" alt="Register" class="w-6 h-6 mr-2 inline-block">
                                Add user
                            </a>
                        </li>
                        <li>
                            <a href="manage_users.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/532376/user-pen.svg" alt="Manage Users" class="w-6 h-6 mr-2 inline-block">
                                Manage users
                            </a>
                        </li>
                        <li>
                            <a href="manage_publishers.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/487780/shipping-box.svg" alt="Manage Publishers" class="w-6 h-6 mr-2 inline-block">
                                Manage publishers
                            </a>
                        </li>
                        <li>
                            <a href="manage_categories.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/339889/categories.svg" alt="Manage Categories" class="w-6 h-6 mr-2 inline-block">
                                Manage categories
                            </a>
                        </li>
                        <li>
                            <a href="manage_age_recommendations.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/521448/age-18.svg" alt="Manage Age Recommendations" class="w-6 h-6 mr-2 inline-block">
                                Manage age recommendations
                            </a>
                        </li>
                        <li>
                            <a href="manage_series.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/343094/episodes.svg" alt="Manage Series" class="w-6 h-6 mr-2 inline-block">
                                Manage series
                            </a>
                        </li>
                        <li>
                            <a href="manage_genre.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/422119/game-gaming-3.svg" alt="Manage Genre" class="w-6 h-6 mr-2 inline-block">
                                Manage genre
                            </a>
                        </li>
                    </ul>
                </nav>
        </header>

                <header class="block xl:hidden bg-gray-700 dark:bg-gray-700 py-4 text-sm xl:text-base sticky top-0 z-10">

                <nav class="text-sm md:flex justify-center items-center">
                <ul class="flex justify-center space-x-4 md:space-x-8 lg:space-x-10">
                        <li>
                            <a href="register.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/532379/user-plus.svg" alt="Register" class="w-6 h-6 mr-2 inline-block fill-white">
                            </a>
                        </li>
                        <li>
                            <a href="manage_users.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/532376/user-pen.svg" alt="Manage Users" class="w-6 h-6 mr-2 inline-block fill-white">
                            </a>
                        </li>
                        <li>
                            <a href="manage_publishers.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/487780/shipping-box.svg" alt="Manage Publishers" class="w-6 h-6 mr-2 inline-block fill-white">
                            </a>
                        </li>
                        <li>
                            <a href="manage_categories.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/339889/categories.svg" alt="Manage Categories" class="w-6 h-6 mr-2 inline-block fill-white">
                            </a>
                        </li>
                        <li>
                            <a href="manage_age_recommendations.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/521448/age-18.svg" alt="Manage Age Recommendations" class="w-6 h-6 mr-2 inline-block fill-white">
                            </a>
                        </li>
                        <li>
                            <a href="manage_series.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/343094/episodes.svg" alt="Manage Series" class="w-6 h-6 mr-2 inline-block fill-white">
                            </a>
                        </li>
                        <li>
                            <a href="manage_genre.php" class="text-white">
                                <img src="https://www.svgrepo.com/show/422119/game-gaming-3.svg" alt="Manage Genre" class="w-6 h-6 mr-2 inline-block fill-white">
                            </a>
                        </li>
                </nav>


            </header>


            <?php } ?>




            <?php if(isset($_SESSION['urole']) && $_SESSION['urole'] == "Regular"){ ?>

<header class="xl:block hidden bg-gray-700 dark:bg-gray-700 py-4 text-sm xl:text-base sticky top-0 z-10">


    <nav class="text-sm md:flex justify-center items-center">
        <ul class="flex justify-center space-x-6 md:space-x-8 lg:space-x-10">
            <li>
                <a href="create_book.php" class="text-white">
                    <img src="https://www.svgrepo.com/show/488479/writing.svg" alt="Register" class="w-6 h-6 mr-2 inline-block">
                    Create a book
                </a>
            </li>
            <li>
                <a href="my_books.php?uid=<?= $_SESSION['uid'] ?>" class="text-white">
                    <img src="https://www.svgrepo.com/show/473036/books.svg" alt="Manage Users" class="w-6 h-6 mr-2 inline-block">
                    View my books
                </a>
            </li>
        </ul>
    </nav>
</header>

    <header class="block xl:hidden bg-gray-700 dark:bg-gray-700 py-4 text-sm xl:text-base sticky top-0 z-10">

    <nav class="text-sm md:flex justify-center items-center">
    <ul class="flex justify-center space-x-4 md:space-x-8 lg:space-x-10">
            <li>
                <a href="create_book.php" class="text-white">
                    <img src="https://www.svgrepo.com/show/488479/writing.svg" alt="Register" class="w-6 h-6 mr-2 inline-block fill-white">
                </a>
            </li>
            <li>
                <a href="my_books.php?uid=<?= $_SESSION['uid'] ?>" class="text-white">
                    <img src="https://www.svgrepo.com/show/473036/books.svg" alt="Manage Users" class="w-6 h-6 mr-2 inline-block fill-white">
                </a>
            </li>
    </nav>


</header>


<?php } ?>



                <div style="width: 1920px;" class="max-w-full mx-auto justify-center overflow-hidden bg-gray-800 dark:bg-gray-900">

