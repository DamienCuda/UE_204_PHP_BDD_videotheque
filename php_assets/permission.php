<?php
    // On récupère les infos de l'utilisateur
    $usersData = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ? LIMIT 1');
    $usersData->execute([
            $_SESSION['id']
        ]);
    $user = $usersData->fetch();

    if($user['is_admin'] > 0){

        $is_admin = 1;

        if($user['rang'] === "Owner"){
            $permission = 3;
        }else if($user['rang'] === "Administrateur"){
            $permission = 2;
        }else if($user['rang'] === "Modérateur"){
            $permission = 1;
        }else{
            $permission = 0;
        }
    }else{
        $is_admin = 0;
        $permission = 0;
    }
?>
