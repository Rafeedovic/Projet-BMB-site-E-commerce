<?php 
    /* La page de messagerie affiche une conversation identifier par son id et permet d'en ajouter si elle n'est pas cloturé.
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
                <a href="connection/Connection.php" class="minor">Connection</a>
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
                <!--La barre de navigation pour le fournisseur contient la page ajout de fournisseur/client/gestionnaire/produit, liste des produits, liste des users et son profil-->
                <a href="Accueil.php"> Accueil</a>
                <a href="Liste_user.php">Comptes usagers</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php } ?>
        </header>
        <main>
            <?php
            if(isset($_POST["id_conv"])){ //On récupére l'id de la conversation, si le post est vide la page à été recharger suite à une erreur.
                $id_conv = $_POST["id_conv"];
                $_SESSION['id_conv'] = $id_conv; //On sauvegarde l'id dans la session au cas ou la page est rechargé
            } elseif (isset($_SESSION['id_conv'])){
                $id_conv = $_SESSION['id_conv']; //On peut ainsi récupéré l'id même si il n'y a plus de post.
            }
            $sql = "SELECT * FROM `reclame` WHERE `id_reclame`='$id_conv' ORDER BY `num_message`";
            $result = mysqli_query($con,$sql);
            $sql2 = "SELECT * FROM `reclame` WHERE `id_reclame`='$id_conv' AND `niv_reclame`='3'";
            $result2 = mysqli_query($con,$sql2);
            $count = mysqli_num_rows($result2);
            if ($count==0){
                $clos = 0;
            } else {
                $clos = 1;
            } 
            while($row = mysqli_fetch_assoc($result)){ 
                $id_poster = $row["id_user"];

                $sql2 = "SELECT * FROM `user` WHERE `ID`='$id_poster'";
                $result2 = mysqli_query($con,$sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $type = $row2["Type"];
                $nom = $row2["id_1"];

                $niv = $row["niv_reclame"];
                $message = $row["message"]; 
                
                ?>
                <div class="reclame_messagerie">
                    <p class="author"> 
                        <?php
                        if ($type=="1"){?>
                            De : <?php echo $nom ?>, Client
                            <?php
                        } elseif ($type=="2"){?>
                            De : <?php echo $nom ?>, Fournisseur 
                            <?php
                        } elseif ($type=="3"){?>
                            De : <?php echo $nom ?>, Gestionaire
                            <?php
                        } elseif ($type=="4"){?>
                            De : <?php echo $nom ?>, Admin
                            <?php                        
                        } ?>
                    </p>
                    
                    <p class="sujet_messagerie">
                        <?php if($niv==3){ echo 'Cloture du sujet <br/>';} ?>
                        <?php echo $message ?>
                    <p>
                </div>
                <?php
            }
            if($clos==1){ ?>
                <p class="Cloture"> Sujet clos</p>
            <?php }
            if ($clos==0){?>
            <!-- On Crée un form pour lui permettre de répondre au sujet -->
                <form method="POST" action="reclamation_gestion.php">
                    <input type="text" name="message" placeholder="Reponse" />
                    <input type="hidden" name="niv_reclame" value = <?php echo $niv ?> />
                    <input type="hidden" name="id_conv" value = <?php echo $id_conv ?>>
                    <input type="hidden" name="id_user" value = <?php echo $id ?>>
                    <input type="hidden" name="Action" value = "reponse"/>
                    <input type="submit" name="reponse" class="confirmer action-button" value="Envoyer"/>
                </form>
                <?php
                if ($user==4){ ?>
                    <form method="POST" action="reclamation_gestion.php">
                        <input type="hidden" name="id_conv" value = <?php echo $id_conv ?>>
                        <input type="hidden" name="id_user" value = <?php echo $id ?>>
                        <input type="hidden" name="Action" value = "clos"/>
                        <input type="submit" name="clos" class="confirmer action-button" value="Clore le sujet"/>
                    </form>
                <?php
                }?>
            <?php 
            } ?>
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