<?php 
    /* La page de profil permet d'éditer/supprimer son profil quelque soit l'utilisateur tand qu'il n'est pas visiteur 
        F:30/12/2022 15h27
    */
    include('../Load.php');
    session_start();
    if(!empty($_SESSION["user"]["id_2"])){ //On récupére les informations de la session
        $id_2 = $_SESSION["user"]["id_2"];  
        $id_1 = $_SESSION["user"]["id_1"];    
        $Num_tel = $_SESSION["user"]["Num_tel"];  
        $Adresse = $_SESSION["user"]["Adresse"];  
        $Mail = $_SESSION["user"]["Mail"];
        $user = $_SESSION["user"]["Type"]; 

        //On traite mail pour lui rendre son @
        $Mail = str_replace("[a]","@", $Mail);
    } else {
        header('Location:../Accueil.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="../css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page du profil (pour les utilisateurs : client) du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <main id="form_edit">
            <div class="fprofil">
                <nav id="form_nav">
                    <a href="../Accueil.php" id="form_nav">← Accueil  </a>
                </nav>
                </br>
                <form action="modification.php" method="POST">
                    <div class="formulaire_ajout_casier">
                        <div class="formulaire_ajout_case">
                            <?php
                            if ($user == 2){ //Si l'utilisateur est un fournisseur on privilége le nom de société et le numéro du siret plutôt que le nom prénom.?>
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
                        </div>
                        <div class="formulaire_ajout_case">
                            <input type="text" name="id_1" placeholder="id_1" value='<?php echo $id_1 ?>' />
                            <input type="text" name="id_2" placeholder="id_2" value='<?php echo $id_2 ?>' />
                            <input type="text" name="Num_tel" placeholder="Num_tel" value='0<?php echo $Num_tel ?>' />
                            <input type="text" name="Adresse" placeholder="Adresse" value='<?php echo $Adresse ?>' />
                            <input type="text" name="Mail" placeholder="Mail" value='<?php echo $Mail ?>' />
                        </div>
                    </div>
                    <fieldset>
                        <input type="password" name="old_pass" placeholder="Ancien mot de passe" />
                        <input type="password" name="new_pass" placeholder="Nouveau mot de passe" />
                        <br>
                        <?php
                        if (isset($_GET['error'])){ //On affiche les erreurs éventuelle
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
                            } else if ($err=="error_pass"){ ?>
                                <h3>Mot de passe invalide ou absent</h3>
                                <h3>8 caractères au minimum pour le nouveau mot de passe</h3>
                            <?php
                            } else if ($err=="error_inscri"){ ?>
                                <h3>Votre adresse mail est déjà utilisée.</h3>
                            <?php
                            } 
                        } ?>
                        <input type="submit" name="confirmer" class="confirmer action-button" value="Appliquer les changement" />
                        <a href="destruct.php"><input type="button" name="supp" class="Supprimer action-button" value="Supprimer le compte" /></a>
                    </fieldset>
                </form>
            </div>
            </br></br></br></br></br></br></br></br></br>
        </main>
        <?php
            if(!empty($_SESSION)){ ?>
                <div id="disconect">
                    <fieldset>
                        <a href="../deconnexion.php"><input type="button" name="disconect" class="disconect_buton" value="Déconnexion" id="disconect"/></a>
                    </fieldset>
                </div>
            <?php
            } ?>
    </body>
</html>