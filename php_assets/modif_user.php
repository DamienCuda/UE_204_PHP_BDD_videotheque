<?php session_start();
    require_once("connectdb.php");
    require ("fonctions.php");

$pdoDe = $conn->prepare('UPDATE utilisateurs SET login= ?, SET email= ? WHERE id= ?');
$pdoDe->execute([
nettoyage($_GET['login']),
nettoyage($_GET['email']),
nettoyage($_GET['id']),
]);
header("Location: ../gestion_user.php");
?>