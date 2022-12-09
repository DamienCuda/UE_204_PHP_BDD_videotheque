<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'php_assets/head.php'?>

<body>
    <?php include 'php_assets/header.php'?>
    <main class="container">
        <div class="row mt-5 d-flex align-content-start">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Inscription</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" id="registerForm">
                            <div class="col-12 mt-3">
                                <label for="username">Nom d'utilisateur : </label>
                                <input type="text" class="form-control" placeholder="Votre nom d'utilisateur" name="username" id="username">
                                <div id="validationUsernameFeedback" class="invalid-feedback">
                                    Veuillez renseigner un nom d'utilisateur.
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="pass">Mot de passe : </label>
                                <input type="password" class="form-control" placeholder="Votre mot de passe" name="pass" id="pass">
                            </div>
                            <div class="col-12 mt-3">
                                <label for="passconfirm">Confirmez votre mot de passe : </label>
                                <input type="password" class="form-control" placeholder="Confirmez votre mot de passe" name="passconfirm" id="passconfirm">
                                <div id="validationPasswordFeedback" class="invalid-feedback">
                                    Le mot de passe doit contenir minimum 8 caractères, une majuscule, un chiffre et un caractère spécial.
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-primary" id="registerBtn">S'INSCRIRE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Déja un compte ? Connectez vous !</h2>
                    </div>
                    <div class="card-body">
                        <form action="php_assets/login.php" method="POST">
                            <label for="username">Nom d'utilisateur : </label>
                            <input type="text" class="form-control" placeholder="Votre nom d'utilisateur" name="username" required> </br>
                            <label for="pass">Mot de passe : </label>
                            <input type="password" class="form-control" placeholder="Votre mot de passe" name="pass" required> </br>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">SE CONNECTER</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Register -->
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
                        <p>Merci beaucoup ! Votre inscription a été prise en compte.</p>
                        <p>Vous pouvez maintenant vous connecter !</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">SE CONNECTER</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'php_assets/footer.php'?>
    <script src="js/register.js"></script>
</body>
</html>