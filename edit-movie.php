<?php
require_once("php_assets/connectdb.php");
require("php_assets/verif_session_connect.php");
require("php_assets/fonctions.php");
require("php_assets/permission.php");
?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'php_assets/head.php' ?>

<body id="movieWallpaper">
<?php include 'php_assets/header.php' ?>
<main>

    <div class="container">
        <?php if (isset($_GET['movie']) && $_GET['movie'] != "") {
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
                <h5 class="card-header"><input type="text" class="form-control" name="movie_title" id="movie_title"
                                               value="<?= $movieData['title']; ?>"></h5>
                <div class="card-body" id="movie-details">
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
                                    <p class="details"><input type="number" class="form-control" name="annee_sortie"
                                                              id="annee_sortie"
                                                              value="<?= $movieData['release_year'] ?>"></p>
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

                                    <!-- NOTE POUR Julien il faut modifier le lien edit_movie.php -->
                                    <a href="php_assets/edit_movie.php?movie=<?= $_GET['movie']; ?>">
                                        <button class="btn btn-warning d-flex align-items-center justify-content-end">
                                            <span>Modifier</span> <i class='bx bx-edit-alt ml-2'></i></button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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