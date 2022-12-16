<?php
    if(!empty($_SESSION['id'])){
        $id_user = $_SESSION['id'];
        $is_admin = $_SESSION['is_admin'];
        require_once("permission.php");
        require_once("connectdb.php");
        
        //Requête pour recupérer le solde utilisateur
        $solde_user_req = $conn->prepare('
        SELECT solde FROM utilisateurs
            WHERE id = ?');
        $solde_user_req->execute(array($id_user));
        $solde_user = $solde_user_req->fetch(PDO::FETCH_ASSOC);

    }else{
        $is_admin = false;
    }

    $header = '
        <header class="bg-light">
            <nav class="align-items-center navbar navbar-expand-lg navbar-light bg-light px-5 ms-md-auto">
        ';

        if(str_contains($_SERVER['PHP_SELF'], 'index.php')){
            $header .= '
                        <div class="site-logo col-12 text-center">
                            <img id="logo_index" class="navbar-brand img-fluid img_index" src="assets/logo.png" alt="Vidéothèque Groupe 9">
                        </div>               
                    </nav>
                </div>
            </header>';
            echo $header;
            return;
        }else{
            $header .= '
                <img class="site-logo img_pages navbar-brand img-fluid" src="assets/logo.png" alt="Vidéothèque Groupe 9">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            ';
        }
        if($is_admin){
            $header .= '
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="col-md-9 col-sm-12 d-lg-flex d-md-inline d-sm-inline nav-pills justify-content-around align-items-center text-md-end text-sm-end" id="nav_menu">
                    <li class="nav-item h5"><a href="catalogue.php" class="nav-link p-2">Gestion Films</a></li>
                    <li class="nav-item h5"><a href="gestion_user.php?id='.$id_user.'" class="nav-link p-2">Gestion Utilisateur</a></li>
                    <li class="nav-item h5"><a href="liste.php?id='.$id_user.'" class="nav-link p-2">Ma Liste</a></li>
                </ul>
                <ul class="col-md-3 col-sm-12 d-lg-flex d-md-inline d-sm-inline align-items-center justify-content-around align-items-center icon-right-nav text-md-end text-sm-end">
                    <li class="nav-item h5"><a href="espace_perso_user.php?id='.$id_user.'" class="nav-link p-2"><i class="icones_nav bx bx-user-circle" id="user_icon"></i></a></li>
                    <li class="nav-item h5 d-flex align-items-center justify-content-md-end justify-content-sm-end solde_icon"><i class="p-2 icones_nav bx bxs-coin-stack"></i><span>'.$solde_user['solde'].'</span></li>
                    <li class="nav-item h5"><a href="php_assets/disconnect.php" class="nav-link p-2"><i class="bx bx-log-out icones_nav"></i></a></li>
                </ul>
            ';
        }else{
            $header .= '
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="col-md-9 col-sm-12 d-lg-flex d-md-inline d-sm-inline nav-pills justify-content-around align-items-center text-md-end text-sm-end" id="nav_menu">                
                        <li class="nav-item h5"><a href="catalogue.php" class="nav-link p-2">Catalogue</a></li>
                        <li class="nav-item h5"><a href="liste.php?id='.$id_user.'" class="nav-link p-2">Ma Liste</a></li>
                     </ul>
                     <ul class="col-md-3 col-sm-12 d-lg-flex d-md-inline d-sm-inline align-items-center justify-content-around align-items-center icon-right-nav text-md-end text-sm-end">
                         <li class="nav-item h5"><a href="espace_perso_user.php?id='.$id_user.'" class="nav-link p-2"><i class="icones_nav bx bx-user-circle" id="user_icon"></i></a></li>
                         <li class="nav-item h5 d-flex align-items-center justify-content-md-end justify-content-sm-end solde_icon"><i class=" p-2 icones_nav bx bxs-coin-stack"></i><span>'.$solde_user['solde'].'</span></li>
                         <li class="nav-item h5"><a href="php_assets/disconnect.php" class="nav-link p-2"><i class="icones_nav bx bx-log-out"></i></a></li>
                     </ul>
            ';
        }
        $header .= '
                </div>
            </nav>
        </header>
        ';
        echo $header;
?>

<script src="js/menu.js"></script>