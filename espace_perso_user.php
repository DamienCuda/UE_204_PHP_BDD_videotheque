<?php
    require_once("php_assets/connectdb.php");
    require ("php_assets/verif_session_connect.php");
    require ("php_assets/fonctions.php");

    //On prépare la requête pour récupérer les infos de l'utilisatuer en connecté
    $userinfoReq = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ?');

    //On l'exécute avec le SESSION['id'] et on passe le tout dans une variable user_data
    $userinfoReq->execute(array($_SESSION['id']));
    $user_data = $userinfoReq->fetch(PDO::FETCH_ASSOC);

    $user_login = $user_data['login'];
    $user_email = $user_data['email'];
    $user_profil_pic = $user_data['profile_picture'];

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
                    <p>Solde : </p>
                </div>
            </div>
            <div class="row mt-5">
                <h3 class="p-2 rounded yellow-bg">Location en cours</h3>
                <div>

                </div>
            </div>
            <div class="row mt-5">
                <h3 class="p-2 rounded yellow-bg">Historique de location</h3>
                <div>

                </div>
            </div>
        </section>        
    </main>
    <?php include 'php_assets/footer.php'?>
</body>
</html>