<?php 
    require_once("connectdb.php");
    require ("fonctions.php");

    // On vérifie si les champs ne sont pas vide.
    if (isset($_POST["username"]) && $_POST["username"] != "" && isset($_POST["email"]) && $_POST["email"] != "" && isset($_POST["pass"]) && $_POST["pass"] != "" && isset($_POST["passconfirm"]) && $_POST["passconfirm"] != "") {

        // On vérifie si l'utilistauer n'existe pas.
        $usernameReq = $conn->prepare('SELECT * FROM utilisateurs WHERE identifiant =:identifiant');
        $usernameReq->bindValue(':identifiant', nettoyage($_POST['username']));
        $usernameReq->execute();
        $usernames = $usernameReq->fetchAll();

        // Si l'utilistauer n'existe pas on continue.
        if (count($usernames) === 0) {

            // On vérifie que l'email est correct.
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                // On nettoie les variables pour éviter les injections.
                $username = strtolower(nettoyage($_POST["username"]));
                $email = strtolower(nettoyage($_POST["email"]));
                $password = nettoyage($_POST["pass"]);
                $confirmpass = nettoyage($_POST["passconfirm"]);

                // On vérifie que le mot de passe correspond à nos attentes.
                if (preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password)) {

                    // On vérifie que les mots de passes sont identiques.
                    if ($password == $confirmpass) {

                        // On hash le mot de passe pour le stocker.
                        $options = array("cost"=>4);
                        $passwordHashed = password_hash($password, PASSWORD_DEFAULT, $options);

                        // On insert les données dans la table utilisateurs.
                        $insert = $conn->prepare('INSERT INTO utilisateurs (identifiant, email, password) VALUES(?,?,?)');
                        $insert->execute(array(
                            $username,
                            $email,
                            $passwordHashed
                        ));

                        // On retourne un status de succès pour ajax.
                        $response_array['status'] = 'success';
                        echo json_encode($response_array);
                        exit();

                    } else {

                        // Les mots de passes ne correspondent pas on transmet l'infos à ajax.
                        $response_array['status'] = 'passNotSame';
                        echo json_encode($response_array);
                        exit();
                    }
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

?>