<?php

require_once("connectdb.php");
require("verif_session_connect.php");
require("fonctions.php");
require("permission.php");

if(isset($_POST['id']) && $_POST['id'] != ""){

    $id_not_delete = [];

    // On vérifie si l'utilisateur est bien administrateur.
    if($permission > 1){
        for ($i = 0; $i < count($_POST['id']); $i++){

            // On vérifie le rang de l'utilisateur que l'on souhaite supprimer.
            $userData = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ?');
            $userData->execute([
                nettoyage($_POST['id'][$i])
            ]);
            $user = $userData->fetch();

            if($user['rang'] == "Membre"){
                $rang = 0;
            }else if($user['rang'] == "Modérateur") {
                $rang = 1;
            }else if($user['rang'] == "Administrateur"){
                $rang = 2;
            }else if($user['rang'] == "Owner"){
                $rang = 3;
            }else{
                $rang = 0;
            }

            if($rang < 3 && $permission > $rang){
                $pdoDe = $conn->prepare('DELETE FROM utilisateurs WHERE id = ? LIMIT 1');
                $pdoDe->execute([
                    nettoyage($_POST['id'][$i])
                ]);
            }else{
                array_push($id_not_delete, $_POST['id'][$i]);

            }
        }
        if(count($id_not_delete) === 0){
            $response_array['status'] = 'success';
            echo json_encode($response_array);
            exit();
        }else{
            $response_array['status'] = 'permissionError';
            echo json_encode($response_array);
            exit();
        }

    }
}

?>