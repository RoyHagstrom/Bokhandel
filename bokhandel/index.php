<?php
include 'Includes/header.php';

$featured_category = "";
$featured_category_sql = "SELECT name, image FROM categories WHERE featured = 1";
$featured_category_result = $conn->query($featured_category_sql);

if ($featured_category_result->num_rows > 0) {
    $row = $featured_category_result->fetch_assoc();
    $featured_category = $row['name'];
    $featured_category_image = $row['image'];
}

$new_books_sql = "SELECT * FROM Book ORDER BY BookID DESC LIMIT 10";
$new_books_result = $conn->query($new_books_sql);

$featured_books_sql = "SELECT * FROM Book WHERE Featured = 1 ORDER BY RAND() LIMIT 10";
$featured_books_result = $conn->query($featured_books_sql);


$highest_rated_sql = "SELECT * FROM Book ORDER BY Rating DESC LIMIT 10";
$highest_rated_result = $conn->query($highest_rated_sql);
?>


<div class="dark bg-white text-gray-900 min-h-screen flex flex-col justify-center items-center">





<div class="bg-gray-800 p-2 md:p-8 w-full sm:w-130">
    <h1 class="text-center text-2xl font-semibold mb-6 text-white">Featured Categories:</h1>

    <div class="flex flex-wrap justify-center items-center">
        <?php
        $featured_categories_sql = "SELECT id, name, image FROM categories WHERE featured = 1";
        $featured_categories_result = $conn->query($featured_categories_sql);

        if ($featured_categories_result->num_rows > 0) {
            while ($row = $featured_categories_result->fetch_assoc()) {
                $featured_category_id = $row['id']; 
                $featured_category_name = $row['name'];
                $featured_category_image = $row['image'];

                echo '<div class="m-2 md:m-4 relative rounded-lg">';
                echo '<a href="books.php?id=' . $featured_category_id . '">'; 
                echo '<img src="' . $featured_category_image . '" alt="' . $featured_category_name . '" class="w-28 h-28 sm:w-40 sm:h-40 lg:w-64 lg:h-64 object-cover rounded-lg opacity-70">';
                echo '<div class="absolute inset-0 flex items-center justify-center text-white text-sm lg:text-3xl font-semibold bg-black bg-opacity-50 rounded-lg">' . $featured_category_name . '</div>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-lg text-gray-500">No featured categories available</p>';
        }
        ?>
    </div>
</div>



        <div class="container bg-neutral-500 p-8 w-full sm:w-135 mt-8">
    <h1 class="text-black text-2xl font-semibold mb-6">Book Search</h1>
    <div class="relative mb-4">
        <input type="text" id="searchInput" class="appearance-none border border-gray-300 rounded-md py-2 px-4 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-blue-500 w-full" placeholder="Search for books">
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>
    <div id="bookInfo" class="shadow-md rounded-lg w-full mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo min($new_books_result->num_rows, 3); ?> xl:grid-cols-<?php echo min($new_books_result->num_rows, 4); ?> gap-2" style="z-index: 1;"></div>
</div>


<script>
    const searchInput = document.getElementById('searchInput');
    const bookInfo = document.getElementById('bookInfo');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim().toLowerCase();
        searchBooks(searchTerm);
    });

    function searchBooks(searchTerm) {
        fetch(`Includes/search.php?term=${searchTerm}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('');
                }
                return response.json();
            })
            .then(data => {
                displayBooks(data);
            })
            .catch(error => {
                bookInfo.innerHTML = '';
            });
    }

    function displayBooks(books) {
        if (books.length === 0) {
            bookInfo.innerHTML = '';
            return;
        }

        const html = books.map(book => {
            const description = book.Description
                ?.toString()
                ?.trim()
                ?.replace(/<[^>]*>|[\p{Z}\p{C}]+/gu, ' ')
                ?.slice(0, 50)
                ?.replace(/\s+/gu, ' ');
            const title = book.Title
                ?.toString()
                ?.trim()
                ?.replace(/<[^>]*>|[\p{Z}\p{C}]+/gu, ' ')
                ?.replace(/ +/g, ' '); 
            const author = book.Author
                ?.toString()
                ?.trim()
                ?.replace(/<[^>]*>|[\p{Z}\p{C}]+/gu, ' ')
                ?.replace(/ +/g, ' ');

            return `
                <div class="book-info mb-4 flex text-container bg-white p-6 rounded-lg cursor-pointer" onclick="window.location='singlebook.php?id=${encodeURIComponent(book.BookID)}';">
                <img src="${(book.Image)}" alt="${encodeURI(title)}" class="h-32 object-cover mr-4 rounded-sm shadow-md">
                    <div style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;" class="flex-grow text-xs">
                        <h2 style="overflow: hidden" class="text-lg mb-1 leading-5"><a href="singlebook.php?id=${encodeURIComponent(book.BookID)}">${title}</a></h2>
                        <p class="text-xs">Author: ${author}</p>
                        <p class="line-clamp-2">${description + '...'}</p>
                    </div>
                </div>
            `;
        }).join('');

        bookInfo.innerHTML = html;
    }
</script>


<h1 class="text-2xl font-semibold mb-6 text-center text-black">Book Series</h1>
<div class="container mt-8 px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4 md:gap-8 place-content-center justify-center items-center self-center">
    <?php
    $stmt = $conn->query("SELECT * FROM Series WHERE Featured = 1 ORDER BY SeriesID DESC LIMIT 4");
    while ($series = $stmt->fetch_assoc()) { ?>
    <a href="books.php?search=<?= $series['SeriesName'] ?>" class="group w-full h-full">
        <div class="relative overflow-hidden rounded-lg shadow-md">
            <img src="<?= $series['Image'] ?>" alt="<?= $series['SeriesName'] ?>" class="w-full h-48 sm:h-64 lg:h-80 object-cover group-hover:opacity-75 transition-opacity duration-200 ease-in-out" />
            <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-70 group-hover:bg-opacity-75 transition-opacity duration-200 ease-in-out">
                <h2 class="text-2xl font-semibold text-white mb-2 group-hover:opacity-100 transition-opacity duration-200 ease-in-out underline decoration-black"><?= $series['SeriesName'] ?></h2>
            </div>
        </div>
    </a>
    <?php } if (!$stmt->num_rows) { ?>
        <div class="text-center">
            <p class="text-gray-700 dark:text-gray-300">No book series available</p>
        </div>
    <?php } ?>
</div>


<div class="container bg-white p-8 rounded-lg shadow-md w-full sm:w-130 mt-8">
    <h1 class="text-2xl font-semibold mb-6">Featured Books</h1>
    <div class="text-gray-700 dark:text-gray-300 mb-8">
        <p class="leading-relaxed text-sm sm:text-base lg:text-lg mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac velit at purus aliquet bibendum eu sed sem. Nulla facilisi. Nullam auctor, nulla et condimentum posuere,
            sem nisi tristique velit, ut eleifend urna leo et velit. Sed nec velit eget metus dictum vehicula. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
        </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-8">
        <?php
        if ($featured_books_result->num_rows > 0) {
            while ($row = $featured_books_result->fetch_assoc()) {
                echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-30 md:w-full h-30 md:h-80 object-cover">';
                echo '<div class="p-3 md:p-6 text-sm md:text-md">';
                echo '<h2 class="md:text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">' . htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 100)) . '...</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo 'No new books available';
        }
        ?>
    </div>
</div>


<div class="bg-white container p-8 rounded-lg shadow-md w-full sm:w-130 mt-8">

<div class="flex items-center mb-6 gap-2">

    <h1 class="text-2xl font-semibold">Highest Rated Books</h1>



    <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
        <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
    </svg>
    <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
        <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
    </svg>
    <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
        <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
    </svg>
    <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
        <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
    </svg>
    <svg class="w-4 h-4 ms-1 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
        <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
    </svg>
</div>
<p class="mt-2 text-sm text-gray-700 dark:text-gray-300 lg:text-base">
        Our highest rated books are selected based on their reviews from our users. Here are some of our top picks.
    </p>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-8">
        <?php

        if ($highest_rated_result->num_rows > 0) {
            while ($row = $highest_rated_result->fetch_assoc()) {
                echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-30 md:w-full h-30 md:h-80 object-cover">';
                echo '<div class="p-3 md:p-6 text-sm md:text-md">';
                echo '<h2 class="md:text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">' . htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 100)) . '...</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo 'No highest rated books available';
        }
        ?>
    </div>
</div>




<div class="bg-white container p-8 rounded-lg shadow-md w-full sm:w-130 mt-8">
    <h1 class="text-2xl font-semibold mb-6">New Books</h1>
    <div class="text-gray-700 dark:text-gray-300 mb-8">
        <p class="leading-relaxed text-sm sm:text-base lg:text-lg mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac velit at purus aliquet bibendum eu sed sem. Nulla facilisi. Nullam auctor, nulla et condimentum posuere,
            sem nisi tristique velit, ut eleifend urna leo et velit. Sed nec velit eget metus dictum vehicula. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
        </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-8">
        <?php
        if ($new_books_result->num_rows > 0) {
            while ($row = $new_books_result->fetch_assoc()) {
                echo '<a href="singlebook.php?id=' . $row['BookID'] . '" class="block bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:bg-gray-200 dark:hover:bg-gray-800 transition duration-300 relative">';
                echo '<span class="absolute top-2 right-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-lg">' . $row['Price'] . '€</span>';
                echo '<img src="' . $row['Image'] . '" alt="' . $row['Title'] . '" class="w-30 md:w-full h-30 md:h-80 object-cover">';
                echo '<div class="p-3 md:p-6 text-sm md:text-md">';
                echo '<h2 class="md:text-xl font-semibold text-gray-900 dark:text-white">' . $row['Title'] . '</h2>';
                echo '<p class="text-gray-700 dark:text-gray-300">Author: ' . $row['Author'] . '</p>';
                echo '<p class="text-gray-700 dark:text-gray-300">' . htmlspecialchars(substr(strip_tags(html_entity_decode($row["Description"])), 0, 50)) . '...</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo 'No new books available';
        }
        ?>
    </div>
</div>



<div class="container bg-white p-8 rounded-lg shadow-md w-full sm:w-116 mt-8">
    <div class="flex flex-col md:flex-row">
        <img src="images/img-02.jpg" alt="Image" class="w-full md:w-1/2 h-auto md:h-80 object-cover rounded-lg mb-6 md:mb-0">
        <div class="md:w-1/2 md:pl-6">
            <h2 class="text-2xl font-semibold mb-4">Info</h2>
            <p class="text-gray-700 dark:text-gray-300">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam semper felis et metus maximus auctor. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum vehicula nisi et lacus scelerisque vestibulum. Donec fermentum sapien et turpis pulvinar, id efficitur sapien aliquam. Cras id enim bibendum, vestibulum erat in, tincidunt nisi. Proin id metus ac lorem efficitur venenatis. Nulla rutrum massa nec volutpat laoreet. Duis ac ex vitae ipsum ultricies convallis. Suspendisse potenti.</p>
        </div>
    </div>
</div>


<div class="container mx-auto py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="text-container bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Bokhandel</h2>
                <address class="mt-2">
                    Pehr Sommars Gata 14,<br> 10600 Ekenäs
                </address>
            </div>
            <div class="map-container rounded-lg overflow-hidden shadow-md">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1995.98448781003!2d23.45551817001953!3d59.982167233711465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x468d0781c4df266b%3A0xd7806c0586ff53f9!2sGigantti!5e0!3m2!1sen!2sfi!4v1712662419833!5m2!1sen!2sfi" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0" class="w-full h-full"></iframe> 
           </div>
        </div>
    </div>



</div>

<?php
include 'Includes/footer.php';
?>
