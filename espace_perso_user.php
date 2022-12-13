<?php
    require_once("php_assets/connectdb.php");
    require ("php_assets/verif_session_connect.php");
    require ("php_assets/fonctions.php");

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
?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'php_assets/head.php'?>

<body>
    <?php include 'php_assets/header.php'?>
    <main>
        <section id ="user_infos_container" class="container">
            <div class="row mt-5">
                <div class="col-12 text-center text-light">
                    <h1>Bienvenue sur votre espace <span class="yellow-txt"><?= $user_login; ?></span></h1>
                </div>
            </div>
            <div class ="row mt-5">
                <div class ="col-3 text-center">
                    <img class="rounded-circle mb-3" src="img/profil_img/<?= $user_profil_pic; ?>" alt="Avatar" style="height:150px">
                </div>
                <div class="col-9 text-light">
                    <h2 class="mb-3 h3">Vos informations</h2>
                    <p class="mb-2">Mail : <?= $user_email; ?></p>
                    <p class="mb-2">Rang : <?= $user_rank; ?></p>
                </div>
            </div>
        </section>
        <section id ="current_locations" class="container">
            <div class="row mt-5">
                <h3 class="p-2 rounded yellow-bg">Location en cours</h3>
                <div>

                </div>
            </div>
        </section>
        <section id ="past_locations" class="container">
            <div class="row mt-5">
                <h3 class="p-2 rounded yellow-bg">Historique de location</h3>
                <div class="mt-3 d-flex">
                    <?php
                        foreach($user_rented_list as $movie):
                    ?>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start">
                        <div class="card movie">
                            <a href="php_assets/add-bookmark.php?movie=<?= $movie['id'] ?>&page=catalogue"></a>
                            <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                <div class="card-body movie-img" style="background: url('img/movies_img/<?= $movie['movie_picture'] ?>')">
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
        </section>        
    </main>
    <?php include 'php_assets/footer.php'?>
</body>
</html>