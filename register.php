<?php 
require_once("connectdb.php"); 
$username = nettoyage($_POST["username"]);
$password = nettoyage($_POST["pass"]);
$confirmpass = nettoyage($_POST["passconfirm"]);

if ($username != "" && $password != "" && $confirmpass != "") {
    if ($password === $confirmpass) {
        if (strlen($password) >= 8) {
            $sql = "INSERT INTO `utilisateurs` ( `id`, `identifiant`, `motdepasse`, `is_admin`)
            VALUES( NULL, '$username', '$password', 0)";
            if ($conn->query($sql) === TRUE) {
                }
                $conn->close();
                header("Location: /cours/index.php");
            }
        } 
    } 

function nettoyage($valeur){ // Fonction de nettoyage
    $valeur = trim($valeur); // On enlève pour commencer les espace en trop.
    $valeur = stripslashes($valeur); // Ici on enlève les antislashes (\) qui sont des caractères d'échapements.
    $valeur = htmlspecialchars($valeur); // Et pour finir le pré-nettoyage on échape tout caractères comme les chevrons donc protège contre l'execution de code.
    return ($valeur);
}
?>