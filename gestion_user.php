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
        <section id="gestion_user" class="mt-5">
            <table class="table table-striped" id="gestion_user_table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo de profil</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $user){ ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td>
                            <div class="profil-membre" style="background: url('img/profil_img/<?= $user['profile_picture']; ?>');"></div>
                        </td>
                        <td><?= $user['login']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td>
                            <?php
                            if($user['is_admin'] == 1){
                                echo "Administrateur";
                            }else{
                                echo "Membre";
                            }
                            ?>
                        </td>
                        <td>
                            <div class="d-flex justify-content-between">
                                <a href="php_assets/modif_user.php?id=<?= $user['id']; ?>" title="Modifier"><i class='bx bx-edit-alt'></i></a>
                                <?php
                                if($user['is_admin'] == 1){
                                    ?>
                                    <a href="php_assets/downgrade_user.php?id=<?= $user['id']; ?>" title="Destituer"><i class='bx bx-chevrons-down'></i></a>
                                    <?php
                                }else{
                                    ?>
                                    <a href="php_assets/upgrade_user.php?id=<?= $user['id']; ?>" title="Promouvoir"><i class='bx bx-chevrons-up'></i></a>
                                    <?php
                                }
                                ?>
                                <a href="php_assets/delete_user.php?id=<?= $user['id']; ?>" title="Supprimer"><i class='bx bx-trash'></i></a>

                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </section>
    </div>
</main>
<?php include 'php_assets/footer.php'?>
<script>
    $(document).ready(function () {
        $('#gestion_user_table').DataTable({
            language: {
                url: 'app-assets/dataTables.french.json'
            }
        });
    });
</script>
</body>
</html>