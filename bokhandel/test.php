<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <header class="bg-white">
        <div class="container mx-auto p-6">
            <nav class="flex items-center justify-between">
                <div class="flex items-center flex-shrink-0 text-black mr-6">
                    <span class="font-semibold text-xl tracking-tight">Bookstore</span>
                </div>
                <div class="block lg:hidden">
                    <button id="nav-toggle"
                        class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-gray-700 hover:border-teal-500 appearance-none focus:outline-none">
                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path
                                d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
                    </button>
                </div>
                <div class="hidden lg:block">
                    <ul class="flex items-center">
                        <li><a class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                                href="#">Home</a></li>
                        <li><a class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                                href="#">Books</a></li>
                        <li><a class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                                href="#">About</a></li>
                        <li><a class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                                href="#">Contact</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="hero bg-gray-100 py-16">
        <div class="container mx-auto text-center">
            <h1 class="text-5xl font-bold text-gray-800 leading-tight">Discover Your Next Favorite Book</h1>
            <p class="text-xl text-gray-700 mt-4">Browse our vast collection of books</p>
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-8 rounded-full focus:outline-none focus:shadow-outline">
                Explore Books
            </button>
        </div>
    </div>

    <div class="featured-books py-16">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold text-gray-800 text-center mb-8">Featured Books</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="max-w-sm rounded overflow-hidden shadow-lg">
                    <img class="w-full" src="https://via.placeholder.com/350x500" alt="Book Cover">
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2">Book Title</div>
                        <p class="text-gray-700 text-base">
                            Author Name
                        </p>
                    </div>
                    <div class="px-6 pt-4 pb-2">
                        <span
                            class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#fiction</span>
                        <span
                            class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">#fantasy</span>
                    </div>
                </div>
                <div class="max-w-sm rounded overflow-hidden shadow-lg">
                    <img class="w-full" src="https://via.placeholder.com/350x500" alt="Book Cover">
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2">Book Title</div>
                        <p class="text-gray-700 text-base">Author Name</p>
                    </div>
                    <div class="px-6 pt-4 pb-2">
                        <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">#genre</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Bookstore. All rights reserved.</p>
        </div>
    </footer>

    <script>
    </script>
</body>

</html>