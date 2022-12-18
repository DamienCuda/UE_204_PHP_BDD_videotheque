<?php
require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

if($is_admin === false){
    header("location: index.php");
}

if (isset($_GET['id']) && $_GET['id'] != "") {

// On vérifie si l'utilistauer à un grade inférieur à nous
    $userData = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ?');
    $userData->execute([
        nettoyage($_GET['id'])
    ]);
    $user = $userData->fetch();

    if ($user['rang'] === "Membre") {
        $user_rang = 0;
    } else if ($user['rang'] === "Modérateur") {
        $user_rang = 1;
    } else if ($user['rang'] === "Administrateur") {
        $user_rang = 2;
    } else if ($user['rang'] === "Owner") {
        $user_rang = 3;
    }


    if ($permission > $user_rang) {

        $user_rang++;

        if ($user_rang === 1) {
            $rang = "Modérateur";
        } else if ($user_rang === 2) {
            $rang = "Administrateur";
        }

        $pdoEdit = $conn->prepare('UPDATE utilisateurs SET is_admin = 1, rang = ? WHERE id = ? LIMIT 1');
        $pdoEdit->execute([
            $rang,
            nettoyage($_GET['id'])
        ]);
    }
}

header("Location: ../gestion_user.php");

?>