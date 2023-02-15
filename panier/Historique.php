<?php 
    /* La page d'historique permet au client uniquement de voir l'historique de ces achat. 
        F:30/12/2022 16:22
    */
    include('../Load.php');
    session_start();
    if(!empty($_SESSION["user"]["Type"])){ //On test si un utilisateur est déjà connecté et on récupére la valeur de son rôle. Tout les non-client son renvoyer à l'accueil
        $user = $_SESSION["user"]["Type"];
    } else {
        $user = 0;
    }
    if($user == 2 || $user == 3 || $user == 4){
        header('Location:../Accueil.php');
    }
    $cache = $_SESSION['user']['ID'];
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="../css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page du panier du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <header>
            <!--Contient nom du site et la barre de navigation.-->
            <div class="Head">
                <h1>SHOP</h1>
            </div>
            <?php
                if ($user ==0){
            ?>
            <nav >
                <!--La barre de navigation pour le visiteur contient la page accueil, magasin, connection et inscription-->
                <a href="../Accueil.php">Accueil</a>
                <a href="../Magasin.php">Magasin</a>
                <a href="Panier.php" class="minor">Panier</a>
                <a href="../inscription/Inscription.php" class="minor">Inscription</a>
                <a href="../connection/Connection.php" class="minor">Connection</a>
            </nav>
            <?php
                } elseif ($user ==1){
            ?>
            <nav >
                <!--La barre de navigation pour le client contient la page accueil, magasin, panier et profil-->
                <a href="../Accueil.php">Accueil</a>
                <a href="../Magasin.php">Magasin</a>
                <a href="Panier.php" class="minor">Panier</a>
                <a href="../profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==2){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de produit, liste des produit et profil-->
                <a href="../Accueil.php">Ajouter produit</a>
                <a href="../Magasin.php">Produits</a>
                <a href="../profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==3){
            ?>
            <nav >
                <!--La barre de navigation pour le gestionaire contient la page liste de fournisseur, liste des produit et profil-->
                <a href="../Accueil.php">Fournisseur</a>
                <a href="../Magasin.php">Produits</a>
                <a href="../profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==4){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de fournisseur/client/gestionnaire/produit, liste des produit, liste des user et son profil-->
                <a href="../Accueil.php"> Accueil</a>
                <a href="../list_compte.html">Comptes usagers</a>
                <a href="../Magasin.php">Produits</a>
                <a href="../profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php } ?>
        </header>
        <main>
            <div class="linky">
                <a href="Panier.php">Retour</a>
            </div>
            <div class="panier">
                <?php
                $sql = "SELECT * FROM `commande` WHERE `ID_user` = '$cache'";
                $result = mysqli_query($con,$sql);
                while($row = mysqli_fetch_assoc($result)){
                    $date = $row["Date"];
                    $prix = $row["Prix_U"];
                    $prix_t = $row["Prix_T"];
                    $prix_f = $row["Prix_F"];
                    $quantite = $row["Quantite"];
                    $id_produit = $row["ID_produit"];
                    $sql2 = "SELECT * FROM `produit` WHERE `ID`='$id_produit'";
                    $result2 = mysqli_query($con,$sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                    $name = $row2["Nom"]; ?>
                    <div class="panier_produit">
                        <div class="description_panier_produit">
                            <h4> <?php echo $name ?> </h4>
                            <div class="description">
                                <p> Acheté: <?php echo $quantite ?> </p>
                                <p> Prix unitaire : <?php echo $prix ?> </p>
                                <p> Prix total : <?php echo $prix_t ?> </p>
                                <p> Prix avec charge : <?php echo $prix_f ?> </p>
                                <p> Date d'achat : <?php echo $date ?> </p>
                            </div>
                        </div>
                    </div>
                <?php 
                }?>
            </div>
        </main>
        <footer>
            <a href="../Information.php">Information</a>
            <?php 
            if(!empty($_SESSION["user"]["Type"])) { ?>
                <a href="Reclamation.php">Réclamation</a>
            <?php
            } ?>
        </footer>
    </body>
</html>