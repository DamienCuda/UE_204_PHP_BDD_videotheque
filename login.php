<?php
session_start();
require_once("connectdb.php"); 

$username = nettoyage($_POST["username"]);
$password = nettoyage($_POST["pass"]);
$sql = "SELECT * FROM utilisateurs WHERE identifiant='$username' AND motdepasse='$password'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    if ($row['identifiant'] === $username && $row['motdepasse'] === $password) {
        $_SESSION['identifiant'] = $row['identifiant'];
        $_SESSION['id'] = $row['id'];
        header("Location: index.php");
    }
}

function nettoyage($valeur){ // Fonction de nettoyage
    $valeur = trim($valeur); // On enl�ve pour commencer les espace en trop.
    $valeur = stripslashes($valeur); // Ici on enl�ve les antislashes (\) qui sont des caract�res d'�chapements.
    $valeur = htmlspecialchars($valeur); // Et pour finir le pr�-nettoyage on �chape tout caract�res comme les chevrons donc prot�ge contre l'execution de code.
    return ($valeur);
}

?>