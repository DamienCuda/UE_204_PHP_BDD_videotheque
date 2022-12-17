<?php
require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

if (isset($_GET['movie']) && $_GET['movie'] != "") {

    $movie_id = nettoyage($_GET['movie']);

    // On récupère les infos du film.
    $movieData = $conn->prepare('SELECT * FROM catalogue WHERE id = ?');
    $movieData->execute([
        $movie_id
    ]);
    $movies = $movieData->fetchAll();

    if (count($movies) > 0) {

        // On récupère les infos de l'utilisateur.
        $userData = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ?');
        $userData->execute([
            $_SESSION['id']
        ]);
        $users = $userData->fetchAll();

        if (count($users) > 0) {

            foreach ($movies as $movie) {
                // On récupère le prix du film.
                $movie_price = $movie['price'];
            }

            foreach ($users as $user) {
                // On récupère le solde de l'utilisateur.
                $user_solde = $user['solde'];
            }

            // Si le solde est supérieur au prix du film on insère les infos dans la table des locations.
            if ($user_solde > $movie_price) {

                $date = date("Y-m-d H:i:s");
                $date_end = date('Y-m-d H:i:s', strtotime($date. ' + 1 days'));

                // On check si le film n'est pas déjà dans la table des location
                $locationData = $conn->prepare('SELECT * FROM movies_location WHERE movie_id = ? AND user_id = ?');
                $locationData->execute([
                    $movie_id,
                    $_SESSION['id']
                ]);
                $locations = $locationData->fetchAll();

                if (count($locations) > 0) {

                    // Si il existe déjà on update la ligne de la location.
                    $editMovie = $conn->prepare('UPDATE movies_location set date_location = ?, date_location_end = ?, is_loc = ? WHERE movie_id= ? AND user_id = ?');
                    $editMovie->execute([
                        $date,
                        $date_end,
                        1,
                        $movie_id,
                        $_SESSION['id']
                    ]);

                } else {

                    // Sinon on insère la ligne de la location.
                    $insert = $conn->prepare('INSERT INTO movies_location (movie_id, user_id, date_location, date_location_end) VALUES(?,?,?,?)');
                    $insert->execute(array(
                        $movie_id,
                        $_SESSION['id'],
                        $date,
                        $date_end
                    ));
                }

                // On calcule le nouveau solde de l'utilsateur.
                $newsolde = $user_solde - $movie_price;

                // On déduit l'argent à l'utilisateur.
                $editUser = $conn->prepare('UPDATE utilisateurs set solde = ? WHERE id= ?');
                $editUser->execute([
                    $newsolde,
                    $_SESSION['id']
                ]);

                // On enregistre la transaction
                $insert = $conn->prepare('INSERT INTO transactions (movie_id, user_id, date, amount) VALUES(?,?,?,?)');
                $insert->execute(array(
                    $movie_id,
                    $_SESSION['id'],
                    $date,
                    $movie_price
                ));
            }

        }
    }
    header("location: ../espace_perso_user.php");
} else {
    header("location: ../catalogue.php");
}


?>