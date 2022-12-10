<?php session_start();
    require_once("connectdb.php");
    require ("fonctions.php");

$pdoDe = $conn->prepare('UPDATE utilisateurs SET is_admin=1 WHERE id= ? LIMIT 1');
$pdoDe->execute([
nettoyage($_GET['id'])
]);
header("Location: ../gestion_user.php");
?>