<?php
    require_once("connectdb.php");
    require ("verif_session_connect.php");
    require ("fonctions.php");

    if(isset($_GET['movie']) && $_GET['movie'] != ""){

        // On initialise et nettoie les variables.
        $id_movie = nettoyage($_GET['movie']);
        $id_user = $_SESSION['id'];
        $date = date("Y-m-d H:i:s");

        // On récupère le film avec l'ID en paramètre dans l'url.
        $moviesReq = $conn->prepare('SELECT * FROM catalogue WHERE id = ? LIMIT 1');
        $moviesReq->execute([
            $id_movie
        ]);
        $movie = $moviesReq->fetch();

        // On verifie qu'il n'existe pas déjà dans la table bookmark
        $moviesReq = $conn->prepare('SELECT * FROM movies_bookmark WHERE movie_id = ? AND user_id = ? LIMIT 1');
        $moviesReq->execute([
            $id_movie,
            $id_user
        ]);
        $bookmark = $moviesReq->fetchAll();

        // Si il existe pas on l'ajoute
        if(count($bookmark) === 0){
            $insertBookmark = $conn->prepare('INSERT INTO movies_bookmark (movie_id, user_id, date) VALUES(?,?,?)');
            $insertBookmark->execute(array(
                $id_movie,
                $id_user,
                $date
            ));
        }else{
            // Sinon on le supprime
            $pdoDel = $conn->prepare('DELETE FROM movies_bookmark WHERE movie_id = ? AND user_id = ?');
            $pdoDel->execute([
                $id_movie,
                $id_user
            ]);
        }

        if(isset($_GET['page']) && $_GET['page'] == "film_details"){
            header("location: ../catalogue.php?movie=$id_movie");
        }else{
            header("location: ../catalogue.php");
        }
    }else{
        header("location: ../catalogue.php");
    }
 ?>