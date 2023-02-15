<?php 
    /* La page Profil_edit permet de réaliser les actions lier à la modification d'autre profil que l'utilisateur :
        -Le client, le visiteur et le fournisseur sont rediriger vers la page d'accueil car ils ne sont pas sensé être ici.
        -Le gestionnaire peut édité toute les information du fournisseur et ne peut pas être ici pour un profil non fournisseur.
        -L'admin peut édité toute les information du profil qu'il édite  et ne peut pas être ici pour un profil admin.
        F : 30/12/2022 15h11
    */
    include('Load.php');
    session_start();
    if(!empty($_SESSION)){ //On test si un utilisateur est déjà connecté et on récupére la valeur de son rôle.
        $user = $_SESSION["user"]["Type"];
    } else {
        $user = 0;
    }
    if($user == 0 || $user == 1 || $user == 2){ //On envoie les client/visiteur/fournisseur sur la page d'acceil.
        header('Location:Accueil.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page de connection(pour les utilisateurs : visiteur) du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <main id="form_edit">
            <div class="fprofil">
                <nav id="form_nav">
                    <a href="Accueil.php" id="form_nav">← Accueil  </a>
                </nav>
                <form action="edit.php" method="POST">
                    <?php
                        if(isset($_POST["ID"])){ //On récupére l'id du profil par un post, si le post est vide la page à été recharger suite à une erreur.
                            $ID = $_POST["ID"];
                            $_SESSION['id_edit'] = $ID; //On sauvegarde l'id dans la session au cas ou la page est rechargé
                        } elseif (isset($_SESSION['id_edit'])){
                            $ID = $_SESSION['id_edit']; //On peut ainsi récupéré l'id même si il n'y a plus de post.
                        }
                        
                        $sql = "SELECT * FROM `user` WHERE `ID` = '$ID'"; //On récupére les informations du profil.
                        $result = mysqli_query($con,$sql);
                        $row = mysqli_fetch_assoc($result);
                        $id_1 = $row["id_1"];
                        $id_2 = $row["id_2"];
                        $mail = $row["Mail"];
                        $mail = str_replace("[a]","@", $mail);
                        $num_tel = $row["Num_tel"];
                        $adresse = $row["Adresse"];
                        $type = $row["Type"];
                        if($type==4){ //Si le profil est admin on renvoi l'utilisateur à la page d'accueil, il n'est pas sensé accédé au compte admin
                            header('Location:Accueil.php');
                        } elseif($user==3 && $type!=2){ //Si l'utilisateur est gestionnaire mais accéde à un compte non gestionnaire on le renvoie à l'accueil
                            header('Location:Accueil.php');
                        }
                    ?>
                    <fieldset>
                        <h2>Profil N°<?php echo $ID ?></h2>
                        <div class="formulaire_ajout_casier">
                            <div class ="formulaire_ajout_case">
                                <?php
                                if ($type == 2){ //Si le profil est un fournisseur on utilise nom de société et numéro de siret plutôt que nom et prénom?>
                                    <p>Nom de société : </p>
                                    <p>Numéro de siret : </p>
                                <?php
                                } else { ?>
                                    <p>Nom : </p>
                                    <p>Prénom : </p>
                                <?php
                                } ?>
                                <p>Numéro de téléphone : </p>
                                <p>Adresse : </p>
                                <p>Mail : </p>
                                <?php
                                if($user==4){ ?>
                                    <p> Type de compte : </p> 
                                <?php
                                } ?>
                            </div>
                            <div class ="formulaire_ajout_case">
                                <input type="hidden" name="Origine" value = "Profil_edit.php">
                                <input type="hidden" name ="ID" value = '<?php echo $ID ?>' />
                                <input type="text" name="id_1" placeholder="id_1" value='<?php echo $id_1 ?>' />
                                <input type="text" name="id_2" placeholder="id_2" value='<?php echo $id_2 ?>' />
                                <input type="text" name="Num_tel" placeholder="Num_tel" value= '0<?php echo $num_tel ?>' />
                                <input type="text" name="Adresse" placeholder="Adresse" value='<?php echo $adresse ?>' />
                                <input type="text" name="Mail" placeholder="Mail" value='<?php echo $mail ?>' />
                                <?php
                                if($user==4){ //Si l'utilisateur est admin il peut éditer le rôle du profil. ?>
                                    <select name="Type">
                                        <?php
                                        if($type==2){ ?>
                                            <option value ="2"> Fournisseur </option>
                                            <option value ="1"> Client </option>
                                            <option value ="3"> gestionaire </option>
                                        <?php
                                        } elseif ($type==3) { ?>
                                            <option value ="3"> gestionaire </option>
                                            <option value ="1"> Client </option>
                                            <option value ="2"> Fournisseur </option>
                                        <?php
                                        } else { ?>
                                            <option value ="1"> Client </option>
                                            <option value ="2"> Fournisseur </option>
                                            <option value ="3"> gestionaire </option>
                                        <?php
                                        } ?>
                                    </select>
                                <?php
                                } ?>
                            </div>
                        </div>
                        <select name="Action">
                            <option value ="Mod_compte"> Mofifier le compte </option>
                            <option value ="Sup_compte"> Supprimer le compte </option>
                        </select>
                        <input type="password" name="new_pass" placeholder="Nouveau mot de passe" />
                        <br>
                        <?php
                            if (isset($_GET['error'])){ //On affiche les erreurs éventuelle.
                                $err = $_GET['error'];
                                if ($err=="error_prenom"){ ?>
                                    <h3>Prénom ou numéro de siret invalide ou absent</h3>
                                <?php
                                } else if ($err=="error_nom"){ ?>
                                    <h3>Nom ou nom de société invalide ou absent</h3>
                                <?php
                                } else if ($err=="error_num_tel"){ ?>
                                    <h3>Numéro de téléphone invalide ou absent</h3>
                                    <h3>Format : 0123456789</h3>
                                <?php
                                } else if ($err=="error_adresse"){ ?>
                                    <h3>Adresse invalide ou absent</h3>
                                <?php
                                } else if ($err=="error_mail"){ ?>
                                    <h3>Mail invalide ou absent</h3>
                                <?php
                                } else if ($err=="error_inscri"){ ?>
                                    <h3>Votre adresse mail est déjà utilisé.</h3>
                                <?php
                                } 
                            } ?>
                        <input type="submit" name="confirmer" class="confirmer action-button" value="Valider" />
                    </fieldset>
                </form>
            </div>
        </main>
    </body>
</html>