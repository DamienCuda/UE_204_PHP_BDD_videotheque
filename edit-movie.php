<?php
    require_once("php_assets/connectdb.php");
    require("php_assets/verif_session_connect.php");
    require("php_assets/fonctions.php");
    require("php_assets/permission.php");

    if($is_admin === false){
        header("location: index.php");
    }

    // On récupère les infos du film.
    $movieData = $conn->prepare('SELECT * FROM catalogue WHERE id = ?');
    $movieData->execute([
        nettoyage($_GET['movie'])
    ]);
    $movies = $movieData->fetchAll();

    // On check si le film existe, si ce n'est pas le cas on retourne sur le catalogue.
    if(count($movies) == 0){
        header("location: catalogue.php");
    }else{
        foreach($movies as $movie){
            $movie_img = $movie['movie_picture'];
            $movie_title = $movie['title'];
            $movie_director = $movie['director'];
            $movie_acteurs = $movie['acteurs'];
            $movie_genre = $movie['genre'];
            $movie_annee = $movie['release_year'];
            $movie_duration = $movie['duration'];
            $movie_synopsis = $movie['synopsis'];
            $movie_price = $movie['price'];
        }

        // On reformat la durée pour être compatible avec notre input
        $movie_duration_changed = str_replace("h", ":", $movie_duration);
    }

?>

<!DOCTYPE html>
<html lang="fr">
    <?php include 'php_assets/head.php' ?>
    <body id="movieWallpaper">
        <?php include 'php_assets/header.php' ?>
        <main>
            <div class="container">
                <!-- BEGIN: Bouton retour via historique -->
                <button class="btn btn-light btn-back d-flex align-items-center justify-content-between mt-5" id="back"><i class='bx bx-left-arrow-alt'></i><span>Retour</span></button>
                <!-- END: Bouton retour via historique -->

                <!-- BEGIN: Card du film -->
                <div class="card mt-3">
                    <!-- BEGIN: Card header du film -->
                    <h5 class="card-header"><input type="text" class="form-control" name="movie_title" id="movie_title" value="<?= $movie_title; ?>" autocomplete="off"></h5>
                    <!-- END: Card header du film -->

                    <!-- BEGIN: Card body du film -->
                    <div class="card-body" id="movie-details">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 justify-content-sm-center justify-content-center justify-content-md-center justify-content-lg-center justify-content-xl-start">
                                <label for="movie_img" style="width:100%">
                                    <div class="movie_picture" style="background: url('img/movies_img/<?= $movie_img; ?>');"><div id="overlay"><i class='bx bxs-camera-plus picture_add'></i></div></div>
                                </label>
                                <input type="file" name="movie_img" id="movie_img" value="<?= $movie_img; ?>" hidden>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 cold-lg-12 col-xl-9 justify-content-sm-center justify-content-center justify-content-md-center justify-content-lg-center justify-content-xl-start mt-3 mt-sm-3 mt-md-3 mt-lg-3 mt-xl-0">
                                <div class="row col-12">
                                    <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 label-movie-admin">
                                            <label>Genre:</label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                            <input type="text" class="form-control" name="movie_genre" id="movie_genre" placeholder="Genres" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 label-movie-admin">
                                            <label>Acteurs:</label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                            <input type="text" class="form-control" name="movie_actor" id="movie_actor" placeholder="Acteurs" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 label-movie-admin">
                                            <label>Réalisateur:</label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                            <input type="text" class="form-control" name="movie_director" id="movie_director" value="<?= $movie_director; ?>" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 label-movie-admin">
                                            <label>Année de sortie:</label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                            <input type="number" class="form-control" name="annee_sortie" id="annee_sortie" placeholder="Année de sortie" value="<?= $movie_annee; ?>" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex align-items-center mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 label-movie-admin">
                                            <label>Durée:</label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                            <input type="text" class="form-control" name="movie_duration" id="movie_duration" placeholder="Durée" value="<?= $movie_duration_changed; ?>" autocomplete="off">
                                            <script>
                                                $('#movie_duration').datetimepicker({
                                                    format: 'hh:mm'
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex align-items-start mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 label-movie-admin">
                                            <label>Synopsis:</label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                            <textarea class="form-control" name="movie_synopsis" id="movie_synopsis" placeholder="Synopsis (minimum 20 caractères)" rows="4" autocomplete="off"><?= $movie_synopsis; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex align-items-start mb-3 flex-column flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 label-movie-admin">
                                            <label>Prix (en jeton):</label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                            <input type="number" class="form-control" name="price_movie" id="price_movie" placeholder="Prix de la location" value="<?= $movie_price; ?>" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-5">
                                    <div class="d-flex btn-zone justify-content-end">
                                        <button class="btn btn-warning d-flex align-items-center justify-content-end" id="edit_movie_btn"><span>Modifier le film</span><i class='bx bx-edit-alt ml-2'></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Card header du film -->
                </div>
                <!-- END: Card du film -->
            </div>
        </main>
        <?php include 'php_assets/footer.php' ?>

        <?php
            /*
                On récupère la liste de acteurs que l'on va explode afin d'ajouter des guillemet de part et d'autre de chaque acteur,
                Car on en a besoin pour les inétégrer dans la fonction addData() dans notre script JS pour les ajouter au tags.
                On met tout ça dans un nouveau tableau que l'on va transformer en string pour notre fonction JS. (ex: "Chris Brown","Leonardo DiCaprio").
                NOTE/ On oublie pad de retirer les espace grâce à la fonction trim().
             */

            $actors = explode(",", $movie_acteurs);
            $actors_format = array();

            foreach($actors as $actor){
                array_push($actors_format, '"'.trim($actor).'"');
            }

            $result_acteurs = implode(",", $actors_format);

            // On fait pareil pour les genres

            $genres = explode(",", $movie_genre);
            $genres_format = array();

            foreach($genres as $genre){
                array_push($genres_format, '"'.trim($genre).'"');
            }

            $result_genres = implode(",", $genres_format);
        ?>

        <!-- BEGIN: Script qui gère l'affichage des tags -->
        <script>
            var tagGenre = new TagsInput({
                selector: 'movie_genre',
                duplicate : false,
                max : 10
            });

            tagGenre.addData([<?= $result_genres ?>])

            var tagActeur = new TagsInput({
                selector: 'movie_actor',
                duplicate : false,
                max : 10
            });

            tagActeur.addData([<?= $result_acteurs ?>])
        </script>
        <!-- END: Script qui gère l'affichage des tags -->

        <!-- BEGIN: Script JS -->
        <script src="js/back.js"></script>
        <script src="js/edit_movie.js"></script>
        <!-- END: Script JS -->

    </body>
</html>