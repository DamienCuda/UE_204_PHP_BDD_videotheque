<?php
    if(!empty($_SESSION['id'])){
        $is_admin = $_SESSION['is_admin'];
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
                            <img class="navbar-brand img-fluid img_index" src="assets/logo.png" alt="Vidéothèque Groupe 9">
                        </div>               
                    </div>
                </div>
            </header>';
            echo $header;
            return;
        }else{
            $header .= '
                <div class="site-logo col-3">
                    <img class=" img_pages navbar-brand img-fluid" src="assets/Logo.png" alt="Vidéothèque Groupe 9">
                </div>
            ';
        }
        if($is_admin){
            $header .= '
                <div class="col-9">
                <nav class="site-navigation text-right ml-auto" role="navigation">
                    <ul class="nav nav-pills d-flex flex-row justify-content-around" id="nav_menu">
                        <li class="nav-item h5"><a href="catalogue.php" class="nav-link active">Gestion Films</a></li>
                        <li class="nav-item h5"><a href="gestion_user.php" class="nav-link">Gestion Utilisateur</a></li>
                        <li class="nav-item h5"><a href="php_assets/disconnect.php" class="nav-link">Déconnexion</a></li>
            ';
        }else{
            $header .= '
                <div class="col-9">
                <nav class="site-navigation text-right ml-auto" role="navigation">
                    <ul class="nav nav-pills d-flex flex-row justify-content-around" id="nav_menu">                
                        <li class="nav-item h5"><a href="catalogue.php" class="nav-link">Catalogue</a></li>
                        <li class="nav-item h5"><a href="espace_perso_user.php" class="nav-link">Espace Perso</a></li>
                        <li class="nav-item h5"><a href="php_assets/disconnect.php" class="nav-link">Déconnexion</a></li>
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
