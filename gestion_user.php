<?php
require_once("php_assets/connectdb.php");
require ("php_assets/verif_session_connect.php");
require ("php_assets/fonctions.php");
?>

<!DOCTYPE html>
<html lang="fr">
<?php include 'php_assets/head.php'?>
<?php include 'php_assets/header.php'?>
<body>
<main>
<?php
    $userReq = $conn->prepare('SELECT * FROM utilisateurs');
    $userReq->execute();
    $users = $userReq->fetchAll();
?>
<div class="container">
<table class="table table-hover">
    <thead>
        <tr>
          <th>ID</th>
          <th>login</th>
          <th>email</th>
          <th>Admin (1= Oui, 0= Non)</th>
          <th>Suppression</th>
          <th>Modifier</th>
          <th>Upgrade</th>
          <th>Downgrade</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user){ ?>
        <tr>
          <td><?= $user['id']; ?></td>
          <td><?= $user['login']; ?></td>
           <td><?= $user['email']; ?></td>
           <td><?= $user['is_admin']; ?></td>
           <td><a href="php_assets/delete_user.php?id=<?= $user['id']; ?>"<><button>Supprimer</button></a></td>
           <td><a href="php_assets/modif_user.php?id=<?= $user['id']; ?>"<><button>Editer</button></a></td>
           <td><a href="php_assets/upgrade_user.php?id=<?= $user['id']; ?>"<><button>Mettre admin</button></a></td>
           <td><a href="php_assets/downgrade_user.php?id=<?= $user['id']; ?>"<><button>Retrograder</button></a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>  
</div>   
</main>
<?php include 'php_assets/footer.php'?>
</body>
</html>