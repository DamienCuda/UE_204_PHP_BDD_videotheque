<?php
require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);
    $query = $conn->prepare('SELECT * FROM catalogue WHERE title LIKE ? OR release_year LIKE ? OR genre LIKE ? OR synopsis LIKE ? OR director LIKE ? LIMIT 10');
    $query->execute(array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));


    while ($results = $query->fetch()) {

        // On verifie si le film est présent dans la table movies_bookmark.
        $moviesReq = $conn->prepare('SELECT * FROM movies_bookmark WHERE movie_id = ? AND user_id = ? LIMIT 1');
        $moviesReq->execute([
            $results['id'],
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
                <a href="php_assets/add-bookmark.php?movie=<?= $results['id'] ?>&page=catalogue">
                    <div class="bookmark">
                        <i class='bx <?php if ($is_bookmark) {
                            echo "bxs-bookmark";
                        } else {
                            echo "bx-bookmark";
                        } ?>'></i>
                    </div>
                </a>
                <a href="catalogue.php?movie=<?= $results['id'] ?>">
                    <div class="card-body movie-img"
                         style="background: url('img/movies_img/<?= $results['movie_picture'] ?>')">
                        <div id="release_year"><h5><?= $results['release_year'] ?></h5></div>
                    </div>
                </a>
                <div class="card-footer d-flex flex-column justify-content-between">
                    <a href="catalogue.php?movie=<?= $results['id'] ?>"><h4><?= $results['title']; ?></h4></a>
                    <small>De <?= $results['director'] ?></small>
                    <?php
                    $id_movie = $results['id'];

                    if($is_admin == 1 && $permission >= 1){

                        echo "<a href='edit-movie.php?movie=$id_movie'><button class='btn btn-warning mt-1 align-items-center justify-content-center d-flex' style='width:100%'><span>Modifier</span><i class='bx bx-edit-alt ml-2' ></i></button></a>";

                        // NOTE POUR Julien - Il faut modifier le lien delete-movie.php (pas edit-movie)
                        if($permission >= 2){
                            echo "<a href='delete-movie.php?id=$id_movie'><button class='btn btn-danger mt-1 align-items-center justify-content-center d-flex' style='width:100%'><span>Supprimer</span><i class='bx bx-trash ml-2'></i></button></a>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>


