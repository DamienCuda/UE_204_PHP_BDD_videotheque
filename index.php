<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

<?php include 'php_assets/head.php'?>

<body>
    <?php include 'php_assets/header.php'?>
    <main class="container">
        <div class="row mt-5">
            <div class="col-6">
                <h2>Inscription</h2>
                <form action="php_assets/register.php" method="POST">
                    <label for="username">Nom d'utilisateur : </label>
                    <input type="text" placeholder="Votre nom d'utilisateur" name="username" required> </br>
                    <label for="pass">Mot de passe : </label>
                    <input type="password" placeholder="Votre mot de passe" name="pass" required> </br>
                    <label for="passconfirm">Confirmez votre mot de passe : </label>
                    <input type="password" placeholder="Confirmez votre mot de passe" name="passconfirm" required> </br>
                    <button type="submit">Connexion</button>
                </form>
            </div>
            <div class="col-6 my-auto">
                <h2>Déja un compte ? Connectez vous !</h2>
                <form action="php_assets/login.php" method="POST">
                    <label for="username">Nom d'utilisateur : </label>
                    <input type="text" placeholder="Votre nom d'utilisateur" name="username" required> </br>
                    <label for="pass">Mot de passe : </label>
                    <input type="password" placeholder="Votre mot de passe" name="pass" required> </br>
                    <button type="submit">Connexion</button>
                </form>
            </div>
        </div>
    </main>
    <?php include 'php_assets/footer.php'?>
</body>
</html>