<?php
require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

// On récupère les donnée des utilisateurs.
$datasSQL = $conn->prepare("SELECT * FROM utilisateurs WHERE id=:id");
$datasSQL->bindValue(':id', $_SESSION['id']);
$datasSQL->execute();
$datas = $datasSQL->fetchAll();

foreach ($datas as $data) {
    $login = $data['login'];
    $lastEmail = $data['email'];
    $avatarData = $data['profile_picture'];
    $lastPassword = $data['password'];
}

if (isset($_POST["email"]) && $_POST["email"] != "") {

    // On vérifie que l'email est correct.
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

        $email = nettoyage($_POST["email"]);

        if ($email != $lastEmail) {
            $update = $conn->prepare('UPDATE utilisateurs SET email = ? WHERE id = ?');
            $update->execute(array(
                $email,
                htmlspecialchars($_SESSION['id'])
            ));

            $array = array("status" => "success");
            echo json_encode($array);
            exit();
        }

    } else {
        // l'email est incorrect on transmet l'infos à ajax.
        $response_array['status'] = 'emailInvalid';
        echo json_encode($response_array);
        exit();
    }
}

if (isset($_POST["password"]) && $_POST["password"]) {
    $password = nettoyage($_POST["password"]);

    // On vérifie que le mot de passe correspond à nos attentes.
    if (preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password)) {

        // On hash le mot de passe pour le stocker.
        $options = array("cost" => 4);
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT, $options);

        // On vérifie que le nouveau mot de passe est bien différent de l'ancien.
        if (!password_verify($password, $lastPassword)) {
            $update = $conn->prepare('UPDATE utilisateurs SET password = ? WHERE id = ?');
            $update->execute(array(
                $passwordHashed,
                htmlspecialchars($_SESSION['id'])
            ));

            $array = array("status" => "success");
            echo json_encode($array);
            exit();
        }
    } else {
        // Le mot de passe ne correspond pas aux attentes fixé dans le regex on transmet l'infos à ajax.
        $response_array['status'] = 'passInvalid';
        echo json_encode($response_array);
        exit();
    }
}


if(isset($_FILES['avatar']['tmp_name']) && $_FILES['avatar']['tmp_name'] != "") {

    $id = $_SESSION['id'];

    if ($avatarData != "") {
        $lastAvatar = "../users/$id/avatar/$avatarData";
        if (file_exists($lastAvatar)) {
            unlink($lastAvatar);
        }
    }

    $taille_maxi = 2000000;
    $taille = filesize($_FILES['avatar']['tmp_name']);
    $extensions = array('.png', '.jpg', '.jpeg');
    $extension = strrchr($_FILES['avatar']['name'], '.');

    if (!in_array($extension, $extensions)) {
        $error = 'Vous devez uploader un fichier de type png, jpg ou jpeg';
        $array = array("status" => "errorIMG");
        echo json_encode($array);
        exit();
    }
    if ($taille > $taille_maxi) {
        $error = 'Le fichier est supérieur à 2 Mo';
        $array = array("status" => "errorIMG");
        echo json_encode($array);
        exit();
    }

    if (!isset($error)) {
        $name = $id . '_' . $login . '_' . $_FILES['avatar']['name'] . '';
        $avatar = $name;

        $tmpName = htmlspecialchars($_FILES['avatar']['tmp_name']);
        $path = "../users/$id/avatar/" . $avatar;
        $resultat = move_uploaded_file($tmpName, $path);

        $update = $conn->prepare('UPDATE utilisateurs SET profile_picture = ? WHERE id = ?');
        $update->execute(array(
            htmlspecialchars($avatar),
            htmlspecialchars($_SESSION['id'])
        ));

        $array = array("status" => "success");
        echo json_encode($array);
        exit();
    } else {
        $array = array("status" => "errorIMG");
        echo json_encode($array);
        exit();
    }

}


?>