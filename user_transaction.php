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
            //ON récupère l'id de l'utilisateur depuis la variable GET 
            $user_id = $_GET['user_id'];

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
            foreach($transactions as $key => $value){
                $total_transaction += $value['amount'];
            }

        ?>
        <!-- Bouton de retour par historique -->
        <section class=container>
            <div class="row">
                <button class=" col-12 btn btn-light btn-back d-flex align-items-center justify-content-between mt-5" id="back">
                    <i class='bx bx-left-arrow-alt'></i><span>Retour</span>
                </button>
            </div>
        </section>
        <!-- Section d'affichage des informations utilisateur -->
        <section id="user_infos_container" class="container">
            <div class="row my-5">
                <div class="col-12 text-light text-center text-sm-center text-md-center text-lg-start text-xl-start">
                    <h2 class="text-center">Historique des transactions<br><span class="yellow-txt"><?= $user['login']; ?></span></h2>
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

        <!-- Section d'affichage des informations utilisateur -->
        <section class="container">
            <div id="transaction_user" class="mt-5 row">
                <table class="table table-striped responsive nowrap id="transaction_user_table">     
                    <thead>
                        <tr>
                            <td>Montant</td>
                            <td>Film</td>
                            <td>Date de location</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($total_transaction != 0){
                                foreach($transactions as $transaction):
                                    
                                    //On faormate la date pour l'afffichage
                                    $originalDate = $transaction['date'];
                                    $newDate = date("d/m/Y", strtotime($originalDate));

                                    // Pour chaque passage on récupère le titre du film correspondant à la transaction
                                    $rented_movie_req = $conn->prepare('
                                        SELECT title FROM catalogue
                                            WHERE id = ?');
                                    $rented_movie_req->execute(array($transaction['movie_id']));
                                    $movie = $rented_movie_req->fetch()
                        ?>
                            <tr>
                                <td><?= $transaction['amount']; ?></td>
                                <td><?= $movie['title']; ?></td>
                                <td><?= $newDate; ?></td>
                            </tr>
                        <?php
                                endforeach;
                            }else{
                        ?>
                            <tr><h5 class='no-data '>Aucun film n'a été loué pour le moment...</h5></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php include 'php_assets/footer.php'?>

    <script src="js/edit_user.js"></script>
    <script src="js/add_user.js"></script>
    <script src="js/gestion_user.js"></script>
    <script src="js/back.js"></script>
</body>
</html>