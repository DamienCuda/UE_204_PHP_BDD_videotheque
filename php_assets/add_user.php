<?php
require_once("connectdb.php");
require ("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

// On vérifie si les champs ne sont pas vide.
if (isset($_POST["username"]) && $_POST["username"] != "" && isset($_POST["email"]) && $_POST["email"] != "" && isset($_POST["pass"]) && $_POST["pass"] != "") {

    // On vérifie si l'utilistauer n'existe pas.
    $usernameReq = $conn->prepare('SELECT * FROM utilisateurs WHERE login = ?');
    $usernameReq->execute([
        nettoyage($_POST['username'])
    ]);
    $usernames = $usernameReq->fetchAll();

    // Si l'utilisateur n'existe pas on continue.
    if (count($usernames) === 0) {

        // On vérifie que l'email est correct.
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

            // On nettoie les variables pour éviter les injections.
            $username = strtolower(nettoyage($_POST["username"]));
            $email = strtolower(nettoyage($_POST["email"]));
            $password = nettoyage($_POST["pass"]);

            if($permission > $_POST["role"]){
                $rang = nettoyage($_POST["role"]);
            }else{
                // On retourne un status d'erreur pour ajax.
                $response_array['status'] = 'ErrorPerm';
                echo json_encode($response_array);
                exit();
            }

            if($rang > 0){
                $is_admin = 1;
            }else{
                $is_admin = 0;
            }

            if($rang == 0){
                $user_rang = "Membre";
            }else if($rang == 1){
                $user_rang = "Modérateur";
            }else if($rang == 2){
                $user_rang = "Administrateur";
            }

            // On vérifie que le mot de passe correspond à nos attentes.
            if (preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password)) {

                // On hash le mot de passe pour le stocker.
                $options = array("cost" => 4);
                $passwordHashed = password_hash($password, PASSWORD_DEFAULT, $options);

                // On insert les données dans la table utilisateurs.
                $insert = $conn->prepare('INSERT INTO utilisateurs (login, email, password, is_admin, rang) VALUES(?,?,?,?,?)');
                $insert->execute(array(
                    $username,
                    $email,
                    $passwordHashed,
                    $is_admin,
                    $user_rang
                ));

                // On détermine l'identifiant de l'utilisateur.
                $pdoID = $conn->prepare("SELECT MAX(id) AS max_id FROM utilisateurs");
                $pdoID->execute();
                $pdoID = $pdoID->fetch();
                $newID = $pdoID['max_id'] + 1;

                // On lui créer son repertoire.
                $userFolder = "../users/$newID";
                $avatarFolder = "../users/$newID/avatar";

                // On attribue les permission au dossier.
                if (!file_exists($userFolder)) {
                    mkdir($userFolder, 0777, true);
                    mkdir($avatarFolder, 0777, true);
                }

                // On retourne un status de succès pour ajax.
                $response_array['status'] = 'success';
                echo json_encode($response_array);
                exit();

            } else {
                // Le mot de passe ne correspond pas aux attentes fixé dans le regex on transmet l'infos à ajax.
                $response_array['status'] = 'passNotCorrect';
                echo json_encode($response_array);
                exit();
            }
        } else {
            // l'email est incorrect on transmet l'infos à ajax.
            $response_array['status'] = 'emailInvalid';
            echo json_encode($response_array);
            exit();

        }
    } else {
        // L'utilisateur existe déjà on transmet l'infos à ajax.
        $response_array['status'] = 'userExiste';
        echo json_encode($response_array);
        exit();
    }

} else {

    // L'utilisateur est vide on transmet l'infos à ajax.
    if ($_POST["username"] == "") {
        $response_array['status'] = 'usernameEmpty';
        echo json_encode($response_array);
        exit();
    }

    // L'email est vide on transmet l'infos à ajax.
    if ($_POST["email"] == "") {
        $response_array['status'] = 'emailEmpty';
        echo json_encode($response_array);
        exit();
    }

    // Le mot de passe est vide on transmet l'infos à ajax.
    if ($_POST["pass"] == "") {
        $response_array['status'] = 'passEmpty';
        echo json_encode($response_array);
        exit();
    }

    // Le role est vide on transmet l'infos à ajax.
    if ($_POST["role"] == "") {
        $response_array['status'] = 'roleEmpty';
        echo json_encode($response_array);
        exit();
    }

}

?>