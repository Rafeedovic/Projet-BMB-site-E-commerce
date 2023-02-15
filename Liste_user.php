<?php 
    /* La page liste_user permet de réaliser les action lier à la liste des utilisateur de chaque utilisateur :
        -Le client, le visiteur et le fournisseur sont rediriger vers la page d'accueil car ils ne sont pas sensé être ici.
        -Le gestionnaire voit uniquement les fournisseur et à un bouton d'édition.
        -L'admin voit l'ensemble des utilisateur et à un bouton d'adition sauf pour les autres admins. 
        F : 28/12/2022 20:17    
    */
    include('Load.php');
    session_start();
    //On test si un utilisateur est déjà connecté et on récupére la valeur de son rôle.
    if(!empty($_SESSION["user"]["Type"])){
        $user = $_SESSION["user"]["Type"];
    } else {
        $user = 0;
    }
    if($user == 0 || $user == 1 || $user == 2){ //On redirige les visiteurs/clients/fournisseurs vers la page d'accueil
        header('Location:Accueil.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page listant les utilisateurs du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <header>
            <!--Contient nom du site et la barre de navigation.-->
            <div class="Head">
                <h1>BMB</h1>
            </div>
            <?php
                if ($user ==0){
            ?>
            <nav >
                <!--La barre de navigation pour le visiteur contient la page accueil, magasin, connection et inscription-->
                <a href="Accueil.php">Accueil</a>
                <a href="Magasin.php">Magasin</a>
                <a href="panier/Panier.php" class="minor">Panier</a>
                <a href="inscription/Inscription.php" class="minor">Inscription</a>
                <a href="connection/Connection.php" class="minor">Connexion</a>
            </nav>
            <?php
                } elseif ($user ==1){
            ?>
            <nav >
                <!--La barre de navigation pour le client contient la page accueil, magasin, panier et profil-->
                <a href="Accueil.php">Accueil</a>
                <a href="Magasin.php">Magasin</a>
                <a href="panier/Panier.php" class="minor">Panier</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==2){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de produit, liste des produits et profil-->
                <a href="Accueil.php">Ajouter produit</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==3){
            ?>
            <nav >
                <!--La barre de navigation pour le gestionaire contient la page liste de fournisseur, liste des produits et profil-->
                <a href="Liste_user.php">Fournisseur</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==4){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de fournisseur/client/gestionnaire/produit, liste des produits, liste des users et leurs profils-->
                <a href="Accueil.php"> Accueil</a>
                <a href="Liste_user.php">Comptes usagers</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php } ?>
        </header>
        <main>
            <div class=list_utilisateur>
                <?php 
                    if($user==3){ //Si l'utilisateur est un gestionnaire on lui donne l'accés au données fournisseur
                        $sql = "SELECT * FROM `user` WHERE `Type` = '2'";
                        $result = mysqli_query($con,$sql);
                    } else { //Sinon il est admin est à accés à touts les utilisateurs non admin.
                        $sql = "SELECT * FROM `user` WHERE `Type` < '4'";
                        $result = mysqli_query($con,$sql);
                    }
                    if($result){
                        while($row = mysqli_fetch_assoc($result)){ //On récupére et affiche dans une case les informations de chaque utilisateur
                            $id = $row["ID"];
                            $id_1 = $row["id_1"];
                            $id_2 = $row["id_2"];
                            $mail = $row["Mail"];
                            $mail = str_replace("[a]","@", $mail);
                            $num_tel = $row["Num_tel"];
                            $adresse = $row["Adresse"]; ?>
                            <div class="user_case">
                                <p>ID : <?php echo $id ?></p>
                                <p>Nom : <?php echo $id_1 ?> </h4>
                                <?php
                                if($row["Type"]==2){ ?>
                                    <p>Numéro de SIRET : <?php echo $id_2 ?></p>
                                <?php
                                } else { ?>
                                    <p>Prénom : <?php echo $id_2 ?></p>
                                <?php
                                } ?>
                                <p>Mail : <?php echo $mail ?></p>
                                <p>Numéro de téléphone : <?php echo $num_tel ?></p>
                                <p>Adresse : <?php echo $adresse ?></p>
                                <?php
                                if($user==4){ ?>
                                    <p>Type : <?php echo $row["Type"] ?></p>
                                <?php
                                }?>
                                <form method="POST" action="Profil_edit.php">
                                    <input type="hidden" name="ID" value = <?php echo $id ?>>
                                    <button type="submit" class="button_liste" name="edit_profil">Editer</button> <!-- Le bouton envoie un post à edit.php avec l'id de l'utilisateur à éditer.-->
                                </form>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </main>
        <?php
            if(!empty($_SESSION)){ ?>
                <div id="disconect">
                    <fieldset>
                        <input type="button" onclick="window.location.href='deconnexion.php'" name="disconect" class="disconect_buton" value="Déconnexion" id="disconect"/>
                    </fieldset>
                </div>
            <?php
            } ?>
        <footer>
            <a href="Information.php">Information</a>
            <?php 
            if(!empty($_SESSION["user"]["Type"])) { ?>
                <a href="Reclamation.php">Réclamation</a>
            <?php
            } ?>
        </footer>
    </body>
</html>