<?php

require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

if($is_admin === false && $permission < 2){
    header("location: index.php");
}

// On vérifie si l'id n'est pas vide.
if(isset($_GET['id']) && $_GET['id'] != ""){

    // On vérifie si l'utilisateur est bien administrateur.
    if($permission > 1){
        $pdoDe = $conn->prepare('DELETE FROM catalogue WHERE id = ? LIMIT 1');
        $pdoDe->execute([
            nettoyage($_GET['id'])
        ]);
    }

}

header("Location: ../catalogue.php");
?>
