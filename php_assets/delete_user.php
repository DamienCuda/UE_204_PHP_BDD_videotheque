<?php session_start();
    require_once("connectdb.php");
    require ("fonctions.php");

$pdoDe = $conn->prepare('SELECT FROM utilisateurs WHERE id= ?');
        $pdoDe->execute(array(nettoyage($_GET['id'])));
        header("Location: ../gestion_user.php");
?>
