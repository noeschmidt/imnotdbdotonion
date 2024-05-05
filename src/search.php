<?php
global $pdo;
require 'database.php';

$search_term = isset($_POST['search']) ? $_POST['search'] : 'Default Search';  // Récupère la valeur de recherche ou utilise 'Default Search' si rien n'est soumis
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results</title>
    <link href="output.css" rel="stylesheet">
    <link href="input.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="max-w-screen-xl mx-auto p-4">
<nav class="bg-black">
    <div class="flex flex-wrap items-center justify-between mx-auto">
        <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="../assets/logo.png" class="h-8" alt="Flowbite Logo"/>
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">IM(not)DB.onion</span>
        </a>
        <button data-collapse-toggle="navbar-default" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg bg-transparent md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                <li>
                    <a href="/index.php"
                       class="block text-white py-2 px-3 rounded hover:bg-red-700 transition-all ease-in-out duration-200">Home/Search</a>
                </li>
                <li>
                    <a href=""
                       class="block text-white py-2 px-3 rounded hover:bg-red-700 transition-all ease-in-out duration-200">Movies
                        Viewed</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="flex flex-col justify-center h-full">
    <div class="flex flex-col my-12 md:my-24 text-center gap-2">
        <h1 class="text-4xl">Looking for <span class="italic"><?php echo htmlspecialchars($search_term); ?></span>...
        </h1>
        <p class="opacity-80 text-sm">Click on a movie below to get redirected to it.</p>
        <a href="../index.php"
           class="mt-2 mx-auto cursor-pointer bg-red-600 border-2 border-red-700  w-48 h-fit px-4 py-3 font-medium text-lg rounded-xl transition ease-in-out duration-200 hover:bg-red-700 hover:border-red-800">Search
            another</a>
    </div>

    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (isset($_POST['search'])) {
        $search_term = $_POST['search'];
        $search_url = "https://www.imdb.com/search/title/?title=" . urlencode($search_term);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $search_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $html = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        if ($html) {
            $dom = new DOMDocument();
            @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new DOMXPath($dom);

            $titleNodes = $xpath->query('//h3[@class="ipc-title__text"]');
            echo "<div class='flex flex-col gap-8 max-w-2xl mx-auto'>";

            foreach ($titleNodes as $index => $titleNode) {
                $rawTitle = trim($titleNode->textContent);
                $title = preg_replace('/^\d+\.\s*/', '', $rawTitle);

                // Check database for personal rating
                $stmt = $pdo->prepare("SELECT my_rating FROM projectIMDB WHERE title = :title");
                $stmt->execute(['title' => $title]);
                $userRating = $stmt->fetchColumn();

                $ratingText = $userRating ? "Your rating: $userRating" : "Not rated by you";

                // Output each movie in a card
                echo "<div class='movie-card'>$title - $ratingText</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Failed to retrieve data. Please try again.</p>";
        }
    } else {
        echo "<p>No search term provided.</p>";
    }
    ?>
</div>
<script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>
