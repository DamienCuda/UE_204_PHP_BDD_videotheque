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
            // On compte le nombre de film.
            $rented_movies_req_count = $conn->prepare('
            SELECT COUNT(*) AS nb_movies
                    FROM catalogue AS catalogue
                    JOIN movies_bookmark AS fav_movies
                    ON catalogue.id = fav_movies.movie_id && fav_movies.user_id = ?');
            $rented_movies_req_count->execute(array(nettoyage($_GET['id'])));
            $result = $rented_movies_req_count->fetch();
            $nbMovies = (int)$result['nb_movies'];

            // On determine le nombre de film par page.
            $parPage = 12;
            $pages = ceil($nbMovies / $parPage);
            $premier = ($currentPage * $parPage) - $parPage;

            // On sécurise l'accès au pages qui n'existe pas.
            $id_user = nettoyage($_GET['id']);

            if($currentPage > $pages && $pages > 0){
                header("location: liste.php?id=$id_user&page=1");
            }


            //Requête pour recupérer les films favoris de l'utilisateur.
            $fav_movies_req = $conn->prepare('
                SELECT DISTINCT catalogue.id, catalogue.movie_picture, catalogue.release_year, catalogue.title, catalogue.director
                    FROM catalogue AS catalogue
                    JOIN movies_bookmark AS fav_movies
                    ON catalogue.id = fav_movies.movie_id && fav_movies.user_id = :id LIMIT :premier, :parpage;');
            $fav_movies_req->bindValue(':id', nettoyage($_GET['id']));
            $fav_movies_req->bindValue(':premier', $premier, PDO::PARAM_INT);
            $fav_movies_req->bindValue(':parpage', $parPage, PDO::PARAM_INT);
            $fav_movies_req->execute();
            $user_fav_lists = $fav_movies_req->fetchAll(PDO::FETCH_ASSOC);

            ?>
            <section id="current_locations" class="container">
                <div class="row mb-5 mt-4">
                    <div class="d-flex justify-content-start p-0 mb-4"><button class="btn btn-light d-flex align-items-center" id="back"><i class='bx bx-arrow-back'></i><span class="ml-2">Retour</span></button></div>
                    <h3 class="p-2 rounded yellow-bg d-flex align-items-center"><span class="mr-2">Ma liste de films</span><i class='bx bx-bookmark'></i></h3>
                    <div class="row mt-5">
                        <?php
                        if (count($user_fav_lists) > 0) {
                            foreach ($user_fav_lists as $user_fav_list):

                                // On verifie si le film est présent dans la table movies_bookmark.
                                $moviesReq = $conn->prepare('SELECT * FROM movies_bookmark WHERE movie_id = ? AND user_id = ? LIMIT 1');
                                $moviesReq->execute([
                                    $user_fav_list['id'],
                                    $_SESSION['id']
                                ]);
                                $bookmark = $moviesReq->fetchAll();

                                // Si il est présent on passe la bool $is_bookmark à true.
                                if (count($bookmark) > 0) {
                                    $is_bookmark = true;
                                } else {
                                    $is_bookmark = false;
                                }

                                ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start mb-4">
                                    <div class="card movie">
                                        <a href="php_assets/add-bookmark.php?movie=<?= $user_fav_list['id'] ?>&page=catalogue">
                                            <div class="bookmark">
                                                <i class='bx <?php if ($is_bookmark) {
                                                    echo "bxs-bookmark";
                                                } else {
                                                    echo "bx-bookmark";
                                                } ?>'></i>
                                            </div>
                                        </a>
                                        <?php
                                        // On récupère les infos de location du film.
                                        $locationData = $conn->prepare('SELECT * FROM movies_location WHERE movie_id = ? AND user_id = ?');
                                        $locationData->execute([
                                            nettoyage($user_fav_list['id']),
                                            $_SESSION['id']
                                        ]);
                                        $locations = $locationData->fetchAll();

                                        // On vérifie si le film est déjà présent dans la table des location, sinon on met is_loc à 0.
                                        if (count($locations) > 0) {
                                            foreach ($locations as $location) {
                                                $is_loc = $location['is_loc'];
                                            }
                                        } else {
                                            $is_loc = 0;
                                        }

                                        if ($is_loc === 0) {
                                            ?>

                                            <a href="php_assets/location.php?movie=<?= $user_fav_list['id']; ?>" class="btn_location_movie"><div id="location_btn" style="z-index: 10"><span>Louer ce film</span><span class="d-flex align-items-center ml-2">3<i class="bx bx-coin"></i></span></div></a>

                                            <?php
                                        }else{
                                            ?>
                                            <a href="#" class="btn_view_movie"><div id="view_btn"><span>Voir ce film</span><span class="d-flex align-items-center ml-2"><i class='bx bx-show'></i></span></div></a>
                                            <?php
                                        }
                                        ?>
                                        <a href="catalogue.php?movie=<?= $user_fav_list['id'] ?>">
                                            <div class="card-body movie-img"
                                                 style="background: url('img/movies_img/<?= $user_fav_list['movie_picture'] ?>')">
                                                <div id="release_year"><h5><?= $user_fav_list['release_year'] ?></h5>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer">
                                            <a href="catalogue.php?movie=<?= $user_fav_list['id'] ?>">
                                                <h3><?= $user_fav_list['title']; ?></h3></a>
                                            <small>De <?= $user_fav_list['director'] ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                        } else {
                            echo "<h5 class='no-data'>Aucun film n'est actuellement mis en favoris...</h5>";
                        }
                        ?>
                        <?php
                        if ($nbMovies >= $parPage) {
                            ?>
                            <ul class="pagination mt-5 d-flex justify-content-end">
                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                    <a href="liste.php?id=<?= $_GET['id']; ?>" class="page-link">Précédente</a>
                                </li>
                                <?php for ($page = 1; $page <= $pages; $page++): ?>
                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                        <a href="liste.php?id=<?= $_GET['id']; ?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                    </li>
                                <?php endfor ?>
                                <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                    <a href="liste.php?id=<?= $_GET['id']; ?>&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
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
    <script src="js/back.js"></script>
    </body>
    </html>
    <?php
} else {
    header('location: espace_perso_user.php?id=' . $_SESSION['id'] . '');
}
?>
