<?php 
    require_once("connectdb.php");
    require ("fonctions.php");

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
                    header("Location: ../index.php");
                }
            } 
        } 
?>