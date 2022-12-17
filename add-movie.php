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
                        <label for="movie_img" style="width:100%">
                            <div class="movie_picture" style="background: url('img/movies_img/no_img.jpg');"><div id="overlay"><i class='bx bxs-camera-plus picure_add'></i></div></div>
                        </label>
                        <input type="file" name="movie_img" id="movie_img" hidden>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 cold-lg-12 col-xl-9 justify-content-sm-center justify-content-center justify-content-md-center justify-content-lg-center justify-content-xl-start mt-3 mt-sm-3 mt-md-3 mt-lg-3 mt-xl-0">
                        <div class="row col-12">
                            <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                    <label>Genre:</label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <input type="text" class="form-control" name="movie_genre" id="movie_genre" placeholder="Genres">
                                </div>
                            </div>
                            <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                    <label>Acteurs:</label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <input type="text" class="form-control" name="movie_actor" id="movie_actor" placeholder="Acteurs">
                                </div>
                            </div>
                            <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                    <label>Réalisateur:</label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <input type="text" class="form-control" name="movie_director" id="movie_director" placeholder="Réalisateurs">
                                </div>
                            </div>
                            <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                    <label>Année de sortie:</label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <input type="number" class="form-control" name="annee_sortie" id="annee_sortie" placeholder="Année de sortie" value="2022">
                                </div>
                            </div>
                            <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                    <label>Durée:</label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <input type="text" class="form-control" name="movie_duration" id="movie_duration" placeholder="Durée" value="1">
                                    <script>
                                        $('#movie_duration').datetimepicker({
                                            format: 'hh:mm'
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="col-12 d-flex align-items-start mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                    <label>Synopsis:</label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <textarea class="form-control" name="movie_synopsis" id="movie_synopsis" placeholder="Synopsis (minimum 20 caractères)" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-12 d-flex align-items-start mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                    <label>Prix (en jeton):</label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <input type="number" class="form-control" name="price_movie" id="price_movie" placeholder="Prix de la location" value="3">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="d-flex btn-zone justify-content-end">
                                <button class="btn btn-warning d-flex align-items-center justify-content-end" id="add_movie_btn"><span>Ajouter le film</span><i class='bx bx-plus ml-2'></i></button>
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
<script src="js/tags_add_movie.js"></script>
<script src="js/add_movie.js"></script>
</body>
</html>