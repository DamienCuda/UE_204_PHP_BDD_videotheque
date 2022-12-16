<?php
require_once("php_assets/connectdb.php");
require ("php_assets/verif_session_connect.php");
require ("php_assets/fonctions.php");

if($is_admin === false){
    header("location: index.php");
}

?>

<!DOCTYPE html>
<html lang="fr">
<?php include 'php_assets/head.php'?>
<?php include 'php_assets/header.php'?>
<body>
<style>

</style>
<main>
    <?php
        $userReq = $conn->prepare('SELECT * FROM utilisateurs');
        $userReq->execute();
        $users = $userReq->fetchAll();
    ?>
    <div class="container">
        <section id="gestion_user" class="mt-5">
            <table class="table table-striped responsive nowrap" id="gestion_user_table">
                <thead>
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center">ID</th>
                    <th class="text-center">Photo de profil</th>
                    <th class="text-center">Utilisateur</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Mot de passe</th>
                    <th class="text-center">Rôle</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $user){

                    // ON récupère le rang des utilisateurs
                    if($user['rang'] == "Owner"){
                        $rang = 3;
                    }else if($user['rang'] == "Administrateur"){
                        $rang = 2;
                    }else if($user['rang'] == "Modérateur"){
                        $rang = 1;
                    }else if($user['rang'] == "Membre"){
                        $rang = 0;
                    }else{
                        $rang = 0;
                    }

                    ?>
                    <tr class="<?php if($user['id'] == $_SESSION['id']){ echo "you"; } ?>">
                        <td id="<?= $user['id']; ?>" class="checkbox_table"></td>
                        <td><?= $user['id']; ?></td>
                        <td>
                            <div class="profil-membre" style="background: url('img/profil_img/<?= $user['profile_picture']; ?>');"></div>
                        </td>
                        <td><?= $user['login']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td>********</td>
                        <td>
                            <?php
                                echo $user['rang'];
                            ?>
                        </td>
                        <td>
                            <div class="d-flex mt-2 mt-sm-2 mt-md-2 mt-lg-0 mt-xl-0 justify-content-around justify-content-sm-around justify-content-md-around justify-content-lg-around justify-content-xl-around">   
                                <a href="user_transaction.php?user_id=<?= $user['id']; ?>"><i class='bx bx-money-withdraw'></i></a>
                                <?php
                                    // Si notre permission est supérieur au rang de l'utilisateur on à accès la modification, destitution et promotion de l'utilisateur.
                                    if($permission > $rang){
                                        ?>
                                        <button class="edit_btn" title="Modifier"><i class='bx bx-edit-alt'></i></button>
                                        <?php
                                            // Si l'utilisateur est membre on ne peut pas le destituer car y a plus rien en dessous.
                                            if($rang > 0){
                                                ?>
                                                    <a href="php_assets/downgrade_user.php?id=<?= $user['id']; ?>" title="Destituer"><i class='bx bx-chevrons-down'></i></a>
                                                <?php
                                            }
                                            if($rang + 1 < $permission){
                                        ?>
                                        <a href="php_assets/upgrade_user.php?id=<?= $user['id']; ?>" title="Promouvoir"><i class='bx bx-chevrons-up'></i></a>
                                        <?php
                                            }
                                    }
                                    if($permission >= 2 && $rang < 3 && $permission > $rang){
                                        // Si notre permission est superieur à 2 on a le pouvoir de supprimer un membre.
                                        ?>
                                        <a href="php_assets/delete_user.php?id=<?= $user['id']; ?>" title="Supprimer"><i class='bx bx-trash'></i></a>
                                <?php
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr id="add_user_row">

                        <?php
                            $pdoID = $conn->prepare("SELECT MAX(id) AS max_id FROM utilisateurs");
                            $pdoID->execute();
                            $pdoID = $pdoID->fetch();
                            $max_incrementation = $pdoID['max_id'] + 1;
                        ?>

                        <td></td>
                        <td><?= $max_incrementation ?></td>
                        <td><div class="profil-membre" style="background: url('img/profil_img/avatar.jpg');"></div></td>
                        <td style="height: 60px">
                            <input type="text" name="username_add" id="username_add" class="form-control" placeholder="Nom d'utilisateur...">
                        </td>
                        <td>
                            <input type="email" name="email_add" id="email_add" class="form-control" placeholder="Adresse email...">
                        </td>
                        <td>
                            <input type="password" name="pass_add" id="pass_add" class="form-control" placeholder="Mot de passe...">
                        </td>
                        <td>
                            <?php if($permission > 1){?>
                            <select name="role" id="role_add">
                                <?php if($permission === 1){ ?>
                                <option value="0">Membre</option>
                                <?php }else if($permission === 2){ ?>
                                    <option value="0">Membre</option>
                                    <option value="1">Modérateur</option>
                                <?php }else if($permission === 3){ ?>
                                    <option value="0">Membre</option>
                                    <option value="1">Modérateur</option>
                                    <option value="2">Administrateur</option>
                                <?php } ?>
                            </select>
                            <?php } ?>
                        </td>
                        <td><button class="btn btn-warning" style="background: #ffca2c !important;" id="add_user_btn">Ajouter</button></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>
</main>

<!-- Modal permissionError -->
<div class="modal fade" id="permissionError" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel"><span style="margin-right: 10px;">Attention !</span> <i class='bx bx-error' ></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Vous avez séléctionné des utilisateurs sur lesquels vous n'avez pas la permission d'intéragir. Seuls les utilisateurs dont vous avez l'autorité seront pris en compte.</p>
            </div>
            <div class="modal-footer">
                <a href="gestion_user.php">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="valid_permissionError">J'AI COMPRIS</button>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel"><span style="margin-right: 10px;">Félicitation !</span> <i class='bx bx-party'></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>L'utilisateur a été ajouté avec succès.</p>
            </div>
            <div class="modal-footer">
                <a href="gestion_user.php">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">CONTINUER</button>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'php_assets/footer.php'?>

<script>
    $(document).ready(function () {
        $('#gestion_user_table').DataTable({
            language: {
                url: 'app-assets/dataTables.french.json'
            },
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0
            } ],
            select: {
                style:    'multi',
                'selector': 'td:first-child'
            },
            responsive: true,
            order: [[ 1, 'asc' ]]
        });

        // Ajout du bouton "options" à coté de la barre de recherche.
        let options =
            '<div class="action-dropdown-btn">' +
                '<div class="dropdown invoice-options">' +
                    '<button class="btn border dropdown-toggle mr-2" type="button" id="invoice-options-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>' +
                    'Options' +
                    '</button>' +
                    '<div id="multiDelete" class="dropdown-menu dropdown-menu-right" aria-labelledby="gestion-user-options-btn">' +
                        '<span class="dropdown-item" style="cursor:pointer">Supprimer</span>' +
                    '</div>' +
                '</div>' +
            '</div>';

        // On met un timout car datatable.js doit d'abord créer les divs.
        setTimeout(() => {
            $("#gestion_user_table_filter").append(options);
        }, "100");
    });
</script>
<script src="js/edit_user.js"></script>
<script src="js/add_user.js"></script>
<script src="js/gestion_user.js"></script>
</body>
</html>