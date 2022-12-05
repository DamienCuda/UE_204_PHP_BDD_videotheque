<?php session_start(); ?>
<!DOCTYPE html>
<html>
<body>

<form action="/cours/pages/register.php" method="POST">

<label for="username">Nom d'utilisateur : </label>
<input type="text" placeholder="Votre nom d'utilisateur" name="username" required> </br>
<label for="pass">Mot de passe : </label>
<input type="password" placeholder="Votre mot de passe" name="pass" required> </br>
<label for="passconfirm">Confirmez votre mot de passe : </label>
<input type="password" placeholder="Confirmez votre mot de passe" name="passconfirm" required> </br>
<button type="submit">Connexion</button>

</form>

<h2>Déja un compte ? Connectez vous !</h2>

<form action="/pages/login.php" method="POST">

<label for="username">Nom d'utilisateur : </label>
<input type="text" placeholder="Votre nom d'utilisateur" name="username" required> </br>
<label for="pass">Mot de passe : </label>
<input type="password" placeholder="Votre mot de passe" name="pass" required> </br>
<button type="submit">Connexion</button>

</form>

</body>
</html>