<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IMDBNoé.onion - Home</title>
    <link href="src/output.css" rel="stylesheet">
    <link href="src/input.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="max-w-screen-xl mx-auto p-4">
    <nav class="bg-black">
        <div class="flex flex-wrap items-center justify-between mx-auto">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="assets/logo.png" class="h-8" alt="Flowbite Logo" />
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

    <div class="flex flex-col justify-center items-center h-full">
        <div class="flex flex-col my-24 text-center">
            <h1 class="text-4xl">Search for a movie</h1>
            <p class="opacity-80 text-sm">Enter a movie name below and click "search".</p>
        </div>

        <form method="post" action="src/search.php" class="w-full flex justify-center">
            <div class="relative w-96">
                <label for="search" class="sr-only">Search</label>
                <input type="search" name="search" id="search" class="block w-full p-4 h-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-neutral-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Search" required />
                <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
            </div>
        </form>


    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        // Check if the form has been submitted
        if (isset($_POST['search'])) {
            echo "Search term: " . $_POST['search'];
            $search_term = $_POST['search'];
            $search_url = "https://www.imdb.com/find?q=" . urlencode($search_term);

            // Initialize cURL session
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $search_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $html = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            }
            curl_close($ch);

            if (!$html) {
                echo "<p>Failed to retrieve data. Please try again.</p>";
            } else {
                // Create a DOM parser object
                $dom = new DOMDocument();
                @$dom->loadHTML($html);
                if (!@$dom->loadHTML($html)) {
                    echo "Failed to load HTML.";
                }
                $xpath = new DOMXPath($dom);

                // Search for the titles in the DOM
                $nodes = $xpath->query('//td[@class="result_text"]/a');

                echo "<ul class='search-results-list'>";
                foreach ($nodes as $node) {
                    $title = $node->nodeValue;
                    $link = "https://www.imdb.com" . $node->getAttribute("href");
                    echo "<li><a href='$link'>$title</a></li>";
                }
                echo "</ul>";
            }
        }
        ?>

    </div>
    <script src="node_modules/flowbite/dist/flowbite.min.js"></script>
</body>

</html>