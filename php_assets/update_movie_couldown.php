<?php
require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

if(isset($_POST['movie_id']) && $_POST['movie_id'] != ""){

    $update = $conn->prepare('UPDATE movies_location SET is_loc = ? WHERE movie_id = ? AND user_id = ?');
    $update->execute(array(
        0,
        nettoyage($_POST['movie_id']),
        nettoyage($_GET['id'])
    ));

    $response_array['status'] = 'success';
    echo json_encode($response_array);
    exit();

}

?>