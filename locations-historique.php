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

            // On détermine la page courrante.
            if (isset($_GET['page']) && !empty($_GET['page'])) {
                $currentPage = (int)strip_tags($_GET['page']);
            } else {
                $currentPage = 1;
            }

            // On compte le nombre de film.
            $rented_movies_req_count = $conn->prepare('
            SELECT COUNT(*) AS nb_movies
                FROM catalogue AS catalogue
                JOIN movies_location AS rented_movies
                ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = ?');
            $rented_movies_req_count->execute(array(nettoyage($_GET['id'])));
            $result = $rented_movies_req_count->fetch();
            $nbMovies = (int)$result['nb_movies'];

            // On determine le nombre de film par page.
            $parPage = 12;
            $pages = ceil($nbMovies / $parPage);
            $premier = ($currentPage * $parPage) - $parPage;

            // On sécurise l'accès au pages qui n'existe pas.
            $id_user = nettoyage($_GET['id']);

            if($currentPage > $pages){
                header("location: locations-historique.php?id=$id_user&page=1");
            }

            //Requête pour recupérer les films loués par l'utilisateur
            $rented_movies_req = $conn->prepare('
            SELECT DISTINCT catalogue.id, catalogue.movie_picture, catalogue.release_year, catalogue.title, catalogue.director
                FROM catalogue AS catalogue
                JOIN movies_location AS rented_movies
                ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = :id ORDER BY id DESC LIMIT :premier, :parpage;');
            $rented_movies_req->bindValue(':id', nettoyage($_GET['id']));
            $rented_movies_req->bindValue(':premier', $premier, PDO::PARAM_INT);
            $rented_movies_req->bindValue(':parpage', $parPage, PDO::PARAM_INT);
            $rented_movies_req->execute();
            $user_rented_list = $rented_movies_req->fetchAll(PDO::FETCH_ASSOC);

            ?>
            <section id="past_locations" class="container">
                <div class="row mb-5 mt-4">
                    <a href="espace_perso_user.php?id=<?= $_GET['id']; ?>" class="p-0 mb-4"><div class="d-flex justify-content-start"><button class="btn btn-light d-flex align-items-center"><i class='bx bx-arrow-back'></i><span class="ml-2">Retour</span></button></div></a>
                    <h3 class="p-2 rounded yellow-bg d-flex align-items-center"><span class="mr-2">Historique des locations</span><i class='bx bx-history'></i></h3>
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
                                $location_date = date("d/m/Y", strtotime($location_date));

                                if ($location['date_location_end'] != null) {
                                    $location_date_end = $location['date_location_end'];
                                    $location_date_end = date("d/m/Y", strtotime($location_date_end));
                                }

                                $is_loc = $location['is_loc'];
                                ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start mb-4">
                                    <div class="card movie">
                                        <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                            <div class="card-body movie-img"
                                                 style="background: url('img/movies_img/<?= $movie['movie_picture'] ?>')">
                                                <div id="release_year"><h5><?= $movie['release_year'] ?></h5></div>
                                            </div>
                                        </a>
                                        <div class="card-footer">
                                            <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                                <h3><?= $movie['title']; ?></h3>
                                            </a>
                                            <small>De <?= $movie['director'] ?></small>
                                            <small>Loué le: <?= $location_date ?></small>
                                            <small><?php if ($is_loc === 1) {
                                                    echo "Status: En cours";
                                                } else {
                                                    echo "Location terminé le: $location_date_end";
                                                } ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                        } else {
                            echo "<h5 class='no-data'>Aucun film n'a été loué pour le moment...</h5>";
                        }
                        ?>
                        <?php
                        if ($nbMovies >= $parPage) {
                            ?>
                            <ul class="pagination mt-5 d-flex justify-content-end">
                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                    <a href="locations-historique.php?id=<?= $_GET['id']; ?>" class="page-link">Précédente</a>
                                </li>
                                <?php for ($page = 1; $page <= $pages; $page++): ?>
                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                        <a href="locations-historique.php?id=<?= $_GET['id']; ?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                    </li>
                                <?php endfor ?>
                                <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                    <a href="locations-historique.php?id=<?= $_GET['id']; ?>&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                                </li>
                            </ul>
                            <?php
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