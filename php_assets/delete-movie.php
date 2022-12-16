<?php

require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

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
