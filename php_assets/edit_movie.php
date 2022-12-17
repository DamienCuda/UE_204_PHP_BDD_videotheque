<?php
require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

// On récupère les donnée du film.
$datasMovie = $conn->prepare("SELECT * FROM catalogue WHERE id=:id");
$datasMovie->bindValue(':id', nettoyage($_POST['id']));
$datasMovie->execute();
$datas = $datasMovie->fetchAll();

if (count($datas) > 0) {

    foreach ($datas as $data) {
        $movie_title_last = $data['title'];
        $movie_genre_last = $data['genre'];
        $movie_actor_last = $data['acteurs'];
        $movie_director_last = $data['director'];
        $annee_sortie_last = $data['release_year'];
        $movie_duration_last = $data['duration'];
        $movie_synopsis_last = $data['synopsis'];
        $price_movie_last = $data['price'];
        $movie_img_last = $data['movie_picture'];
        $movie_id = $data['id'];
    }

    if (
        isset($_POST['movie_title']) && $_POST['movie_title'] != "" &&
        isset($_POST['movie_genre']) && $_POST['movie_genre'] != "" &&
        isset($_POST['movie_actor']) && $_POST['movie_actor'] != "" &&
        isset($_POST['movie_director']) && $_POST['movie_director'] != "" &&
        isset($_POST['annee_sortie']) && $_POST['annee_sortie'] != "" &&
        isset($_POST['movie_duration']) && $_POST['movie_duration'] != "" &&
        isset($_POST['movie_synopsis']) && $_POST['movie_synopsis'] != "" &&
        isset($_POST['price_movie']) && $_POST['price_movie'] != ""
    ) {

        // On définit et protèges nos variables.
        $movie_title = nettoyage($_POST['movie_title']);
        $movie_genre = nettoyage($_POST['movie_genre']);
        $movie_actor = nettoyage($_POST['movie_actor']);
        $movie_director = nettoyage($_POST['movie_director']);
        $annee_sortie = nettoyage($_POST['annee_sortie']);
        $movie_duration = nettoyage($_POST['movie_duration']);
        $movie_synopsis = nettoyage($_POST['movie_synopsis']);
        $price_movie = nettoyage($_POST['price_movie']);

        // On récupères les genres pour explode et recréer un tableau qui aura chaque genre avec la première lettre en majuscule.
        $genres = explode(",", $movie_genre);
        $genres_format = array();

        foreach ($genres as $genre) {
            array_push($genres_format, ucfirst($genre));
        }

        // On passe le tableau en string pour le mettre dans la BDD.
        $genres_format_string = implode(",", $genres_format);

        // On fait pareil pour les acteurs sauf que là on veut une majuscule sur le prénom et le nom.
        $acteurs = explode(",", $movie_actor);
        $acteurs_format = array();

        foreach ($acteurs as $acteur) {
            array_push($acteurs_format, ucwords($acteur));
        }

        // On passe le tableau en string pour le mettre dans la BDD.
        $acteurs_format_string = implode(",", $acteurs_format);

        // On modifie le string de la durée pour le stocker dans la BDD (ex: 01:35 -> 01h35).
        $movie_duration_changed = str_replace(":", "h", $movie_duration);

        if (isset($_FILES['movie_img']['tmp_name']) && $_FILES['movie_img']['tmp_name'] == "") {
            $movie_img = $_FILES['movie_img']['tmp_name'];

            // On détermine nos spécifications autorisé pour l'image.
            $taille_maxi = 2000000;
            $taille = filesize($_FILES['movie_img']['tmp_name']);
            $extensions = array('.png', '.jpg', '.jpeg');
            $extension = strrchr($_FILES['movie_img']['name'], '.');

            if (!in_array($extension, $extensions)) {
                $error = 'Vous devez uploader un fichier de type png, jpg ou jpeg';
            }
            if ($taille > $taille_maxi) {
                $error = 'Le fichier est supérieur à 2 Mo';
            }

            // On détermine l'identifiant du film pour lui passer dans le nom afin d'avoir toujours un nom unique et de ne pas écraser une autres.
            $pdoID = $conn->prepare("SELECT MAX(id) AS max_id FROM catalogue");
            $pdoID->execute();
            $pdoID = $pdoID->fetch();
            $newID = $pdoID['max_id'] + 1;

            $id_user = $_SESSION['id'];
            $id_film = $newID;

            if (!isset($error)) {

                $name = $id_user . '_' . $id_film . '_' . $_FILES['movie_img']['name'] . '';
                $movie_img_name = $name;

                if ($movie_img_name != $movie_img_last) {
                    $tmpName = htmlspecialchars($_FILES['movie_img']['tmp_name']);
                    $path = "../img/movies_img/" . $movie_img_name;
                    $resultat = move_uploaded_file($tmpName, $path);

                    $update = $conn->prepare('UPDATE catalogue SET movie_picture = ? WHERE id = ?');
                    $update->execute(array(
                        $movie_img_name,
                        $movie_id
                    ));
                }
            } else {
                $array = array("status" => "errorIMG");
                echo json_encode($array);
                exit();
            }
        }

        if ($movie_title != $movie_title_last) {
            $update = $conn->prepare('UPDATE catalogue SET title = ? WHERE id = ?');
            $update->execute(array(
                $movie_title,
                $movie_id
            ));
        }

        if ($movie_director != $movie_director_last) {
            $update = $conn->prepare('UPDATE catalogue SET director = ? WHERE id = ?');
            $update->execute(array(
                $movie_director,
                $movie_id
            ));
        }

        if ($acteurs_format_string != $movie_actor_last) {
            $update = $conn->prepare('UPDATE catalogue SET acteurs = ? WHERE id = ?');
            $update->execute(array(
                $acteurs_format_string,
                $movie_id
            ));
        }

        if ($genres_format_string != $movie_genre_last) {
            $update = $conn->prepare('UPDATE catalogue SET genre = ? WHERE id = ?');
            $update->execute(array(
                $genres_format_string,
                $movie_id
            ));
        }

        if ($annee_sortie != $annee_sortie_last) {
            $update = $conn->prepare('UPDATE catalogue SET release_year = ? WHERE id = ?');
            $update->execute(array(
                $annee_sortie,
                $movie_id
            ));
        }

        if ($movie_duration_changed != $movie_duration_last) {
            $update = $conn->prepare('UPDATE catalogue SET duration = ? WHERE id = ?');
            $update->execute(array(
                $movie_duration_changed,
                $movie_id
            ));
        }

        if ($movie_synopsis != $movie_synopsis_last) {
            $update = $conn->prepare('UPDATE catalogue SET synopsis = ? WHERE id = ?');
            $update->execute(array(
                $movie_synopsis,
                $movie_id
            ));
        }

        if ($price_movie != $price_movie_last) {
            $update = $conn->prepare('UPDATE catalogue SET price = ? WHERE id = ?');
            $update->execute(array(
                $price_movie,
                $movie_id
            ));
        }

        $array = array("status" => "success");
        echo json_encode($array);
        exit();

    } else {
        if (!isset($_POST['movie_title']) || $_POST['movie_title'] != "") {
            $array = array("status" => "emptyTitle");
            echo json_encode($array);
            exit();
        }

        if (isset($_POST['movie_genre']) || $_POST['movie_genre'] != "") {
            $array = array("status" => "emptyGenre");
            echo json_encode($array);
            exit();
        }

        if (isset($_POST['movie_actor']) || $_POST['movie_actor'] != "") {
            $array = array("status" => "emptyActor");
            echo json_encode($array);
            exit();
        }

        if (isset($_POST['movie_director']) || $_POST['movie_director'] != "") {
            $array = array("status" => "emptyDirector");
            echo json_encode($array);
            exit();
        }

        if (isset($_POST['annee_sortie']) || $_POST['annee_sortie'] != "") {
            $array = array("status" => "emptyAnnee");
            echo json_encode($array);
            exit();
        }

        if (isset($_POST['movie_duration']) || $_POST['movie_duration'] != "") {
            $array = array("status" => "emptyDuration");
            echo json_encode($array);
            exit();
        }

        if (isset($_POST['movie_synopsis']) || $_POST['movie_synopsis'] != "") {
            $array = array("status" => "emptySynopsis");
            echo json_encode($array);
            exit();
        }

        if (isset($_POST['price_movie']) || $_POST['price_movie'] != "") {
            $array = array("status" => "emptyPrice");
            echo json_encode($array);
            exit();
        }

        if (isset($_FILES['movie_img']['tmp_name']) || $_FILES['movie_img']['tmp_name'] != "") {
            $array = array("status" => "emptyIMG");
            echo json_encode($array);
            exit();
        }
    }
} else {
    $array = array("status" => "errorID");
    echo json_encode($array);
    exit();
}
?>
