<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Smooth transition for countdown elements */
    .countdown-item {
      transition: all 0.5s ease;
    }
  </style>
</head>

<body>
  <div class="bg-gray-900 h-screen flex flex-col items-center justify-center text-white">
    <!-- Logo or Header -->
    <h1 class="text-4xl font-bold mb-6">Coming Soon</h1>

    <!-- Countdown Timer -->
    <div class="flex text-2xl mb-6">
      <div class="mr-2 countdown-item">
        <span id="days" class="font-semibold">00</span> Days
      </div>
      <div class="mr-2 countdown-item">
        <span id="hours" class="font-semibold">00</span> Hours
      </div>
      <div class="mr-2 countdown-item">
        <span id="minutes" class="font-semibold">00</span> Minutes
      </div>
      <div class="countdown-item">
        <span id="seconds" class="font-semibold">00</span> Seconds
      </div>
    </div>

    <!-- Email Subscription Form -->
    <form class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
      <input
        type="email"
        placeholder="Enter your email"
        class="rounded-full px-4 py-2 bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-400"
      />
      <button
        type="submit"
        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-full transition duration-300"
      >
        Subscribe
      </button>
    </form>

    <!-- Social Media Links -->
    <div class="mt-4 space-x-4">
      <a href="#" class="text-gray-400 hover:text-white">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="#" class="text-gray-400 hover:text-white">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="#" class="text-gray-400 hover:text-white">
        <i class="fab fa-instagram"></i>
      </a>
    </div>
  </div>

  <script>
    // Set the date we're counting down to (adjust the date and time)
    const countDownDate = new Date("May 24, 2024 00:00:00").getTime();

    // Update the countdown every 1 second
    const x = setInterval(function () {
      // Get the current date and time
      const now = new Date().getTime();

      // Calculate the time remaining
      const distance = countDownDate - now;

      // Calculate days, hours, minutes, and seconds
      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // Display the countdown in the HTML with leading zeros
      document.getElementById("days").innerHTML = days.toString().padStart(2, '0');
      document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
      document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
      document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');

      // If the countdown is over, display a message
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("countdown").innerHTML = "EXPIRED";
      }
    }, 1000);
  </script>
</body>
</html>
