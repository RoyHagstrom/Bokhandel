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
                    <span class="font-semibold text-xl tracking-tight">Tailwind CSS</span>
                </div>
                <div class="block lg:hidden">
                    <button id="nav-toggle"
                        class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-gray-700 hover:border-teal-500 appearance-none focus:outline-none">
                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path
                                d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                    </button>
                </div>
                <div class="hidden lg:block">
                    <ul class="flex items-center">
                        <li><a class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                                href="#">Home</a></li>
                        <li><a class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                                href="#">About</a></li>
                        <li><a class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                                href="#">Services</a></li>
                        <li><a class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4"
                                href="#">Contact</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="hero bg-gray-100 py-16">
        <div class="container mx-auto text-center">
            <h1 class="text-5xl font-bold text-gray-800 leading-tight">Welcome to Your Awesome Website</h1>
            <p class="text-xl text-gray-700 mt-4">Crafted with Tailwind CSS</p>
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-8 rounded-full focus:outline-none focus:shadow-outline">
                Get Started
            </button>
        </div>
    </div>

    <div class="features py-16">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold text-gray-800 text-center">Features</h2>
            <div class="flex flex-wrap mt-12">
                <div class="w-full md:w-1/3 px-4 mb-8">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Responsive Design</h3>
                        <p class="text-gray-700">Looks great on any device.</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4 mb-8">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Easy to Customize</h3>
                        <p class="text-gray-700">Customize it to fit your needs.</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4 mb-8">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Fast Loading</h3>
                        <p class="text-gray-700">Optimized for speed.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Tailwind CSS Landing Page. All rights reserved.</p>
        </div>
    </footer>

    <script>
    </script>
</body>

</html>