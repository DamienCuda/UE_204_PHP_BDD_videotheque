<?php
require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);
    $query = $conn->prepare('SELECT * FROM utilisateurs WHERE login LIKE ? OR email LIKE ? OR rang LIKE ? LIMIT 10');
    $query->execute(array("%$search%", "%$search%", "%$search%"));


    while ($results = $query->fetch()) {
        ?>
        <div class="col-12 d-flex justify-content-start align-items-center mb-3 flex-row">
            <a href="espace_perso_user.php?id=<?= $results['id']; ?>">
                <?php if($results['profile_picture'] == null){ ?>
                    <img class="rounded-circle" src="img/profil_img/avatar.jpg" alt="Avatar" style="height:50px;object-fit: cover;">
                <?php }else{ ?>
                <img class="rounded-circle" src="users/<?= $results['id']; ?>/avatar/<?= $results['profile_picture']; ?>" alt="Avatar" style="height:50px;object-fit: cover;">
                <?php } ?>
                <h5 class="ml-5 d-flex align-items-center"><?= $results['login']; ?> [<?= $results['rang']; ?>]</h5>
            </a>
        </div>
        <?php
    }
}
?>


