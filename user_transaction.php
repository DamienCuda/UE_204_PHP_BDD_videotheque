<?php
    require_once("php_assets/connectdb.php");
    require("php_assets/verif_session_connect.php");
    require("php_assets/fonctions.php");

    // On sécurise l'accès pour donner accès à cette page uniquement à l'utilisateur qui console ses transactions ou au admins
    if($is_admin === false && $_SESSION['id'] != $_GET['user_id']){
        header("location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <?php include 'php_assets/head.php' ?>
    <?php include 'php_assets/header.php' ?>
    <body>
        <main>
            <?php
                //ON récupère l'id de l'utilisateur depuis la variable GET
                $user_id = nettoyage($_GET['user_id']);

                // Requête d'info sur l'utilisateur
                $user_req = $conn->prepare('SELECT * FROM utilisateurs WHERE id = ?');
                $user_req->execute(array($user_id));
                $user = $user_req->fetch();

                // Requête des transcations de l'utilisateur selon la variable GET
                $transaction_req = $conn->prepare('SELECT DISTINCT * FROM transactions WHERE user_id = ?');
                $transaction_req->execute(array($user_id));
                $transactions = $transaction_req->fetchALL(PDO::FETCH_ASSOC);

                //Calcul du total des transactions effectué par l'utilisateur
                $total_transaction = 0;
                foreach ($transactions as $key => $value) {
                    $total_transaction += $value['amount'];
                }
            ?>
            <!-- BEGIN: Bouton de retour via historique -->
            <section class=container>
                <div class="row">
                    <button class=" col-12 btn btn-light btn-back d-flex align-items-center justify-content-between mt-5"
                            id="back">
                        <i class='bx bx-left-arrow-alt'></i><span>Retour</span>
                    </button>
                </div>
            </section>
            <!-- END: Bouton de retour via historique -->

            <!-- BEGIN: Informations de l'utilisateur -->
            <section id="user_infos_container" class="container">
                <div class="row my-5">
                    <div class="col-12 text-light text-center text-sm-center text-md-center text-lg-start text-xl-start">
                        <h2 class="text-center">Historique des transactions<br><span
                                    class="yellow-txt"><?= $user['login']; ?></span></h2>
                    </div>
                </div>
                <div class="row mb-5 text-center">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 text-light mx-auto">
                        <p class="mb-2 h5 text-center">Email : <span id="email_zone"><?= $user['email']; ?></span></p>
                        <p class="mb-2 h5 text-center">Rang : <span><?= $user['rang']; ?></span></p>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 text-light mx-auto">
                        <p class="mb-2 h5 text-center">Solde : <span><?= $user['solde']; ?></span></p>
                        <p class="mb-2 h5 text-center">Total achat : <span><?= $total_transaction; ?></span></p>
                    </div>
                </div>
            </section>
            <!-- END: Informations de l'utilisateur -->

            <!-- BEGIN: Transactions de l'utilisateur -->
            <?php if (count($transactions) > 0) { ?>
                <section class="container">
                    <div id="transaction_user" class="mt-5 row">
                        <table class="table table-striped responsive nowrap" id="transaction_user_table">
                            <thead>
                            <tr>
                                <th>ID transaction</th>
                                <th>Montant</th>
                                <th>Film</th>
                                <th>Date de location</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($transactions as $transaction):

                                //On faormate la date pour l'afffichage
                                $originalDate = $transaction['date'];
                                $newDate = date("d/m/Y", strtotime($originalDate));

                                // Pour chaque passage on récupère le titre du film correspondant à la transaction
                                $rented_movie_req = $conn->prepare('SELECT title FROM catalogue WHERE id = ?');
                                $rented_movie_req->execute(array($transaction['movie_id']));
                                $movie = $rented_movie_req->fetch()
                                ?>
                                <tr>
                                    <td>#<?= $transaction['id']; ?></td>
                                    <td>
                                        <span class="d-flex align-items-center justify-content-center"><?= $transaction['amount']; ?> <i
                                                    class='bx bx-coin ml-2'></i></span></td>
                                    <td><?= $movie['title']; ?></td>
                                    <td><?= $newDate; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php } else { ?>
                <tr><h5 class='no-data justify-content-center d-flex'>Aucun film n'a été loué pour le moment...</h5></tr>
            <?php } ?>
            <!-- END: Transactions de l'utilisateur -->
        </main>

        <?php include 'php_assets/footer.php' ?>

        <!-- BEGIN: Script JS de la datatable -->
        <script>
            $(document).ready(function () {
                $('#transaction_user_table').DataTable({
                    language: {
                        url: 'app-assets/dataTables.french.json'
                    },
                    responsive: true
                });

                // On met un timout car datatable.js doit d'abord créer les divs.
                setTimeout(() => {
                    $("#gestion_user_table_filter").append(options);
                }, "100");
            });
        </script>
        <!-- END: Script JS de la datatable -->

        <!-- BEGIN: Script JS -->
        <script src="js/edit_user.js"></script>
        <script src="js/add_user.js"></script>
        <script src="js/gestion_user.js"></script>
        <script src="js/back.js"></script>
        <!-- BEGIN: Script JS -->
    </body>
</html>