<?php
global $pdo;
require 'database.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Movies Viewed</title>
    <link href="output.css" rel="stylesheet">
    <link href="input.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="max-w-screen-xl mx-auto p-4">
<nav class="bg-black">
    <div class="flex flex-wrap items-center justify-between mx-auto">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="/assets/logo.png" class="h-8" alt="Flowbite Logo" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">IM(not)DB.onion</span>
        </a>
        <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg bg-transparent md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                <li>
                    <a href="/" class="block text-white py-2 px-3 rounded hover:bg-red-700 transition-all ease-in-out duration-200">Home/Search</a>
                </li>
                <li>
                    <a href="/src/list-movies.php" class="block text-white py-2 px-3 rounded hover:bg-red-700 transition-all ease-in-out duration-200">Movies Viewed</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="flex flex-col justify-center h-full">
    <div class="flex flex-col my-24 text-center">
        <h1 class="text-4xl">You already rated this movies</h1>
    </div>

    <?php
    $sql = "SELECT id, title, description, imdb_rating, year, actors, my_rating, my_comment, poster_link, movie_link FROM projectIMDBnoeschmidt.movies ORDER BY title DESC";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        // Affichage des données de chaque ligne
        echo "<div class='grid grid-cols-1 md:grid-cols-3 justify-center gap-4'>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Utilisation de PDO::FETCH_ASSOC
            echo "<a href='details.php?link=" . $row["movie_link"] . "' class='flex flex-col w-full h-fit border border-neutral-800 bg-neutral-900 p-4 md:p-8 rounded-xl gap-4 transition ease-in-out duration-200 hover:scale-95'>";
            echo "<div class='flex gap-2'>";
            echo "<img src='" . $row["poster_link"] . "' alt='Poster' class='rounded-lg border-neutral-800 border w-24 h-fit''>";
            echo "<div class='flex flex-col'>";
            echo "<h3 class='font-semibold text-xl'>" . $row["title"] . "</h3>";
            echo "<p class='opacity-80'>" . $row["year"] . "</p>";
            echo "<p class='line-clamp-2 md:line-clamp-3'>" . $row["description"] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "<div class='flex flex-col'>";
            echo "My Rating: " . $row["my_rating"];
            echo "<br>";
            echo "<p class='truncate'>My Comment: " . $row["my_comment"] . "</p>";
            echo "</div>";
            echo "</a>";
        }
        echo "</div>";
    } else {
        echo "0 results";
    }
    ?>

</div>
<script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>
