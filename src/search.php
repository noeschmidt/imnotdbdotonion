<?php
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

        if (!$html) {
            echo "<p>Failed to retrieve data. Please try again.</p>";
        } else {
            $dom = new DOMDocument();
            @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new DOMXPath($dom);

            // Query for movie titles
            $titleNodes = $xpath->query('//h3[@class="ipc-title__text"]');

            // Query for IMDb ratings
            $ratingNodes = $xpath->query('//span[contains(@class, "ipc-rating-star--imdb")]');

            // Query for IMDB images
            $imageNodes = $xpath->query('//img[contains(@class,"ipc-image")]');

            $descriptionNodes = $xpath->query('//div[@class="ipc-html-content-inner-div"]');

            $linkNodes = $xpath->query('//a[@class="ipc-lockup-overlay ipc-focusable"]/@href');

            echo "<div class='flex flex-col gap-8 max-w-2xl mx-auto'>";
            foreach ($titleNodes as $index => $titleNode) {
                $rawTitle = trim($titleNode->textContent);
                // Removing numbering from the title using regex
                $title = preg_replace('/^\d+\.\s*/', '', $rawTitle);
                $rating = $ratingNodes->item($index) ? trim($ratingNodes->item($index)->textContent) : 'No rating found';
                $imageSrc = $imageNodes->item($index) ? $imageNodes->item($index)->getAttribute('src') : '../assets/no-poster.png'; // Assurez-vous d'avoir une image par défaut
                $description = $descriptionNodes->item($index) ? trim($descriptionNodes->item($index)->textContent) : 'No description found';
                $hrefValue = $linkNodes->item($index) ? $linkNodes->item($index)->nodeValue : '';
                $fullUrl = "https://www.imdb.com" . $hrefValue;

                // Output each movie in a card
                echo "<a href='details.php?link=" . urlencode($fullUrl) . "' class='flex w-full h-fit border border-neutral-800 bg-neutral-900 p-4 md:p-8 rounded-xl gap-4 transition ease-in-out duration-200 hover:scale-110'>
            <img src='$imageSrc' alt='Poster' class='rounded-lg border-neutral-800 border w-24 h-fit'> 
              <div class='bg-neutral-900'>
                  <h5 class='font-semibold text-xl line-clamp-1 md:line-clamp-2'>$title</h5>
                  <p class='flex align-middle gap-1'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='#F9C202' viewBox='0 0 256 256'><path d='M234.29,114.85l-45,38.83L203,211.75a16.4,16.4,0,0,1-24.5,17.82L128,198.49,77.47,229.57A16.4,16.4,0,0,1,53,211.75l13.76-58.07-45-38.83A16.46,16.46,0,0,1,31.08,86l59-4.76,22.76-55.08a16.36,16.36,0,0,1,30.27,0l22.75,55.08,59,4.76a16.46,16.46,0,0,1,9.37,28.86Z'></path></svg> $rating</p>
                  <p class='mt-2 opacity-80 line-clamp-2 md:line-clamp-3'>$description</p>
              </div>
          </a>";
            }
            echo "</div>";
        }
    } else {
        echo "<p>No search term provided.</p>";
    }
    ?>

</div>
<script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>
