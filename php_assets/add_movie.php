<?php
require_once("connectdb.php");
require ("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

$movie_picture = $_POST["movie_picture"];
$movie_title = nettoyage($_POST["movie_title"]);
$movie_director = nettoyage($_POST["movie_director"]);
$movie_actor = nettoyage($_POST["movie_actor"]);
$movie_genre = nettoyage($_POST["movie_genre"]);
$annee_sortie = nettoyage($_POST["annee_sortie"]);
$movie_duration = nettoyage($_POST["movie_duration"]);
$movie_synopsis = nettoyage($_POST["movie_synopsis"]);

$insert = $conn->prepare('INSERT INTO catalogue (movie_picture, title, director, acteurs, genre, release_year, duration, synopsis) VALUES(?,?,?,?,?,?,?,?)');
$insert->execute(array(
    $movie_picture,
    $movie_title,
    $movie_director,
    $movie_actor,
    $movie_genre,
    $annee_sortie,
    $movie_duration,
    $movie_synopsis
    ));
?>