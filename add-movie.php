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
        <button class="btn btn-light btn-back d-flex align-items-center justify-content-between mt-5" id="back"><i
                    class='bx bx-left-arrow-alt'></i><span>Retour</span></button>
        <div class="card mt-3">
            <h5 class="card-header"><input type="text" class="form-control" name="movie_title" id="movie_title"
                                           placeholder="Titre du film"></h5>
            <div class="card-body" id="movie-details">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 justify-content-sm-center justify-content-center justify-content-md-center justify-content-lg-center justify-content-xl-start">
                        <label for="img_movie" style="width:100%">
                            <div class="movie_picture" style="background: url('img/movies_img/no_img.jpg');"></div>
                            <input type="file" name="movie_img" id="movie_img" hidden></input>
                        </label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 cold-lg-12 col-xl-9 justify-content-sm-center justify-content-center justify-content-md-center justify-content-lg-center justify-content-xl-start mt-3 mt-sm-3 mt-md-3 mt-lg-3 mt-xl-0">
                        <div class="row">
                            <div class="col-12 d-flex">
                                <label>Genre:</label>
                                <p class="details genre">
                                    <span><input type="text" class="form-control" name="movie_genre" id="movie_genre" placeholder="Genres" require></span>
                                </p>
                            </div>
                            <div class="col-12 d-flex">
                                <label>Acteurs:</label>
                                <p class="details">
                                    <span><input type="text" class="form-control" name="movie_actor" id="movie_actor" placeholder="Acteurs" require></span>
                                </p>
                            </div>
                            <div class="col-12 d-flex">
                                <label>Réalisateur:</label>
                                <p class="details">
                                    <span><input type="text" class="form-control" name="movie_director" id="movie_director" placeholder="Réalisateurs" require></span>
                                </p>
                            </div>
                            <div class="col-12 d-flex">
                                <label>Année de sortie:</label>
                                <p class="details"><input type="number" class="form-control" name="annee_sortie" id="annee_sortie" placeholder="Année de sortie" value="2022"></p>
                            </div>
                            <div class="col-12 d-flex">
                                <label>Durée:</label>
                                <p class="details">
                                <span><input type="text" class="form-control" name="movie_duration" id="movie_duration" placeholder="Durée" require></span>
                                </p>
                            </div>
                            <div class="col-12 mt-3">
                                <label>Synopsis:</label>
                                <p class="synospis">
                                <span><textarea class="form-control" name="movie_synopsis" id="movie_synopsis" placeholder="Synopsis" require></textarea></span>
                                </p>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="d-flex btn-zone justify-content-end">

                                <a href="php_assets/add_movie.php?movie=">
                                    <button class="btn btn-warning d-flex align-items-center justify-content-end"><span>Ajouter le film</span><i
                                                class='bx bx-plus ml-2'></i></button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
<?php include 'php_assets/footer.php' ?>
<script src="js/back.js"></script>
</body>
</html>