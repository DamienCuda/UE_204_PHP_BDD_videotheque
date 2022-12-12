<?php
session_start();

if(!empty($_SESSION['id']))
{
    //l'utilisateur est connecté
    $is_admin = $_SESSION['is_admin'];
}else{
    header("location: index.php");
}