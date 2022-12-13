<?php

require_once("connectdb.php");
require ("fonctions.php");


if(isset($_POST['username']) && $_POST['username'] != "" && isset($_POST['id']) && $_POST['id'] != ""){
    $usernameReq = $conn->prepare('SELECT * FROM utilisateurs WHERE login = ? AND id != ?');
    $usernameReq->execute([
        nettoyage($_POST['username']),
        nettoyage($_POST['id'])
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
}else{
    $response_array['status'] = 'error';
    echo json_encode($response_array);
    exit();
}



?>