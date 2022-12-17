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
                    ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = ? && rented_movies.is_loc = 1');
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
                header("location: locations-list.php?id=$id_user&page=1");
            }

            //Requête pour recupérer les films loués par l'utilisateur
            $rented_movies_req = $conn->prepare('
                SELECT DISTINCT catalogue.id, catalogue.movie_picture, catalogue.release_year, catalogue.title, catalogue.director
                    FROM catalogue AS catalogue
                    JOIN movies_location AS rented_movies
                    ON catalogue.id = rented_movies.movie_id && rented_movies.user_id = :id && rented_movies.is_loc = 1 LIMIT :premier, :parpage;');
            $rented_movies_req->bindValue(':id', nettoyage($_GET['id']));
            $rented_movies_req->bindValue(':premier', $premier, PDO::PARAM_INT);
            $rented_movies_req->bindValue(':parpage', $parPage, PDO::PARAM_INT);
            $rented_movies_req->execute();
            $user_rented_list_now = $rented_movies_req->fetchAll(PDO::FETCH_ASSOC);

            ?>
            <section id="current_locations" class="container">
                <div class="row mb-5 mt-4">
                    <a href="espace_perso_user.php?id=<?= $_GET['id']; ?>" class="p-0 mb-4"><div class="d-flex justify-content-start"><button class="btn btn-light d-flex align-items-center"><i class='bx bx-arrow-back'></i><span class="ml-2">Retour</span></button></div></a>
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
                                                                url: "php_assets/update_movie_ountdown.php?id=<?= $_GET['id'] ?>", // URL de la page
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
                                                            window.location.href = 'locations-list.php?id=<?= $_GET['id'] ?>';
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
                                        <div class="card-footer">
                                            <a href="catalogue.php?movie=<?= $movie_isloc['id'] ?>">
                                                <h3><?= $movie_isloc['title']; ?></h3></a>
                                            <small>De <?= $movie_isloc['director'] ?></small>
                                            <small>Loué le: <?= $location_date ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                        } else {
                            echo "<h5 class='no-data'>Aucun film n'est loué actuellement...</h5>";
                        }
                        ?>
                        <?php
                        if ($nbMovies >= $parPage) {
                            ?>
                            <ul class="pagination mt-5 d-flex justify-content-end">
                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                    <a href="locations-list.php?id=<?= $_GET['id']; ?>" class="page-link">Précédente</a>
                                </li>
                                <?php for ($page = 1; $page <= $pages; $page++): ?>
                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                        <a href="locations-list.php?id=<?= $_GET['id']; ?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                    </li>
                                <?php endfor ?>
                                <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                    <a href="locations-list.php?id=<?= $_GET['id']; ?>&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
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