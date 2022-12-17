<?php
require_once("php_assets/connectdb.php");
require("php_assets/verif_session_connect.php");
require("php_assets/fonctions.php");
require("php_assets/permission.php");
?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'php_assets/head.php' ?>

<?php
// On détermine la page courrante.
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int)strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}

// On compte le nombre de film.
$movieCount = $conn->prepare('SELECT COUNT(*) AS nb_movies FROM catalogue');
$movieCount->execute();
$result = $movieCount->fetch();
$nbMovies = (int)$result['nb_movies'];

// On determine le nombre de film par page.
$parPage = 12;
$pages = ceil($nbMovies / $parPage);
$premier = ($currentPage * $parPage) - $parPage;

// On sécurise l'accès au pages qui n'existe pas.
if($currentPage > $pages){
    header("location: catalogue.php?page=1");
}

// On récupère les film de la page en cours.
$movieDisplay = $conn->prepare('SELECT * FROM catalogue ORDER BY id DESC LIMIT :premier, :parpage;');
$movieDisplay->bindValue(':premier', $premier, PDO::PARAM_INT);
$movieDisplay->bindValue(':parpage', $parPage, PDO::PARAM_INT);
$movieDisplay->execute();
$movies = $movieDisplay->fetchAll(PDO::FETCH_ASSOC);
?>

<body id="movieWallpaper">
<?php include 'php_assets/header.php' ?>
<main>
    <div id="loading">
        <div class="loader_search"></div>
    </div>
    <div class="container">
        <div id="catalogue">
            <?php
            if (!isset($_GET['movie']) && !isset($_GET["genre"])){ ?>
            <div class="row mt-5">
                <?php if($is_admin == 1 && $permission >= 1){
                ?>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 mb-3 mb-sm-3 mb-md-3 mb-lg-5 mb-xl-5">
                        <input type="text" class="form-control" id="search-movie" name="search-movie" placeholder="Rechercher un film...">
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 mb-5">
                        <a href="add-movie.php">
                            <button class="btn btn-warning col-3 align-items-center d-flex justify-content-center" style="width:100%"><span>Ajouter un film</span><i class='bx bxs-folder-plus ml-2'></i></button>
                        </a>
                    </div>
                <?php
                }else{
                ?>
                    <div class="col-12 mb-5">
                        <input type="text" class="form-control" id="search-movie" name="search-movie" placeholder="Rechercher un film...">
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="row" id="movie_display">
                    <?php
                    foreach ($movies as $movie):

                        // On verifie si le film est présent dans la table movies_bookmark.
                        $moviesReq = $conn->prepare('SELECT * FROM movies_bookmark WHERE movie_id = ? AND user_id = ? LIMIT 1');
                        $moviesReq->execute([
                            $movie['id'],
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
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start">
                            <div class="card movie mb-5">
                                <a href="php_assets/add-bookmark.php?movie=<?= $movie['id'] ?>&page=catalogue">
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
                                    nettoyage($movie['id']),
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

                                    <a href="php_assets/location.php?movie=<?= $movie['id']; ?>" class="btn_location_movie"><div id="location_btn" style="z-index: 10"><span>Louer ce film</span><span class="d-flex align-items-center ml-2">3<i class="bx bx-coin"></i></span></div></a>

                                    <?php
                                }else{
                                    ?>
                                    <a href="#" class="btn_view_movie"><div id="view_btn"><span>Voir ce film</span><span class="d-flex align-items-center ml-2"><i class='bx bx-show'></i></span></div></a>
                                    <?php
                                }
                                ?>
                                <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                    <div class="card-body movie-img"
                                         style="background: url('img/movies_img/<?= $movie['movie_picture'] ?>')">
                                        <div id="release_year"><h5><?= $movie['release_year'] ?></h5></div>
                                    </div>
                                </a>
                                <div class="card-footer d-flex flex-column justify-content-between">
                                    <a href="catalogue.php?movie=<?= $movie['id'] ?>"><h4><?= $movie['title']; ?></h4></a>
                                    <small>De <?= $movie['director'] ?></small>
                                    <?php
                                        $id_movie = $movie['id'];

                                        if($is_admin == 1 && $permission >= 1){

                                            echo "<a href='edit-movie.php?movie=$id_movie'><button class='btn btn-warning mt-1 align-items-center justify-content-center d-flex' style='width:100%'><span>Modifier</span><i class='bx bx-edit-alt ml-2' ></i></button></a>";

                                            if($permission >= 2){
                                                echo "<a href='delete-movie.php?id=$id_movie'><button class='btn btn-danger mt-1 align-items-center justify-content-center d-flex' style='width:100%'><span>Supprimer</span><i class='bx bx-trash ml-2'></i></button></a>";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

            </div>
            <label id="title-search" class="text-light mb-3">Recherche:</label>
            <br>
            <div id="result-search" class="row"></div>
            <?php
            if ($nbMovies >= $parPage) {
                ?>
                <ul class="pagination mt-5 d-flex justify-content-end">
                    <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                    <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                        <a href="catalogue.php?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                    </li>
                    <?php for ($page = 1; $page <= $pages; $page++): ?>
                        <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                        <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                            <a href="catalogue.php?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                        </li>
                    <?php endfor ?>
                    <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                        <a href="catalogue.php?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                    </li>
                </ul>
                <?php
            }
            ?>
        </div>
        <?php } else if (isset($_GET['movie']) && $_GET['movie'] != "") {
            $id_movie = nettoyage($_GET['movie']);
            // On récupère les information du film grâce au paramètre en url.
            $movieDataReq = $conn->prepare('SELECT * FROM catalogue WHERE id = ? LIMIT 1');
            $movieDataReq->execute([
                $id_movie
            ]);
            $movieData = $movieDataReq->fetch();

            ?>
            <button class="btn btn-light btn-back d-flex align-items-center justify-content-between mt-5" id="back"><i
                        class='bx bx-left-arrow-alt'></i><span>Retour</span></button>
            <div class="card mt-3">
                <h5 class="card-header"><?= $movieData['title']; ?></h5>
                <div class="card-body" id="movie-details">
                    <?php

                    $movie_id = nettoyage($_GET['movie']);

                    // On verifie si le film est présent dans la table movies_bookmark.
                    $moviesReq = $conn->prepare('SELECT * FROM movies_bookmark WHERE movie_id = ? AND user_id = ? LIMIT 1');
                    $moviesReq->execute([
                        $movie_id,
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
                    <a href="php_assets/add-bookmark.php?movie=<?= $movie_id; ?>&page=film_details">
                        <div class="bookmark bookmark-right"><i class='bx <?php if ($is_bookmark) {
                                echo "bxs-bookmark";
                            } else {
                                echo "bx-bookmark";
                            } ?>'></i></div>
                    </a>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 justify-content-sm-center justify-content-center justify-content-md-center justify-content-lg-center justify-content-xl-start">
                            <div class="movie_picture d-flex align-items-center justify-content-center" id="movie_trailer_btn" style="background: url('img/movies_img/<?= $movieData['movie_picture'] ?>');"><div id="overlay"><i class='bx bx-play play-ba'></i></div></div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 cold-lg-12 col-xl-9 justify-content-sm-center justify-content-center justify-content-md-center justify-content-lg-center justify-content-xl-start mt-3 mt-sm-3 mt-md-3 mt-lg-3 mt-xl-0">
                            <div class="row">
                                <div class="col-12 d-flex">
                                    <label>Genre:</label>
                                    <p class="details genre">
                                        <?php
                                        // On récupère le champs "genre" pour explode les valeurs et les afficher saparément.
                                        $genres = explode(",", $movieData['genre']);
                                        foreach ($genres as $index => $genre) {
                                            ?>
                                            <span>
                                                <a href="catalogue.php?genre=<?= $genre; ?>">
                                                <?php
                                                // On affiche une virgule après chaque genre sauf le dernier.
                                                echo $genre;
                                                ?>
                                                </a>
                                                    <?php
                                                    if ($index + 1 < count($genres)) {
                                                        echo ",";
                                                    }
                                                    ?>
                                            </span>
                                            <?php
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-12 d-flex">
                                    <label>Acteurs:</label>
                                    <p class="details">
                                        <?php
                                        // On récupère le champs "genre" pour explode les valeurs et les afficher saparément.
                                        $acteurs = explode(",", $movieData['acteurs']);
                                        foreach ($acteurs as $index => $acteur) {
                                            ?>
                                            <span>
                                                    <?php
                                                    // On affiche une virgule après chaque genre sauf le dernier.
                                                    echo $acteur;
                                                    ?>
                                                    <?php
                                                    if ($index + 1 < count($acteurs)) {
                                                        echo ",";
                                                    }
                                                    ?>
                                                </span>
                                            <?php
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-12 d-flex">
                                    <label>Réalisateur:</label>
                                    <p class="details"><?= $movieData['director'] ?></p>
                                </div>
                                <div class="col-12 d-flex">
                                    <label>Année de sortie:</label>
                                    <p class="details"><?= $movieData['release_year'] ?></p>
                                </div>
                                <div class="col-12 d-flex">
                                    <label>Durée:</label>
                                    <p class="details"><?= $movieData['duration'] ?></p>
                                </div>
                                <div class="col-12 mt-3">
                                    <label>Synopsis:</label>
                                    <p class="synospis"><?= $movieData['synopsis'] ?></p>
                                </div>
                            </div>
                            <div class="col-12 mt-5">
                                <div class="d-flex btn-zone justify-content-end">
                                    <?php
                                    // On récupère les infos de location du film.
                                    $locationData = $conn->prepare('SELECT * FROM movies_location WHERE movie_id = ? AND user_id = ?');
                                    $locationData->execute([
                                        nettoyage($_GET['movie']),
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
                                        <a href="php_assets/location.php?movie=<?= $_GET['movie']; ?>">
                                            <button class="btn btn-warning d-flex align-items-center justify-content-end btn-price"
                                                    id="location_button"><span>Louer ce film</span><span
                                                        class="d-flex align-items-center ml-2"><?= $movieData['price'] ?><i
                                                            class='bx bx-coin'></i></span></button>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <button class="btn btn-warning d-flex align-items-center justify-content-end btn-show-movie"
                                                id="show_movie_button"><span>Voir ce film</span><span
                                                    class="d-flex align-items-center"><i class='bx bx-show ml-2'></i></span>
                                        </button>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="d-flex col-12 justify-content-between mt-3">
                                    <?php
                                    $id_movie = $_GET['movie'];

                                    if($permission >= 2){
                                        echo "<a href='delete-movie.php?id=$id_movie'><button class='btn btn-danger mt-1 align-items-center justify-content-center d-flex' style='width:100%'><span>Supprimer</span><i class='bx bx-trash ml-2'></i></button></a>";
                                    }

                                    if($is_admin == 1 && $permission >= 1){
                                        echo "<a href='edit-movie.php?movie=$id_movie'><button class='btn btn-warning mt-1 align-items-center justify-content-center d-flex' style='width:100%'><span>Modifier</span><i class='bx bx-edit-alt ml-2' ></i></button></a>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Trailer -->
            <div class="modal fade" id="trailerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel"><span style="margin-right: 10px;"><?= $movieData['title'] ?> - Bande d'annonce</span><i class='bx bx-movie-play'></i></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <iframe width="560" height="315" src="" id="trailer_player" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">FERMER</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }else if(isset($_GET["genre"]) && $_GET["genre"] != ""){

            // On détermine la page courrante.
            if (isset($_GET['page']) && !empty($_GET['page'])) {
                $currentPage = (int)strip_tags($_GET['page']);
            } else {
                $currentPage = 1;
            }

            $genre_search = nettoyage(trim($_GET['genre']));

            // On compte le nombre de film.
            $movieCount = $conn->prepare('SELECT COUNT(*) AS nb_movies FROM catalogue WHERE genre LIKE ?');
            $movieCount->execute((array("%$genre_search%")));
            $result = $movieCount->fetch();
            $nbMovies = (int)$result['nb_movies'];

            // On determine le nombre de film par page.
            $parPage = 12;
            $pages = ceil($nbMovies / $parPage);
            $premier = ($currentPage * $parPage) - $parPage;

            // On sécurise l'accès au pages qui n'existe pas.
            if($currentPage > $pages){
                header("location: catalogue.php?page=1");
            }

            // On récupère les film de la page en cours.
            $movieDisplay = $conn->prepare('SELECT * FROM catalogue WHERE genre like :genre ORDER BY id DESC LIMIT :premier, :parpage;');
            $movieDisplay->bindValue(':genre', "%$genre_search%");
            $movieDisplay->bindValue(':premier', $premier, PDO::PARAM_INT);
            $movieDisplay->bindValue(':parpage', $parPage, PDO::PARAM_INT);
            $movieDisplay->execute();
            $movies = $movieDisplay->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <button class="btn btn-light btn-back d-flex align-items-center justify-content-between mt-5 mb-3" id="back"><i class='bx bx-left-arrow-alt'></i><span>Retour</span></button>

            <div class="row" id="movie_display">
                <?php
                foreach ($movies as $movie):

                    // On verifie si le film est présent dans la table movies_bookmark.
                    $moviesReq = $conn->prepare('SELECT * FROM movies_bookmark WHERE movie_id = ? AND user_id = ? LIMIT 1');
                    $moviesReq->execute([
                        $movie['id'],
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
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start">
                        <div class="card movie mb-5">
                            <a href="php_assets/add-bookmark.php?movie=<?= $movie['id'] ?>&page=catalogue">
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
                                nettoyage($movie['id']),
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

                                <a href="php_assets/location.php?movie=<?= $movie['id']; ?>" class="btn_location_movie"><div id="location_btn" style="z-index: 10"><span>Louer ce film</span><span class="d-flex align-items-center ml-2">3<i class="bx bx-coin"></i></span></div></a>

                                <?php
                            }else{
                                ?>
                                <a href="#" class="btn_view_movie"><div id="view_btn"><span>Voir ce film</span><span class="d-flex align-items-center ml-2"><i class='bx bx-show'></i></span></div></a>
                                <?php
                            }
                            ?>
                            <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                <div class="card-body movie-img"
                                     style="background: url('img/movies_img/<?= $movie['movie_picture'] ?>')">
                                    <div id="release_year"><h5><?= $movie['release_year'] ?></h5></div>
                                </div>
                            </a>
                            <div class="card-footer d-flex flex-column justify-content-between">
                                <a href="catalogue.php?movie=<?= $movie['id'] ?>"><h4><?= $movie['title']; ?></h4></a>
                                <small>De <?= $movie['director'] ?></small>
                                <?php
                                $id_movie = $movie['id'];

                                if($is_admin == 1 && $permission >= 1){

                                    echo "<a href='edit-movie.php?movie=$id_movie'><button class='btn btn-warning mt-1 align-items-center justify-content-center d-flex' style='width:100%'><span>Modifier</span><i class='bx bx-edit-alt ml-2' ></i></button></a>";

                                    if($permission >= 2){
                                        echo "<a href='delete-movie.php?id=$id_movie'><button class='btn btn-danger mt-1 align-items-center justify-content-center d-flex' style='width:100%'><span>Supprimer</span><i class='bx bx-trash ml-2'></i></button></a>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <label id="title-search" class="text-light mb-3">Recherche:</label>
            <br>
            <div id="result-search" class="row"></div>
            <?php
            if ($nbMovies >= $parPage) {
                ?>
                <ul class="pagination mt-5 d-flex justify-content-end">
                    <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                    <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                        <a href="catalogue.php?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                    </li>
                    <?php for ($page = 1; $page <= $pages; $page++): ?>
                        <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                        <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                            <a href="catalogue.php?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                        </li>
                    <?php endfor ?>
                    <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                        <a href="catalogue.php?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                    </li>
                </ul>
                <?php
            }
            ?>
        </div>
        <?php
        }
        ?>
    </div>
</main>
<?php include 'php_assets/footer.php' ?>
<script src="js/back.js"></script>
<script src="js/trailer.js"></script>
<script src="js/search_movie.js"></script>
</body>
</html>