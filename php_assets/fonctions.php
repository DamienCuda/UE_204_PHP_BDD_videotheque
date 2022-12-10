<?php

require ("verif_session_connect.php");

// Fonction de nettoyage
function nettoyage($valeur){
    $valeur = trim($valeur); // On enlève pour commencer les espace en trop.
    $valeur = stripslashes($valeur); // Ici on enlève les antislashes (\) qui sont des caractères d'échapements.
    $valeur = htmlspecialchars($valeur); // Et pour finir le pré-nettoyage on échape tout caractères comme les chevrons donc protège contre l'execution de code.
    return ($valeur);
}


?>