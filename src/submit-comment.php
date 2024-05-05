<?php
session_start();

global $pdo;

require 'details.php';
require 'database.php'; // Assurez-vous d'inclure votre script de connexion à la base de données ici.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_POST['rating'])) {
    // Collect data from POST request
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
        $sql = "INSERT INTO `projectIMDB`.movies (`title`, `description`, `imdb_rating`, `year`, `actors`, `my_rating`, `my_comment`, `poster_link`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $storyline, $imdb_rating, $year, $actors, $rating, $comment, $poster_link]);

        // Redirect or handle post-submission display
        echo "<meta http-equiv='refresh' content='0'>";
        header('Location: details.php?link=' . $movie_link);
        exit;
    } catch (PDOException $e) {
        die("PDO Error: " . $e->getMessage());
    }
} else {
    die("Required fields are missing.");
}
?>