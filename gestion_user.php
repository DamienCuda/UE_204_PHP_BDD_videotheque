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
            <table class="table table-striped" id="gestion_user_table">
                <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Photo de profil</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Mot de passe</th>
                    <th>Rôle</th>
                    <th>Action</th>
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
                    }else{
                        $rang = 0;
                    }

                    ?>
                    <tr class="<?php if($user['id'] == $_SESSION['id']){ echo "you"; } ?>">
                        <td id="user_<?= $user['id']; ?>" class="checkbox_table"></td>
                        <td><?= $user['id']; ?></td>
                        <td>
                            <div class="profil-membre" style="background: url('img/profil_img/<?= $user['profile_picture']; ?>');"></div>
                        </td>
                        <td><?= $user['login']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td>********</td>
                        <td>
                            <?php
                            if($user['is_admin'] == 1){
                                echo $user['rang'];
                            }else{
                                echo "Membre";
                            }
                            ?>
                        </td>
                        <td>
                            <div class="d-flex justify-content-around">
                                <a data-toggle="modal" data-target="#transaction-historique"><i class='bx bx-money-withdraw'></i></a>
                                <?php
                                    // Si notre permission est supérieur au rang de l'utilsateur on à accès la modification, destition et promotion de l'utilisateur.
                                    if($permission > $rang){
                                        ?>
                                        <button class="edit_btn" title="Modifier"><i class='bx bx-edit-alt'></i></button>
                                        <?php
                                            // Si l'utilisateur est membre on ne peut pas le desituer car y a plus rien en dessous.
                                            if($rang > 0){
                                                ?>
                                                    <a href="php_assets/downgrade_user.php?id=<?= $user['id']; ?>" title="Destituer"><i class='bx bx-chevrons-down'></i></a>
                                                <?php
                                            }else if($rang < 2){
                                        ?>
                                        <a href="php_assets/upgrade_user.php?id=<?= $user['id']; ?>" title="Promouvoir"><i class='bx bx-chevrons-up'></i></a>
                                        <?php
                                            }
                                    }
                                    if($permission >= 2 && $rang < 3){
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
            </table>
        </section>
    </div>
</main>

<!-- Modal Transaction -->
<div class="modal fade" id="transaction-historique" tabindex="-1" role="dialog" aria-labelledby="transaction-historiqueLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-up" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="exampleModalLabel"><span style="margin-right: 10px;">Historique des transactions</span> <i class='bx bx-money-withdraw'></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <table class="table table-striped" id="gestion_user_table">
                    <thead>
                        <tr>
                            <td>Photo</td>
                            <td>Utilisateur</td>
                            <td>Montant</td>
                            <td>Film</td>
                            <td>Durée</td>
                            <td>Date</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>Test</td>
                            <td>10€</td>
                            <td>Harry Potter et la Coupe de Feu</td>
                            <td>24H</td>
                            <td>01/02/2022</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Test</td>
                            <td>10€</td>
                            <td>Harry Potter et la Coupe de Feu</td>
                            <td>24H</td>
                            <td>01/02/2022</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">FERMER</button>
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
            order: [[ 1, 'asc' ]]
        });

        // Ajout du bouton "options" à coter de la barre de recherche.
        let options =
            '<div class="action-dropdown-btn">' +
                '<div class="dropdown invoice-options">' +
                    '<button class="btn border dropdown-toggle mr-2" type="button" id="invoice-options-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>' +
                    'Options' +
                    '</button>' +
                    '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="gestion-user-options-btn">' +
                        '<span class="dropdown-item" id="multiDelete" style="cursor:pointer">Supprimer</span>' +
                    '</div>' +
                '</div>' +
            '</div>';

        // On met un timout car datatable.js doit d'abord créer les divs.
        setTimeout(() => {
            $("#gestion_user_table_filter").append(options);
        }, "100")
    });
</script>
<script src="js/edit_user.js"></script>
</body>
</html>