<?php 
    require_once("connectdb.php");
    require ("fonctions.php");

    if (isset($_POST["username"]) && $_POST["username"] != "" && isset($_POST["pass"]) && $_POST["pass"] != "" && isset($_POST["passconfirm"]) && $_POST["passconfirm"] != "") {

        $usernameReq = $conn->prepare('SELECT * FROM utilisateurs WHERE identifiant =:identifiant');
        $usernameReq->bindValue(':identifiant', nettoyage($_POST['username']));
        $usernameReq->execute();
        $usernames = $usernameReq->fetchAll();

        if (count($usernames) === 0) {
            $username = strtolower($_POST["username"]);
            $password = nettoyage($_POST["pass"]);
            $confirmpass = nettoyage($_POST["passconfirm"]);

            if (preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password)) {

                if ($password == $confirmpass) {

                    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

                    $insert = $conn->prepare('INSERT INTO utilisateurs (identifiant, motdepasse) VALUES(?,?)');
                    $insert->execute(array(
                        htmlspecialchars(strtolower($username)),
                        htmlspecialchars(strtolower($passwordHashed))
                    ));

                    $response_array['status'] = 'success';
                    echo json_encode($response_array);
                    exit();

                } else {
                    $response_array['status'] = 'passNotSame';
                    echo json_encode($response_array);
                    exit();
                }
            } else {
                $response_array['status'] = 'passNotCorrect';
                echo json_encode($response_array);
                exit();
            }
        }else {
            $response_array['status'] = 'userExiste';
            echo json_encode($response_array);
            exit();
        }
    }else {

        if ($_POST["username"] == "") {
            $response_array['status'] = 'usernameEmpty';
            echo json_encode($response_array);
            exit();
        }

        if ($_POST["pass"] == "") {
            $response_array['status'] = 'passEmpty';
            echo json_encode($response_array);
            exit();
        }

        if ($_POST["passconfirm"] == "") {
            $response_array['status'] = 'passConfEmpty';
            echo json_encode($response_array);
            exit();
        }

    }

?>