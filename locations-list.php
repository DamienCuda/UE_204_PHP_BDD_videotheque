<?php
require_once("php_assets/connectdb.php");
require("php_assets/verif_session_connect.php");
require("php_assets/fonctions.php");

if (isset($_GET['id']) && $_GET['id'] != "") {

//Requête pour récupérer les infos de l'utilisateur en connecté
    $user_info_req = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ?');
    $user_info_req->execute(array($_GET['id']));
    $user_datas = $user_info_req->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <?php include 'php_assets/head.php' ?>

    <body>
    <?php
    include 'php_assets/header.php' ?>

    <main>
        <?php
        if (count($user_datas) != 0) {

            $user_id = $_GET['id'];

            //Requête pour recupérer les films loués par l'utilisateur en ce moment
            $rented_movies_req = $conn->prepare('
                SELECT DISTINCT catalogue.id, catalogue.movie_picture, catalogue.release_year, catalogue.title, catalogue.director
                    FROM catalogue AS catalogue
                    JOIN movies_location AS rented_movies
                    ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = ? && rented_movies.is_loc = 1');
            $rented_movies_req->execute(array($_GET['id']));
            $user_rented_list_now = $rented_movies_req->fetchAll(PDO::FETCH_ASSOC);

            ?>
            <section id="current_locations" class="container">
                <div class="row mb-5 mt-4">
                    <a href="espace_perso_user.php?id=<?= $_GET['id']; ?>" class="p-0 mb-4"><div class="d-flex justify-content-start"><button class="btn btn-light d-flex align-items-center"><i class='bx bx-arrow-back'></i><span class="ml-2">Retour</span></button></div></a>
                    <h3 class="p-2 rounded yellow-bg d-flex align-items-center"><span class="mr-2">Locations en cours</span><i class='bx bx-cart-download'></i></h3>
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
                                $location_date = date("d/m/Y", strtotime($location_date));
                                ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start mb-4">
                                    <div class="card movie">
                                        <a href="catalogue.php?movie=<?= $movie_isloc['id'] ?>">
                                            <div class="card-body movie-img"
                                                 style="background: url('img/movies_img/<?= $movie_isloc['movie_picture'] ?>')">
                                                <div id="release_year"><h5><?= $movie_isloc['release_year'] ?></h5>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer">
                                            <a href="catalogue.php?movie=<?= $movie_isloc['id'] ?>">
                                                <h3><?= $movie_isloc['title']; ?></h3></a>
                                            <small>De <?= $movie_isloc['director'] ?></small>
                                            <small>Loué le: <?= $location_date ?></small>
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
            <?php
        } else {
            echo "<h5 class='text-light justify-content-center align-items-center d-flex' id='notfound'>Erreur 404: Utilisateur introuvable !</h5>";
        }
        ?>
    </main>
    <?php include 'php_assets/footer.php' ?>
    </body>
    </html>
    <?php
} else {
    header('location: espace_perso_user.php?id=' . $_SESSION['id'] . '');
}
?>