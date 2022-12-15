<?php
    if(!empty($_SESSION['id'])){
        $id_user = $_SESSION['id'];
        $is_admin = $_SESSION['is_admin'];
        require_once("permission.php");
    }else{
        $is_admin = false;
    }

    $header = '
        <header class="bg-light">
            <div class="container">
                <div class="row align-items-center">
        ';

        if(str_contains($_SERVER['PHP_SELF'], 'index.php')){
            $header .= '
                        <div class="site-logo col-12 text-center">
                            <img id="logo_index" class="navbar-brand img-fluid img_index" src="assets/logo.png" alt="Vidéothèque Groupe 9">
                        </div>               
                    </div>
                </div>
            </header>';
            echo $header;
            return;
        }else{
            $header .= '
                <div class="site-logo col-3">
                    <img class=" img_pages navbar-brand img-fluid" src="assets/logo.png" alt="Vidéothèque Groupe 9">
                </div>
            ';
        }
        if($is_admin){
            $header .= '
                <div class="col-9">
                <nav class="site-navigation text-right ml-auto" role="navigation">
                    <ul class="nav nav-pills d-flex flex-row justify-content-around align-items-center" id="nav_menu">
                        <li class="nav-item h5"><a href="catalogue.php" class="nav-link active">Gestion Films</a></li>
                        <li class="nav-item h5"><a href="gestion_user.php?id='.$id_user.'" class="nav-link">Gestion Utilisateur</a></li>
                        <li class="nav-item h5"><a href="liste.php?id='.$id_user.'" class="nav-link">Ma Liste</a></li>
                        <div class="d-flex align-items-center">
                        <li class="nav-item h5"><a href="espace_perso_user.php?id='.$id_user.'" class="nav-link"><i class="icones_nav bx bx-user-circle"></i></a></li>
                           <li class="nav-item h5"><a href="php_assets/disconnect.php" class="nav-link"><i class="bx bx-log-out icones_nav"></i></a></li>
                        </div>
            ';
        }else{
            $header .= '
                <div class="col-9">
                <nav class="site-navigation text-right ml-auto" role="navigation">
                    <ul class="nav nav-pills d-flex flex-row justify-content-around" id="nav_menu">                
                        <li class="nav-item h5"><a href="catalogue.php" class="nav-link">Catalogue</a></li>
                        <li class="nav-item h5"><a href="liste.php?id='.$id_user.'" class="nav-link">Ma Liste</a></li>
                        <div class="d-flex align-items-center">
                            <li class="nav-item h5"><a href="espace_perso_user.php?id='.$id_user.'" class="nav-link"><i class="icones_nav bx bx-user-circle"></i></a></li>
                            <li class="nav-item h5"><a href="php_assets/disconnect.php" class="nav-link"><i class="icones_nav bx bx-log-out"></i></a></li>
                        </div>
            ';
        }
        $header .= '
                        </ul>
                    </nav>
                    </div>
                </div>
            </div>
        </header>
        ';
        echo $header;
?>

<script src="js/menu.js"></script>