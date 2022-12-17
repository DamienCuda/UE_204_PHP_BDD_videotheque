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
        <div id="loading">
            <div class="loader_search"></div>
        </div>
        <?php
        if (count($user_datas) != 0) {

            $user_id = $_GET['id'];

            foreach ($user_datas as $user_data) {
                //On récupère lmes données de l'utilsataur séparément
                $user_login = $user_data['login'];
                $user_email = $user_data['email'];
                $user_profil_pic = $user_data['profile_picture'];
                $user_rank = $user_data['rang'];
            }

            //Requête pour recupérer les films loués par l'utilisateur
            $rented_movies_req = $conn->prepare('
            SELECT DISTINCT catalogue.id, catalogue.movie_picture, catalogue.release_year, catalogue.title, catalogue.director
                FROM catalogue AS catalogue
                JOIN movies_location AS rented_movies
                ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = ? LIMIT 4');
            $rented_movies_req->execute(array(nettoyage($_GET['id'])));
            $user_rented_list = $rented_movies_req->fetchAll(PDO::FETCH_ASSOC);

            $count_movies_loc = $conn->prepare("SELECT count(*) as total FROM movies_location WHERE user_id = ? AND is_loc = ?");
            $count_movies_loc->execute([
                    nettoyage($_GET['id']),
                    1
            ]);
            $result_loc = $count_movies_loc->fetch();
            $total_movie_loc = $result_loc['total'];

            //Requête pour recupérer les films loués par l'utilisateur en ce moment
            $rented_movies_req = $conn->prepare('
                SELECT DISTINCT catalogue.id, catalogue.movie_picture, catalogue.release_year, catalogue.title, catalogue.director
                    FROM catalogue AS catalogue
                    JOIN movies_location AS rented_movies
                    ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = ? && rented_movies.is_loc = 1 LIMIT 4');
            $rented_movies_req->execute(array(nettoyage($_GET['id'])));
            $user_rented_list_now = $rented_movies_req->fetchAll(PDO::FETCH_ASSOC);

            $count_movies_his = $conn->prepare("SELECT count(*) as total FROM movies_location WHERE user_id = ?");
            $count_movies_his->execute([
                nettoyage($_GET['id'])
            ]);
            $result_his = $count_movies_his->fetch();
            $total_movie_his = $result_his['total'];
            ?>
            <!-- Section d'affichage des informations et modification utilisateur -->
            <section id="user_search" class="container">
                <div class="row">
                    <?php if(isset($_GET['id']) && $_GET['id'] != $_SESSION['id']){ ?>
                        <button class="btn btn-light btn-back d-flex align-items-center justify-content-between mt-5" id="back"><i class='bx bx-left-arrow-alt'></i><span>Retour</span></button>
                    <?php } ?>
                </div>
                <div class="row <?php if(isset($_GET['id']) && $_GET['id'] != $_SESSION['id']){ echo "mt-3"; }else{ echo "mt-5"; } ?>">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center" id="container-search-user">
                        <input type="text" name="search_user" id="search_user" placeholder="Rechercher un utilisateur" class="form-control">
                        <label id="title-search" class="text-light mb-3">Rechercher un utilisateur:</label>
                        <div id="result-search-user"></div>
                    </div>
                </div>
            </section>
            <section id="user_infos_container" class="container">
                <div class="row mt-5">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 text-center"></div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 text-light text-center text-sm-center text-md-center text-lg-start text-xl-start mt-2">
                        <?php if(isset($_GET['id']) && $_GET['id'] == $_SESSION['id']){ ?>
                        <h2>Bienvenue sur votre espace <span class="yellow-txt"><?= $user_login; ?></span></h2>
                        <?php }else{ ?>
                            <h2>Bienvenue sur l'espace de <span class="yellow-txt"><?= $user_login; ?></span></h2>
                        <?php } ?>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 text-center ">
                        <div id="profil_picture">
                            <!-- L'image affichée dans l'espace perso est différente si l'utilisatuer à défini un avatar ou non -->
                            <div id="edit_zone_img"></div>
                            <?php
                                if($user_profil_pic == null){
                                    ?>
                                    <img class="rounded-circle mb-3" src="img/profil_img/avatar.jpg" alt="Avatar"
                                         style="height:150px;object-fit: cover;">
                            <?php
                                }else{
                            ?>
                            <img class="rounded-circle mb-3" src="users/<?= $_GET['id']; ?>/avatar/<?= $user_profil_pic; ?>" alt="Avatar"
                                 style="height:150px;object-fit: cover;">
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 text-light text-center text-sm-center text-md-center text-lg-start text-xl-start">
                        <?php if(isset($_GET['id']) && $_GET['id'] == $_SESSION['id']){ ?>
                        <h3 class="mt-3 h3">Vos informations</h3>
                        <?php }else{ ?>
                        <h3 class="mt-3 h3">Informations</h3>
                        <?php } ?>
                        <p class="mb-2" id="email_line">Email: <span id="email_zone"><?= $user_email; ?></span></p>
                        <p class="mb-2 d-none" id="password_line">Mot de passe: <span id="password_zone"></span></p>
                        <p class="mb-2">Rang : <?= $user_rank; ?></p>
                        <p class="mb-2 d-flex align-items-center justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start"><a href="liste.php?id=<?= $_GET['id']; ?>" class="text-light"><i class='bx bx-plus mr-2'></i><span><?php if($_GET['id'] == $_SESSION['id']){ echo "Ma liste"; }else{ echo "Sa liste"; } ?></span></a></p>
                        <?php if(isset($_GET['id']) && $_GET['id'] == $_SESSION['id']){ ?>
                        <p class="mb-2 d-flex align-items-center justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start"><a href="user_transaction.php?user_id=<?= $_GET['id']; ?>" class="text-light"><i class='bx bx-euro'></i><span>Mes transactions</span></a></p>
                        <?php } ?>
                        <?php
                        if (isset($_GET['id']) && $_GET['id'] == $_SESSION['id']) {
                        ?>
                            <div class="d-flex mt-4 justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start">
                                <button class="btn btn-warning" id="btn_edit_profil">Modifier le profil</button>
                                <button class="btn btn-light d-none" id="btn_edit_profil_skip">Annuler les modifications</button>
                                <button class="btn btn-warning d-none ml-2" id="btn_edit_profil_valid">Modifier</button>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </section>
            <?php if (isset($_GET['id']) && $_GET['id'] == $_SESSION['id']) { ?>
            <section id="current_locations" class="container">
                <div class="row mb-5">
                    <h3 class="p-2 rounded yellow-bg d-flex align-items-center"><span class="mr-2">Locations en cours</span><i class='bx bx-cart-download'></i></h3>
                    <div class="row mt-5">
                        <?php
                        if (count($user_rented_list_now) > 0) {
                            foreach ($user_rented_list_now as $movie_isloc):

                                // On check la date de location.
                                $locationData = $conn->prepare('SELECT * FROM movies_location WHERE movie_id = ? AND user_id = ?');
                                $locationData->execute([
                                    $movie_isloc['id'],
                                    $_SESSION['id']
                                ]);
                                $location = $locationData->fetch();

                                $location_date = $location['date_location'];
                                $location_date = date("d/m/Y", strtotime($location_date));

                                $location_date_end = $location['date_location_end'];
                                ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start mb-4">
                                    <div class="card movie">
                                        <?php

                                        $now = new DateTime();
                                        $date_end = new DateTime( "$location_date_end" );
                                        $diff = $now->getTimestamp() - $date_end->getTimestamp();

                                            if($diff < 0)
                                            {
                                        ?>
                                        <div id="countdown_<?= $movie_isloc['id'] ?>" class="countdown d-flex align-items-center">
                                            <div class="days"></div>
                                            <div class="separator">J</div>
                                            <div class="hours"></div>
                                            <div class="separator">H :</div>
                                            <div class="minutes"></div>
                                            <div class="separator">m :</div>
                                            <div class="seconds"></div>
                                            <div class="separator">s</div>
                                            <i class='bx bx-timer ml-2 timer_icon'></i>
                                        </div>
                                        <script>
                                            function getTime(dateEnd) {
                                                let total = Date.parse(dateEnd) - Date.parse(new Date());
                                                let seconds = Math.floor((total / 1000) % 60);
                                                let minutes = Math.floor((total / 1000 / 60) % 60);
                                                let hours = Math.floor((total / (1000 * 60 * 60)) % 24);
                                                let days = Math.floor(total / (1000 * 60 * 60 * 24));

                                                return {
                                                    total,
                                                    days,
                                                    hours,
                                                    minutes,
                                                    seconds
                                                };
                                            }

                                            function Countdown(id, dateEnd) {
                                                let clock = document.getElementById(id);
                                                let daysZone = clock.querySelector('.days');
                                                let hoursZone = clock.querySelector('.hours');
                                                let minutesZone = clock.querySelector('.minutes');
                                                let secondsZone = clock.querySelector('.seconds');

                                                function updateTime() {
                                                    let t = getTime(dateEnd);

                                                    daysZone.innerHTML = t.days;
                                                    hoursZone.innerHTML = ('0' + t.hours).slice(-2);
                                                    minutesZone.innerHTML = ('0' + t.minutes).slice(-2);
                                                    secondsZone.innerHTML = ('0' + t.seconds).slice(-2);

                                                    if (t.total <= 0) {

                                                        let movie_id = <?= $movie_isloc['id'] ?>;

                                                        $.ajax({
                                                            url: "php_assets/update_movie_countdown.php?id=<?= $_GET['id'] ?>", // URL de la page
                                                            type: "POST", // GET ou POST
                                                            data: {
                                                                movie_id:movie_id
                                                            }, // Paramètres envoyés à php
                                                            dataType: "json", // Données en retour
                                                            success: function(reponse) {
                                                                console.log(reponse)

                                                                if(reponse.status == "success"){
                                                                    window.location.href = 'espace_perso_user.php?id=<?= $_GET['id'] ?>';
                                                                }
                                                            },
                                                            error:function(error){
                                                                console.log(error)
                                                            }
                                                        });

                                                        clearInterval(timeinterval);
                                                    }
                                                }

                                                updateTime();
                                                let timeinterval = setInterval(updateTime, 1000);
                                            }
                                            let dateEnd<?= $movie_isloc['id'] ?> = new Date(Date.parse(new Date("<?= $location_date_end; ?>")));
                                            Countdown('countdown_<?= $movie_isloc['id'] ?>', dateEnd<?= $movie_isloc['id'] ?>);
                                        </script>
                                        <?php
                                            }else{

                                                ?>
                                                <script>

                                                    let movie_id = <?= $movie_isloc['id'] ?>;

                                                    $.ajax({
                                                        url: "php_assets/update_movie_countdown.php?id=<?= $_GET['id'] ?>", // URL de la page
                                                        type: "POST", // GET ou POST
                                                        data: {
                                                            movie_id:movie_id
                                                        }, // Paramètres envoyés à php
                                                        dataType: "json", // Données en retour
                                                        success: function(reponse) {
                                                            console.log(reponse)

                                                            if(reponse.status == "success"){
                                                                window.location.href = 'espace_perso_user.php?id=<?= $_GET['id'] ?>';
                                                            }
                                                        },
                                                        error:function(error){
                                                            console.log(error)
                                                        }
                                                    });
                                                </script>
                                        <?php
                                            }
                                        ?>
                                        <a href="catalogue.php?movie=<?= $movie_isloc['id'] ?>">
                                            <div class="card-body movie-img"
                                                 style="background: url('img/movies_img/<?= $movie_isloc['movie_picture'] ?>')">
                                                <div id="release_year"><h5><?= $movie_isloc['release_year'] ?></h5>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer d-flex flex-column justify-content-between">
                                            <a href="catalogue.php?movie=<?= $movie_isloc['id'] ?>">
                                                <h4><?= $movie_isloc['title']; ?></h4>
                                            </a>
                                            <div class="d-flex flex-column">
                                                <small>De <?= $movie_isloc['director'] ?></small>
                                                <small>Loué le: <?= $location_date ?></small>
                                            </div>
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
                    <?php if($total_movie_loc > 4){ ?>
                        <a href="locations-list.php?id=<?= $_GET['id']; ?>"><div class="d-flex justify-content-end"><button class="btn btn-warning">Voir tout</button></div></a>
                    <?php } ?>
                </div>
            </section>
            <section id="past_locations" class="container">
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
                                    <a href="php_assets/add-bookmark.php?movie=<?= $movie['id'] ?>&page=catalogue"></a>
                                    <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                        <div class="card-body movie-img"
                                             style="background: url('img/movies_img/<?= $movie['movie_picture'] ?>')">
                                            <div id="release_year"><h5><?= $movie['release_year'] ?></h5></div>
                                        </div>
                                    </a>
                                    <div class="card-footer d-flex flex-column justify-content-between">
                                        <a href="catalogue.php?movie=<?= $movie['id'] ?>">
                                            <h4><?= $movie['title']; ?></h4>
                                        </a>
                                        <div class="d-flex flex-column">
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
                            </div>
                        <?php
                        endforeach;
                    } else {
                        echo "<h5 class='no-data'>Aucun film n'a été loué pour le moment...</h5>";
                    }
                    ?>
                    <?php if($total_movie_his > 4){ ?>
                        <a href="locations-historique.php?id=<?= $_GET['id']; ?>"><div class="d-flex justify-content-end"><button class="btn btn-warning">Voir tout</button></div></a>
                    <?php } ?>
                </div>
            </section>
            <?php } ?>
            <?php
        } else {
            echo "<h5 class='text-light justify-content-center align-items-center d-flex' id='notfound'>Erreur 404: Utilisateur introuvable !</h5>";
        }
        ?>
    </main>
    <?php include 'php_assets/footer.php' ?>
    <script src="js/edit_profil.js"></script>
    <script src="js/search_user.js"></script>
    <script src="js/back.js"></script>
    </body>
    </html>
    <?php
} else {
    header('location: espace_perso_user.php?id=' . $_SESSION['id'] . '');
}
?>