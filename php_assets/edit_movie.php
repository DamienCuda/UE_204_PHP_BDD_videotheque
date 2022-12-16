<?php
require_once("connectdb.php");
require ("verif_session_connect.php");
require ("fonctions.php");
require ("permission.php");

$editMovie = $conn->prepare('UPDATE catalogue set movie_picture = ?, SET movie_title = ?, SET movie_director = ?, SET movie_actor = ?, SET movie_genre = ?, SET annee_sortie = ?, SET movie_duration = ?, SET movie_synopsis = ? WHERE id= ?');
$editMovie->execute(array(
    $movie_picture,
    $movie_title,
    $movie_director,
    $movie_actor,
    $movie_genre,
    $annee_sortie,
    $movie_duration,
    $movie_synopsis,
    nettoyage($_GET['movie'])
]);