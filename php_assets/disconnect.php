<?php
    require ("verif_session_connect.php");
    unset ($_SESSION['user']);
    session_destroy();
    header("Location: ../index.php");
?>