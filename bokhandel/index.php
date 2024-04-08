<?php
include 'db_connection.php';
include 'header.php';

$sql = "SELECT text FROM messages";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $test = $row['text'];
}

$featured_category = "";
$featured_category_sql = "SELECT name, image FROM categories WHERE featured = 1";
$featured_category_result = $conn->query($featured_category_sql);

if ($featured_category_result->num_rows > 0) {
    $row = $featured_category_result->fetch_assoc();
    $featured_category = $row['name'];
    $featured_category_image = $row['image'];
}

$new_books_sql = "SELECT * FROM Book ORDER BY PublicationYear DESC LIMIT 6";
$new_books_result = $conn->query($new_books_sql);
?>

<?php echo $test; ?>

<div class="light-mode bg-white text-gray-900 min-h-screen flex flex-col justify-center items-center">

        <div class="container p-8 rounded-lg shadow-md w-full sm:w-135 mt-8">
            <h1 class="text-2xl font-semibold mb-6">Book Search</h1>
            <div class="relative mb-4">
                <input type="text" id="searchInput" class="appearance-none border border-gray-300 rounded-md py-2 px-4 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-blue-500 w-full" placeholder="Search for books">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div id="bookInfo" class="mt-4"></div>
        </div>


    <script>
        const searchInput = document.getElementById('searchInput');
        const bookInfo = document.getElementById('bookInfo');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();
            searchBooks(searchTerm);
        });

        function searchBooks(searchTerm) {
            fetch(`search.php?term=${searchTerm}`)
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
                bookInfo.innerHTML = 'No books found';
                return;
            }
            bookInfo.innerHTML = books.map(book => `
                <div class="book-info mb-4">
                    <h2 class="text-xl mb-2"><a href="singlebook.php?id=${book.BookID}">${book.Title}</a></h2>
                    <p>Author: ${book.Author}</p>
                    <p>Description: ${book.Description}</p>
                </div>
            `).join('');
        }
    </script>

    <div class="container p-8 rounded-lg shadow-md w-full sm:w-130 mt-8">
        <h1 class="text-2xl font-semibold mb-6">Featured Categories:</h1>

        <div class="flex flex-wrap justify-center items-center">
            <?php
            $featured_categories_sql = "SELECT name, image FROM categories WHERE featured = 1";
            $featured_categories_result = $conn->query($featured_categories_sql);

            if ($featured_categories_result->num_rows > 0) {
                while ($row = $featured_categories_result->fetch_assoc()) {
                    $featured_category_name = $row['name'];
                    $featured_category_image = $row['image'];

                    echo '<div class="m-4 relative rounded-lg">';
                    echo '<a href="books.php?id=' . $row['name'] . '">';
                    echo '<img src="' . $featured_category_image . '" alt="' . $featured_category_name . '" class="w-64 h-64 object-cover rounded-lg opacity-70">';
                    echo '<div class="absolute inset-0 flex items-center justify-center text-white text-3xl font-semibold bg-black bg-opacity-50 rounded-lg">' . $featured_category_name . '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-lg text-gray-500">No featured categories available</p>';
            }
            ?>
        </div>
    </div>

    <div class="container p-8 rounded-lg shadow-md w-full sm:w-116 mt-8">
        <h1 class="text-2xl font-semibold mb-6">New Books</h1>

        <div style="display: flex; flex-wrap: wrap;">
            <?php
            if ($new_books_result->num_rows > 0) {
                while ($row = $new_books_result->fetch_assoc()) {
                    echo '<div class="book-info mb-4 mr-4" style="flex: 0 0 calc(33.33% - 20px);">';
                    echo '<h2 class="text-xl mb-2"><a href="singlebook.php?id=' . $row['BookID'] . '">' . $row['Title'] . '</a></h2>';
                    echo '<p style="margin-right: 10px;">Author: ' . $row['Author'] . '</p>';
                    echo '<p>Description: ' . $row['Description'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo 'No new books available';
            }
            ?>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
