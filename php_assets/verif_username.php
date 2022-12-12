<?php
    require_once("connectdb.php");
    require ("fonctions.php");

    if(isset($_POST['username']) && $_POST['username'] != ""){
        $usernameReq = $conn->prepare('SELECT * FROM utilisateurs WHERE login = ?');
        $usernameReq->execute([
            nettoyage($_POST['username'])
        ]);
        $usernames = $usernameReq->fetchAll();

        if (count($usernames) === 0) {
            $response_array['status'] = 'success';
            echo json_encode($response_array);
            exit();
        }else{
            $response_array['status'] = 'usernameExiste';
            echo json_encode($response_array);
            exit();
        }
    }

?>