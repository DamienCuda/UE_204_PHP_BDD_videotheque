<?php
    require_once("connectdb.php");
    require ("fonctions.php");

    // On vérifie si les champs ne sont pas vide
    if(isset($_POST['username']) && $_POST['username'] != "" && isset($_POST['pass']) && $_POST['pass'] != ""){

        // On nettoie les variables pour éviter les injections
        $username = nettoyage($_POST["username"]);
        $password = nettoyage($_POST["pass"]);

        // On récupère les donnée des utilisateurs.
        $userReq = $conn->prepare('SELECT * FROM utilisateurs WHERE login =:login LIMIT 1');
        $userReq->bindValue(':login', $username);
        $userReq->execute();
        $user = $userReq->fetchAll();

        // On vérifie si l'utiilisateur existe.
        if(count($user) > 0){

            // On vérifie si le mot de passe entré correspond au hash du mot de passe stocker dans la BDD.
            if (password_verify($password, $user[0]['password'])) {

                // On stocker les infos utile pour les pages dans les variables de sessions.
                $_SESSION['login'] = $user[0]['login'];
                $_SESSION['id'] = $user[0]['id'];

                if($user[0]['is_admin'] == 0){
                    $_SESSION['is_admin'] = false;
                }else{
                    $_SESSION['is_admin'] = true;
                }

                // On retourne un status de succès pour ajax.
                $response_array['status'] = 'success';
                echo json_encode($response_array);
                exit();

            }else{

                // Le mot de passe est incorrect on transmet l'infos à ajax.
                $response_array['status'] = 'passNotCorrect';
                echo json_encode($response_array);
                exit();
            }
        }else{
            // L'utilisateur est incorrect on transmet l'infos à ajax.
            $response_array['status'] = 'usernameNotCorrect';
            echo json_encode($response_array);
            exit();
        }

    }else{

        // L'utilisateur est vide on transmet l'infos à ajax.
        if ($_POST["username"] == "") {
            $response_array['status'] = 'usernameEmpty';
            echo json_encode($response_array);
            exit();
        }

        // Le mot de passe est vide on transmet l'infos à ajax.
        if ($_POST["pass"] == "") {
            $response_array['status'] = 'passEmpty';
            echo json_encode($response_array);
            exit();
        }
    }


?>