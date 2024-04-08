<?php
include 'db_connection.php';
include 'header.php';

$sql = "SELECT text FROM messages";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $test = $row['text'];
}

// Fetch featured category
$featured_category_sql = "SELECT * FROM categories WHERE featured = 1";
$featured_category_result = $conn->query($featured_category_sql);

$featured_category = "";
if ($featured_category_result->num_rows > 0) {
    $featured_category_row = $featured_category_result->fetch_assoc();
    $featured_category = $featured_category_row['name'];
}

// Fetch new books
$new_books_sql = "SELECT * FROM Book ORDER BY PublicationYear DESC LIMIT 3";
$new_books_result = $conn->query($new_books_sql);

$conn->close();
?>

<?php echo $test; ?>

<div class="dark-mode bg-gray-900 text-white min-h-screen flex flex-col justify-center items-center">

    <div class="container p-8 rounded-lg shadow-md w-full sm:w-96">
        <h1 class="text-2xl font-semibold mb-6">Book Search</h1>
        <div class="mb-4">
            <input type="text" id="searchInput" class="mt-1 block w-full rounded-md bg-gray-700 border-none text-white px-4 py-2" placeholder="Enter book title">
        </div>
        <div id="bookInfo" class="mt-4"></div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const bookInfo = document.getElementById('bookInfo');

        searchInput.addEventListener('input', function () {
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

    <div class="container p-8 rounded-lg shadow-md w-full sm:w-96 mt-8">
        <h1 class="text-2xl font-semibold mb-6">Featured Category: <?php echo $featured_category; ?></h1>
    </div>

    <div class="container p-8 rounded-lg shadow-md w-full sm:w-116 mt-8">
        <h1 class="text-2xl font-semibold mb-6">New Books</h1>

        <div style="display: flex; flex-wrap: nowrap;">
            <?php
            if ($new_books_result->num_rows > 0) {
                while ($row = $new_books_result->fetch_assoc()) {
                    echo '<div class="book-info mb-4" style="margin-right: 20px;">';
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
