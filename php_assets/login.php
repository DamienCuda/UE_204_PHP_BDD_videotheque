<?php
    session_start();
    require_once("connectdb.php");
    require ("fonctions.php");

    $username = nettoyage($_POST["username"]);
    $password = nettoyage($_POST["pass"]);

    $sql = "SELECT * FROM utilisateurs WHERE identifiant='$username' AND motdepasse='$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['identifiant'] === $username && $row['motdepasse'] === $password) {
            $_SESSION['identifiant'] = $row['identifiant'];
            $_SESSION['id'] = $row['id'];
            header("Location: ../index.php");
        }
    }
?>