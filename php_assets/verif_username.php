<?php
    require_once("connectdb.php");
    require ("fonctions.php");

    $usernameReq = $conn->prepare('SELECT * FROM utilisateurs WHERE identifiant =:identifiant');
    $usernameReq->bindValue(':identifiant', nettoyage($_POST['username']));
    $usernameReq->execute();
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


?>