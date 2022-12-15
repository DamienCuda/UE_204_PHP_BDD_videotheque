<?php
require_once("php_assets/connectdb.php");
require("php_assets/verif_session_connect.php");
require("php_assets/fonctions.php");
?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'php_assets/head.php' ?>

<?php
// On récupère les film dans le catalogue.
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
$parPage = 16;
$pages = ceil($nbMovies / $parPage);
$premier = ($currentPage * $parPage) - $parPage;

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
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <input type="text" class="form-control" id="search-movie" name="search-movie"
                       placeholder="Rechercher un film...">
            </div>
        </div>
        <div id="catalogue">
            <?php
            if ($is_admin == 0){

            if (!isset($_GET['movie'])){ ?>
            <div class="row mt-5">
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
                            <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                <div class="card-body movie-img"
                                     style="background: url('img/movies_img/<?= $movie['movie_picture'] ?>')">
                                    <div id="release_year"><h5><?= $movie['release_year'] ?></h5></div>
                                </div>
                            </a>
                            <div class="card-footer">
                                <a href="catalogue.php?movie=<?= $movie['id'] ?>"><h3><?= $movie['title']; ?></h3></a>
                                <small>De <?= $movie['director'] ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            if (count($movies) > $parPage) {
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
                            <div class="movie_picture"
                                 style="background: url('img/movies_img/<?= $movieData['movie_picture'] ?>');"></div>
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
                                <div class="d-flex btn-zone justify-content-between justify-content-sm-between justify-content-md-between justify-content-lg-between justify-content-xl-between">

                                    <button class="btn btn-light btn-back d-flex align-items-center justify-content-between"
                                            id="back"><i class='bx bx-left-arrow-alt'></i><span>Retour</span></button>
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
                                            <button class="btn btn-warning d-flex align-items-center justify-content-between btn-price"
                                                    id="location_button"><span>Louer ce film</span><span
                                                        class="d-flex align-items-center"><?= $movieData['price'] ?><i
                                                            class='bx bx-coin'></i></span></button>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <button class="btn btn-warning d-flex align-items-center justify-content-between btn-show-movie"
                                                id="show_movie_button"><span>Voir ce film</span><span
                                                    class="d-flex align-items-center"><i class='bx bx-show'></i></span>
                                        </button>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        } else {
            ?>
            <div class="row">
            <?php
                $filmReq = $conn->prepare('SELECT * FROM catalogue');
                $filmReq->execute();
                $films = $filmReq->fetchAll();
            ?>
                <section id="gestion_film" class="mt-5">
                    <table class="table table-striped responsive nowrap" id="gestion_filù_table">
                        <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">ID</th>
                            <th class="text-center">Poster</th>
                            <th class="text-center">Titre</th>
                            <th class="text-center">Réalisateur</th>
                            <th class="text-center">Acteurs</th>
                            <th class="text-center">Genre</th>
                            <th class="text-center">Date de sortie</th>
                            <th class="text-center">Durée</th>
                            <th class="text-center">Synopsis</th>
                            <th class="text-center">Prix</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($films as $film){
                            ?>
                            <tr class="<?php if($film['id'] == $_SESSION['id']){ echo "you"; } ?>">
                                <td id="<?= $film['id']; ?>" class="checkbox_table"></td>
                                <td><?= $film['id']; ?></td>
                                <td><?= $film['movie_picture']; ?></td>
                                <td><?= $film['title']; ?></td>
                                <td><?= $film['director']; ?></td>
                                <td><?= $film['acteurs']; ?></td>
                                <td><?= $film['genre']; ?></td>
                                <td><?= $film['release_year']; ?></td>
                                <td><?= $film['duration']; ?></td>
                                <td><?= $film['synopsis']; ?></td>
                                <td><?= $film['price']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr id="add_film_row">

                                <?php
                                    $pdoID = $conn->prepare("SELECT MAX(id) AS max_id FROM catalogue");
                                    $pdoID->execute();
                                    $pdoID = $pdoID->fetch();
                                    $max_incrementation = $pdoID['max_id'] + 1;
                                ?>

                                <td></td>
                                <td><?= $max_incrementation ?></td>
                                <td><div class="profil-membre" style="background: url('img/profil_img/avatar.jpg');"></div></td>
                                <td style="height: 60px">
                                    <input type="text" name="title_add" id="title_add" class="form-control" placeholder="Nom du film...">
                                </td>
                                <td>
                                    <input type="text" name="director_add" id="director_add" class="form-control" placeholder="Nom du réalisateur...">
                                </td>
                                <td>
                                    <input type="text" name="acteurs_add" id="acteurs_add" class="form-control" placeholder="Nom des acteurs...">
                                </td>
                                <td>
                                    <input type="text" name="genre_add" id="genre_add" class="form-control" placeholder="Genres du film...">
                                </td>
                                <td>
                                    <input type="text" name="year_add" id="year_add" class="form-control" placeholder="Année de sortie...">
                                </td>
                                <td>
                                    <input type="text" name="duration_add" id="duration_add" class="form-control" placeholder="Durée du film...">
                                </td>
                                <td>
                                    <input type="textarea" name="synopsis_add" id="synopsis_add" class="form-control" placeholder="Synopsis du film...">
                                </td>
                                <td>
                                    <input type="text" name="price_add" id="price_add" class="form-control" placeholder="Prix du film...">
                                </td>
                                <td><button class="btn btn-warning" style="background: #ffca2c !important;" id="add_film_btn">Ajouter</button></td>
                            </tr>
                        </tfoot>
                    </table>
                </section>
            </div>
            <?php
        }
        ?>
    </div>
</main>
<?php include 'php_assets/footer.php' ?>
<script src="js/back.js"></script>
</body>
</html>