<?php
require_once("connectdb.php");
require ("verif_session_connect.php");
require ("fonctions.php");
require ("permission.php");


if($is_admin > 0 && $permission >= 0){

// On vérifie si les champs ne sont pas vide.
    if (isset($_POST["username"]) && $_POST["username"] != "" && isset($_POST["email"]) && $_POST["email"] != "" && isset($_GET["id"]) && $_GET["id"] != "") {

        $id = nettoyage($_GET['id']);

        // On vérifie si l'utilistauer n'existe pas.
        $usernameReq = $conn->prepare('SELECT * FROM utilisateurs WHERE login = ? AND id != ?');
        $usernameReq->execute([
            nettoyage($_POST["username"]),
            $id
        ]);
        $usernames = $usernameReq->fetchAll();

        // Si l'utilistauer n'existe pas on continue.
        if (count($usernames) === 0) {

            // On vérifie que l'email est correct.
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                // On nettoie les variables pour éviter les injections.
                $username = strtolower(nettoyage($_POST["username"]));
                $email = strtolower(nettoyage($_POST["email"]));

                // On vérifie si un changement est détecté si c'est le cas on update le champs.
                $userDataReq = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ?');
                $userDataReq->execute([
                    $id
                ]);
                $userData = $userDataReq->fetch();

                if($userData['login'] != $username){
                    $editUser = $conn->prepare('UPDATE utilisateurs set login = ? WHERE id= ?');
                    $editUser->execute([
                        $username,
                        $id
                    ]);

                    // On retourne un status de succès pour ajax.
                    $response_array['status'] = 'success';
                    echo json_encode($response_array);
                    exit();
                }

                if($userData['email'] != $email){
                    $editUser = $conn->prepare('UPDATE utilisateurs set email = ? WHERE id= ?');
                    $editUser->execute([
                        $email,
                        $id
                    ]);

                    // On retourne un status de succès pour ajax.
                    $response_array['status'] = 'success';
                    echo json_encode($response_array);
                    exit();
                }

                if(isset($_POST["pass"]) && $_POST["pass"] != ""){

                    $password = nettoyage($_POST["pass"]);
                    if(password_verify($password, $userData['password']) != 1) {

                        // On vérifie que le mot de passe correspond à nos attentes.
                        if (preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password)) {

                            // On hash le mot de passe pour le stocker.
                            $options = array("cost"=>4);
                            $passwordHashed = password_hash($password, PASSWORD_DEFAULT, $options);

                            $editUser = $conn->prepare('UPDATE utilisateurs set password = ? WHERE id= ?');
                            $editUser->execute([
                                $passwordHashed,
                                $id
                            ]);

                            // On retourne un status de succès pour ajax.
                            $response_array['status'] = 'success';
                            echo json_encode($response_array);
                            exit();

                        }else{
                            // Le mot de passe ne correspond pas aux attentes fixé dans le regex on transmet l'infos à ajax.
                            $response_array['status'] = 'passNotCorrect';
                            echo json_encode($response_array);
                            exit();
                        }
                    }
                }

            } else {
                // l'email est incorrect on transmet l'infos à ajax.
                $response_array['status'] = 'emailInvalid';
                echo json_encode($response_array);
                exit();

            }
        }else{
            // L'utilisateur existe déjà on transmet l'infos à ajax.
            $response_array['status'] = 'userExiste';
            echo json_encode($response_array);
            exit();
        }

    }else {

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

        // Le mot de passe de confirmation est vide on transmet l'infos à ajax.
        if ($_POST["passconfirm"] == "") {
            $response_array['status'] = 'passConfEmpty';
            echo json_encode($response_array);
            exit();
        }

    }
}

?>