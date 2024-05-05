<?php
global $pdo;
session_start();
require 'database.php'; // Assurez-vous que cette inclusion est correcte
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Movie details</title>
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

<div class="flex flex-col justify-center h-full my-12">

    <?php
    if (isset($_GET['link'])) {
    $link = urldecode($_GET['link']);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) {
        echo "<p>Failed to retrieve data. Please try again.</p>";
    } else {
    $dom = new DOMDocument();
    @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $xpath = new DOMXPath($dom);

    // Extract the specific data you need, example:
    $titleNode = $xpath->query('//span[contains(@class, "hero__primary-text")]')->item(0);
    $title = $titleNode ? trim($titleNode->textContent) : 'No title found';

    $ratingNode = $xpath->query('//span[contains(@class, "sc-bde20123-1 cMEQkK")]')->item(0);
    $rating = $ratingNode ? trim($ratingNode->textContent) : 'No rating found';

    // New XPath query to extract the poster image src
    $posterImageNode = $xpath->query('//div[contains(@class, "ipc-poster__poster-image")]/img')->item(0);
    $posterImageUrl = $posterImageNode ? $posterImageNode->getAttribute('src') : 'No poster image found';

    $storylineNode = $xpath->query('//span[contains(@class, "sc-7193fc79-0 ftEVcu")]')->item(0);
    $storyline = $storylineNode ? trim($storylineNode->textContent) : 'No description found';

    $releaseYearNodes = $xpath->query('//a[contains(@class, "ipc-link") and not(translate(text(), "0123456789", ""))]');
    $year = "Unknown year";

    // XPath query pour trouver les conteneurs des acteurs
    $actorContainers = $xpath->query('//div[@data-testid="title-cast-item"]'); // Remplacer "cast-list-item" par la classe correcte du conteneur d'acteurs sur IMDb

    echo "<h1 class='font-bold text-2xl'>$title</h1>";
    foreach ($releaseYearNodes as $releaseYearNode) {
        if (preg_match('/^\d+$/', $releaseYearNode->textContent)) {
            $year = $releaseYearNode->textContent;
            break;
        }
    }
    echo "<div class='flex gap-4 place-items-center'>";
    echo "<p class='opacity-80'>$year</p>";
    echo "<p class='flex align-middle gap-1'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='#F9C202' viewBox='0 0 256 256'><path d='M234.29,114.85l-45,38.83L203,211.75a16.4,16.4,0,0,1-24.5,17.82L128,198.49,77.47,229.57A16.4,16.4,0,0,1,53,211.75l13.76-58.07-45-38.83A16.46,16.46,0,0,1,31.08,86l59-4.76,22.76-55.08a16.36,16.36,0,0,1,30.27,0l22.75,55.08,59,4.76a16.46,16.46,0,0,1,9.37,28.86Z'></path></svg> $rating</p>";
    echo "</div>";

    echo "<div class='my-4 flex gap-4'>";
    if ($posterImageUrl !== 'No poster image found') {
        echo "<img src=\"$posterImageUrl\" alt=\"Movie Poster\" class='w-32 h-fit object-cover rounded-lg border border-neutral-800' />";
    } else {
        echo "<p>$posterImageUrl</p>";
    }
    echo "<p class='text-sm md:text-base text-pretty'>$storyline</p>";
    echo "</div>";
    ?>
    <div class="scroll-container mt-8 bg-black p-4 rounded-lg">
        <?php
        echo "<ul class='flex gap-4 -ml-4'>";

        foreach ($actorContainers as $container) {
            // Extrait l'avatar de l'acteur
            $imageNode = $xpath->query('.//img[contains(@class, "ipc-image")]', $container)->item(0);
            $imageUrl = $imageNode ? $imageNode->getAttribute('src') : '../assets/no-actor.png';  // Mettez le chemin correct ici

            // Extrait le nom réel de l'acteur
            $actorLink = $xpath->query('.//a[@data-testid="title-cast-item__actor"]', $container)->item(0);
            $actorName = $actorLink ? trim($actorLink->textContent) : 'Nom inconnu';

            // Extrait le nom du personnage joué
            $characterSpan = $xpath->query('.//span[contains(@class, "sc-bfec09a1-4 kvTUwN")]', $container)->item(0); // Remplacer "character" par la classe correcte de l'élément span
            $characterName = $characterSpan ? trim($characterSpan->textContent) : 'Personnage inconnu';

            // Affiche les informations
            echo "<li class='flex flex-col w-32 md:w-40 h-48 md:h-52 border border-neutral-800 bg-neutral-900 p-2 rounded-xl gap-2 transition ease-in-out duration-200 overflow-hidden'>";
            echo "<img src='$imageUrl' alt='Avatar de $actorName' class='rounded-lg border-neutral-800 border w-full h-fit'> ";
            echo "<div class='flex flex-col gap-1 bg-neutral-900'>";
            echo "<p class='font-bold text-xs md:text-sm bg-neutral-900 text-nowrap'>$actorName</p>";
            echo "<p class='opacity-80 text-xs md:text-sm bg-neutral-900'>$characterName</p>";
            echo "</div>";
            echo "</li>";
        }
        echo "</ul>";
        }

        } else {
            echo "<p>No movie link provided.</p>";
        }
        ?>
    </div>
    <div class="comment-form my-4">
        <h2 class="font-semibold text-xl mb-1">Leave a Comment</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="flex flex-col gap-4">
            <label for="rating" class="sr-only">Your rating</label>
            <input type="number" id="rating" name="rating" min="1" max="10" placeholder="Your rating (1-10)" required
                   class="w-full text-sm text-neutral-900 bg-white dark:bg-neutral-800 focus:ring-0 dark:text-white dark:placeholder-neutral-400
border border-neutral-200 rounded-lg dark:border-neutral-600 px-4 py-2"/>
            <div class="w-full mb-4 border border-neutral-200 rounded-lg bg-neutral-50 dark:bg-neutral-700 dark:border-neutral-600">
                <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-neutral-800">
                    <label for="comment" class="sr-only">Your comment</label>
                    <textarea id="comment" rows="4"
                              class="w-full px-0 text-sm text-neutral-900 bg-white border-0 dark:bg-neutral-800 focus:ring-0 dark:text-white dark:placeholder-neutral-400"
                              placeholder="Write a comment..."
                              name="comment"
                              required></textarea>
                    <!-- Add this input to capture the rating -->
                    <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>"/>
                    <input type="hidden" name="storyline" value="<?php echo htmlspecialchars($storyline); ?>"/>
                    <input type="hidden" name="imdb_rating" value="<?php echo htmlspecialchars($rating); ?>"/>
                    <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>"/>
                    <input type="hidden" name="actors" value="<?php echo htmlspecialchars($actorName); ?>"/>
                    <input type="hidden" name="poster_link" value="<?php echo htmlspecialchars($posterImageUrl); ?>"/>
                    <input type="hidden" name="movie_link" value="<?php echo htmlspecialchars($link); ?>"/>
                </div>
                <div class="flex items-center justify-end px-3 py-2 border-t dark:border-neutral-600">
                    <button type="submit"
                            class="flex justify-center items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800">
                        Post comment
                    </button>
                </div>
            </div>
        </form>
    </div>

    <h2 class="font-semibold text-xl mb-1">Comments</h2>
    <?php
    // Traitement du formulaire de commentaire
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $rating = intval($_POST['rating']);
        $title = $_POST['title'];
        $storyline = $_POST['storyline'];
        $imdb_rating = $_POST['imdb_rating'];
        $year = $_POST['year'];
        $actors = $_POST['actors'];
        $poster_link = $_POST['poster_link'];
        $movie_link = $_POST['movie_link'];

        try {
            // Prepare SQL query with placeholders
            $sql = "INSERT INTO `projectIMDBnoeschmidt`.movies (`title`, `description`, `imdb_rating`, `year`, `actors`, `my_rating`, `my_comment`, `poster_link`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $storyline, $imdb_rating, $year, $actors, $rating, $comment, $poster_link]);

            // Redirect or handle post-submission display
            echo "<meta http-equiv='refresh' content='0'>";
            header('Location: details.php?=' . $movie_link);
            exit;
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    } else {
        die("Required fields are missing.");
    }
    // Déclaration de la variable pour stocker les commentaires
    $comments = [];

    // Pour l'affichage des commentaires
    if (isset($title)) {
        $stmt = $pdo->prepare("SELECT my_comment FROM projectIMDBnoeschmidt.movies WHERE title = ?");
        $stmt->execute([$title]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($comments) {
            foreach ($comments as $comment) {
                echo "<p class='bg-neutral-800 border-2 border-neutral-600 p-4 rounded-lg'>{$comment['my_comment']}</p>";
            }
        } else {
            echo "<p>No comments yet...</p>";
        }
    } else {
        echo "<p>No poster link provided.</p>";
    }
    ?>
</div>

<script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scrollContainer = document.querySelector('.scroll-container');

        // Écouter les événements de la souris et du toucher pour défilement horizontal
        scrollContainer.addEventListener('wheel', function (e) {
            if (e.deltaY > 0) scrollContainer.scrollLeft += 100;
            else scrollContainer.scrollLeft -= 100;
        });
    });
</script>
</body>
</html>
