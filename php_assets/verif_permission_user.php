<?php
require_once("connectdb.php");
require ("verif_session_connect.php");
require ("fonctions.php");
require ("permission.php");

if($is_admin > 0 && $permission > 0){
    if($permission === 1){

        $response_array['status'] = 'Modérateur';
        echo json_encode($response_array);
        exit();

    }else if($permission === 2){

        $response_array['status'] = 'Administrateur';
        echo json_encode($response_array);
        exit();

    }else if($permission === 3){

        $response_array['status'] = 'Owner';
        echo json_encode($response_array);
        exit();

    }else{
        $response_array['status'] = 'error';
        echo json_encode($response_array);
        exit();
    }
}else{
    $response_array['status'] = 'error';
    echo json_encode($response_array);
    exit();
}

?>