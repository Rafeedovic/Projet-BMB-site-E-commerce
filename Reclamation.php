<?php 
    /* La page de réclamation permet d'ouvrir des conversations au gestionnaire/admin par les clients/fournisseurs/gestionnaires :
        -Les clients et les fournisseurs peuvent voir leurs requête en cours et en créer de nouvelles, il peuvent aussi voir leurs propres sujet clos.
        -Les gestionnaires peuvent voir toute les requête fournisseur et en céer de nouvelles avec les admins, ils peuvent aussi clore les requéte fournisseur.
        -Les admins peuvent voir toute les requête et les clores, ils peuvent aussi voir les sujets cloturés.
    */
    //On test si un utilisateur est déjà connecté et on récupére la valeur de son rôle.
    include('Load.php');
    session_start();
    if(!empty($_SESSION["user"]["Type"])){
        $user = $_SESSION["user"]["Type"];
        $id = $_SESSION["user"]["ID"];
    } else {
        $user = 0;
    }
    if($user==0){ //On envoie le client à une page où son rôle lui permet d'être
        header('Location:Accueil.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page d'accueil du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <header>
            <!--Contient le nom du site et la barre de navigation.-->
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
                } else if ($user ==1){
            ?>
            <nav >
                <!--La barre de navigation pour le client contient la page accueil, magasin, panier et profil-->
                <a href="Accueil.php">Accueil</a>
                <a href="Magasin.php">Magasin</a>
                <a href="panier/Panier.php" class="minor">Panier</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } else if ($user ==2){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de produit, liste des produits et profil-->
                <a href="Accueil.php">Ajouter produit</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } else if ($user ==3){
            ?>
            <nav >
                <!--La barre de navigation pour le gestionaire contient la page liste de fournisseur, liste des produits et profil-->
                <a href="Liste_user.php">Fournisseur</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } else if ($user ==4){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de fournisseur/client/gestionnaire/produit, liste des produits, liste des user et son profil-->
                <a href="Accueil.php"> Accueil</a>
                <a href="Liste_user.php">Comptes usagers</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php } ?>
        </header>
        <main>
            <div class="liste_message">
                <?php
                //On récupére les sujet au quel il peut accédé :
                if ($user==1 || $user==2){
                    $sql = "SELECT * FROM `reclame` WHERE `id_user` = '$id' AND `num_message` = '0'";
                } elseif ($user==3){
                    $sql = "SELECT * FROM `reclame` WHERE (`id_user` = '$id' OR `niv_reclame`='0') AND `num_message` = '0'";
                } else {
                    $sql = "SELECT * FROM `reclame` WHERE `num_message` = '0'";
                }
                $result = mysqli_query($con,$sql);
                while($row = mysqli_fetch_assoc($result)){ 
                    $niv = $row["niv_reclame"];
                    $id_poster = $row["id_user"];
                    $reclame = $row["id_reclame"];
                    $message = $row["message"];
                    $sql2 = "SELECT * FROM `reclame` WHERE `id_reclame`='$reclame' AND `niv_reclame`='3'";
                    $result2 = mysqli_query($con,$sql2);
                    $count = mysqli_num_rows($result2);
                    if ($count==0){
                        $clos = 0;
                    } else {
                        $clos = 1;
                    }
                    $sql3 = "SELECT * FROM `user` WHERE `ID`='$id_poster'";
                    $result3 = mysqli_query($con,$sql3);
                    $row3 = mysqli_fetch_assoc($result3);
                    $type = $row3["Type"];
                    $nom = $row3["id_1"]; ?>
                    <div class="reclame">
                        <p class="author"> 
                            <?php
                            if ($type=="1"){?>
                                  Client : <?php echo $nom ?>
                                <?php
                            } elseif ($type=="2"){?>
                                  Fournisseur : <?php echo $nom ?>
                                <?php
                            } elseif ($type=="3"){?>
                                  Gestionaire : <?php echo $nom ?>
                                <?php
                            } elseif ($type=="4"){?>
                                  Admin : <?php echo $nom ?>
                                <?php
                            }
                            ?>
                        </p>
                        <hr>
                        <p class="sujet">
                            <?php echo $message ?>
                        <p>
                        <?php
                        if($clos==1){?>
                            <p class="clos">Sujet clos</p>
                        <?php 
                        } ?>
                        <form method="POST" action="Reclamation_messagerie.php" >
                            <input type="hidden" name="id_conv" value = <?php echo $reclame ?>>
                            <button type="submit" name="voir" class="bouton_message">Voir</button>
                        </form>
                    </div>
                <?php
                } ?>
            </div>
            <hr>
            <!-- On Crée un form pour lui permettre de créer un sujet -->
            <?php 
            if($user!=4){?>
                <h3>Nouveau sujet :</h3>
                <form method="POST" action="reclamation_gestion.php">
                    <input type="text" name="message" placeholder="Question, probléme rencontrer" />
                    <input type="hidden" name="id_user" value = <?php echo $id ?>/>
                    <input type="hidden" name="Action" value = "nouvelle_reclame"/>
                    <input type="submit" name="nouvelle_reclame" value = "Envoyer"/>
                </form>
            <?php } ?>        
        </main>
        <?php
            if(!empty($_SESSION["user"]["Type"])){ //Si la session n'est pas vide on affiche le bouton de déconnexion ?>
                <div id="disconect"> 
                    <fieldset>
                        <input type="button" onclick="window.location.href='deconnexion.php'" name="disconect" value="Déconnexion" id="disconect"/>
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