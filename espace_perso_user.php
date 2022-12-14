<?php
require_once("php_assets/connectdb.php");
require("php_assets/verif_session_connect.php");
require("php_assets/fonctions.php");

//Requête pour récupérer les infos de l'utilisateur en connecté
$user_info_req = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ?');

//On l'exécute avec le SESSION['id'] et on passe le tout dans une variable user_data
$user_info_req->execute(array($_SESSION['id']));
$user_data = $user_info_req->fetch(PDO::FETCH_ASSOC);

$user_id = $_SESSION['id'];
$user_login = $user_data['login'];
$user_email = $user_data['email'];
$user_profil_pic = $user_data['profile_picture'];
$user_rank = $user_data['rang'];


//Requête pour recupérer les films loués par l'utilisateur
$rented_movies_req = $conn->prepare('
        SELECT DISTINCT catalogue.id, catalogue.movie_picture, catalogue.release_year, catalogue.title, catalogue.director
            FROM catalogue AS catalogue
            JOIN movies_location AS rented_movies
            ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = ?');
$rented_movies_req->execute(array($_SESSION['id']));
$user_rented_list = $rented_movies_req->fetchAll(PDO::FETCH_ASSOC);

//Requête pour recupérer les films loués par l'utilisateur en ce moment
$rented_movies_req = $conn->prepare('
            SELECT DISTINCT catalogue.id, catalogue.movie_picture, catalogue.release_year, catalogue.title, catalogue.director
                FROM catalogue AS catalogue
                JOIN movies_location AS rented_movies
                ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = ? && rented_movies.is_loc = 1');
$rented_movies_req->execute(array($_SESSION['id']));
$user_rented_list_now = $rented_movies_req->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'php_assets/head.php' ?>

<body>
<?php include 'php_assets/header.php' ?>
<main>
    <section id="user_infos_container" class="container">
        <div class="row mt-5">
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 text-center"></div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 text-light text-center text-sm-center text-md-center text-lg-start text-xl-start">
                <h2>Bienvenue sur votre espace <span class="yellow-txt"><?= $user_login; ?></span></h2>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 text-center">
                <img class="rounded-circle mb-3" src="img/profil_img/<?= $user_profil_pic; ?>" alt="Avatar"
                     style="height:150px">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 text-light text-center text-sm-center text-md-center text-lg-start text-xl-start">
                <h3 class="mt-3 h3">Vos informations</h3>
                <p class="mb-2">Email : <?= $user_email; ?></p>
                <p class="mb-2">Rang : <?= $user_rank; ?></p>
            </div>
        </div>
    </section>
    <section id="current_locations" class="container">
        <div class="row mb-5">
            <h3 class="p-2 rounded yellow-bg">Location en cours</h3>
            <div class="row mt-5">
                <?php
                if (count($user_rented_list_now) > 0) {
                    foreach ($user_rented_list_now as $movie_isloc):

                        // On check la date de location.
                        $locationData = $conn->prepare('SELECT * FROM movies_location WHERE movie_id = ?');
                        $locationData->execute([
                            $movie_isloc['id']
                        ]);
                        $location = $locationData->fetch();

                        $location_date = $location['date_location'];
                        $location_date = date("d/m/Y",strtotime($location_date));
                        ?>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start mb-4">
                            <div class="card movie">
                                <a href="php_assets/add-bookmark.php?movie=<?= $movie_isloc['id'] ?>&page=catalogue"></a>
                                <a href="catalogue.php?movie=<?= $movie_isloc['id'] ?>">
                                    <div class="card-body movie-img"
                                         style="background: url('img/movies_img/<?= $movie_isloc['movie_picture'] ?>')">
                                        <div id="release_year"><h5><?= $movie_isloc['release_year'] ?></h5></div>
                                    </div>
                                </a>
                                <div class="card-footer">
                                    <a href="catalogue.php?movie=<?= $movie_isloc['id'] ?>">
                                        <h3><?= $movie_isloc['title']; ?></h3></a>
                                        <small>De <?= $movie_isloc['director'] ?></small>
                                        <small>Loué le: <?= $location_date?></small>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                } else {
                    echo "<h5 class='no-data'>Aucun film n'est loué actuellement...</h5>";
                }
                ?>

            </div>
        </div>
    </section>
    <section id="past_locations" class="container">
        <h3 class="p-2 rounded yellow-bg">Historique de location</h3>
        <div class="row mt-5">
            <?php
            if (count($user_rented_list) > 0) {
                foreach ($user_rented_list as $movie):

                    // On check la date de location.
                    $locationData = $conn->prepare('SELECT * FROM movies_location WHERE movie_id = ?');
                    $locationData->execute([
                        $movie['id']
                    ]);
                    $location = $locationData->fetch();

                    $location_date = $location['date_location'];
                    $location_date = date("d/m/Y",strtotime($location_date));

                    if($location['date_location_end'] != null){
                        $location_date_end = $location['date_location_end'];
                        $location_date_end = date("d/m/Y",strtotime($location_date_end));
                    }

                    $is_loc = $location['is_loc'];
                    ?>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start mb-4">
                        <div class="card movie">
                            <a href="php_assets/add-bookmark.php?movie=<?= $movie['id'] ?>&page=catalogue"></a>
                            <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                <div class="card-body movie-img"
                                     style="background: url('img/movies_img/<?= $movie['movie_picture'] ?>')">
                                    <div id="release_year"><h5><?= $movie['release_year'] ?></h5></div>
                                </div>
                            </a>
                            <div class="card-footer">
                                <a href="catalogue.php?movie=<?= $movie['id'] ?>"><h3><?= $movie['title']; ?></h3>
                                </a>
                                <small>De <?= $movie['director'] ?></small>
                                <small>Loué le: <?= $location_date ?></small>
                                <small><?php if($is_loc === 1){ echo "Status: En cours"; }else{ echo "Location terminé le: $location_date_end"; } ?></small>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach;
            } else {
                echo "<h5 class='no-data'>Aucun film n'a été loué pour le moment...</h5>";
            }
            ?>
        </div>
    </section>
</main>
<?php include 'php_assets/footer.php' ?>
</body>
</html>