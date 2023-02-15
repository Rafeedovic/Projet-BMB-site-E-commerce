<?php 
    /* La page Poduit_edit permet de réaliser les actions lier à la modification d'autre profil que l'utilisateur :
        -Le client, le visiteur sont rediriger vers la page d'accueil car ils ne sont pas sensé être ici.
        -Le fournisseur peut édité toute les information du produit sauf son fournisseur et sa mise en avant, il ne peut pas être ici pour un produit d'on il n'est pas fournisseur.
        -Le gestionnaire et L'admin peuvent édité toute les information du produit.
        F : 30/12/2022 15h02    
    */
    include('Load.php');
    session_start();
    if(!empty($_SESSION)){ //On test si un utilisateur est déjà connecté et on récupére la valeur de son rôle.
        $user = $_SESSION["user"]["Type"];
    } else {
        $user = 0;
    }
    if($user == 0 || $user == 1){ //On envoie les clienst et les visiteurs sur la page d'acceil.
        header('Location:Accueil.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page d'édidition de produit du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <main id="form_edit">
            <div class="fprofil">
                <nav id="form_nav">
                    <a href="Accueil.php" id="form_nav">← Accueil  </a>
                </nav>
                </br>
                <form action="edit.php" method="POST">
                    <?php
                        if(isset($_POST["ID"])){ //On récupére l'id du produit par un post, si le post est vide la page à été recharger suite à une erreur.
                            $ID = $_POST["ID"];
                            $_SESSION['id_edit'] = $ID; //On sauvegarde l'id dans la session au cas ou la page est rechargé
                        } elseif (isset($_SESSION['id_edit'])){
                            $ID = $_SESSION['id_edit']; //On peut ainsi récupéré l'id même si il n'y a plus de post.
                        }
                        
                        $sql = "SELECT * FROM `produit` WHERE `ID` = '$ID'"; //On récupére les informations du produit.
                        $result = mysqli_query($con,$sql);
                        $row = mysqli_fetch_assoc($result);
                        $Nom = $row["Nom"];
                        $Prix = $row["Prix"];
                        $Quantite = $row["Quantite"];
                        $Url_image = $row["Url_image"];
                        $Fournisseur = $row["Fournisseur"];
                        $Mea = $row["Mea"];
                        $Categorie = $row["Categorie"];
                        $Description = $row["Description"];
                    ?>
                    <fieldset>
                        <div class="formulaire_ajout_casier">
                            <div class ="formulaire_ajout_case">
                                <P>Nom du produit :</p>
                                <p>Prix : </p>
                                <p>Quantité : </p>
                                <p>Url de l'image : </p>
                                <?php
                                if($user==3 || $user==4){ ?>
                                    <p> Fournisseur : </p>
                                    <p> Mise en avant : </p> 
                                <?php
                                } ?>
                                <p> Categorie : </p>
                                <p> Description : </p>
                            </div>
                            <div class ="formulaire_ajout_case">
                                <input type="hidden" name="Origine" value = "Produit_edit.php"/>
                                <input type="hidden" name ="ID" value = '<?php echo $ID ?>' />
                                <input type="text" name="Nom" placeholder="Nom" value='<?php echo $Nom ?>' />
                                <input type="text" name="Prix" placeholder="Prix" value='<?php echo $Prix ?>' />
                                <input type="text" name="Quantity" placeholder="Quantity" value= '<?php echo $Quantite ?>' />
                                <input type="text" name="Url_image" placeholder="Url_image" value='<?php echo $Url_image ?>' />
                                <?php
                                if($user==3 || $user==4){ //si l'utilisateur n'est pas un fournisseur on affiche le fournisseur et un moyen pour le changer. ?> 
                                    <select name="Fournisseur">
                                        <?php
                                        $sql2 = "SELECT * FROM `user` WHERE `Type`='2' AND `ID`='$Fournisseur'";
                                        $result2 = mysqli_query($con,$sql2);
                                        $row2 = mysqli_fetch_assoc($result2); ?>
                                        <option value ="<?php echo $Fournisseur ?>"> <?php echo $row2["id_1"] ?> </option>
                                        <?php
                                        $sql3 = "SELECT * FROM `user` WHERE `Type`='2' AND `ID`!='$Fournisseur'";
                                        $result3 = mysqli_query($con,$sql3);
                                        while($row3 = mysqli_fetch_assoc($result3)){ ?>
                                            <option value ="<?php echo $row3["ID"] ?>"> <?php echo $row3["id_1"] ?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select> </br> 
                                    <select name="Mea">
                                        <?php
                                        if($Mea==0){ ?>
                                            <option value ="0"> Non </option>
                                            <option value ="1"> Oui </option>
                                        <?php
                                        } else {
                                            ?>
                                            <option value ="1"> Oui </option>
                                            <option value ="0"> Non </option>
                                        <?php
                                        } ?>
                                    </select>
                                <?php
                                } ?>
                                <input type="text" name="Categorie" placeholder="Categorie" value='<?php echo $Categorie ?>' />
                                <input type="text" name="Description" placeholder="Description" value='<?php echo $Description ?>' />
                            </div>
                        </div>
                        <select name="Action">
                            <option value ="Mod_produit"> Modifier le produit </option>
                            <option value ="Sup_produit"> Supprimer le produit </option>
                        <br>
                        <?php
                            if (isset($_GET['error'])){
                                $err = $_GET['error'];
                                if ($err=="error_nom"){ //On affiche les erreurs s'il y en a.?>
                                    <h3>Nom de produit déjà utilisé ou absent</h3>
                                <?php
                                } else if ($err=="error_prix"){ ?>
                                    <h3>Prix absent ou de mauvais format.</h3>
                                    <h3> Indiquer uniquement une valeur numérique avec un . pour virgule.</h3>
                                <?php
                                } else if ($err=="error_Quantoty"){ ?>
                                    <h3>Quantité inferieur à 0 ou absent.</h3>
                                <?php 
                                }
                            } ?>
                        <input type="submit" name="confirmer" value="Valider" />
                    </fieldset>
                </form>
            </div>
        </main>
    </body>
</html>