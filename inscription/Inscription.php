<?php 
    /* La page d'inscription permet de créer son profil client ou fournisseur ajouté à la base de donné user. Les utilisateur non visiteur sont rediriger vers la page d'accueil
        F:30/12/2022 16:47
    */
    session_start();
    if(!empty($_SESSION["user"]["Type"])){
        header('Location:../Accueil.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="../css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page d'inscription(pour les utilisateurs : visiteur) du projet web">
        <title>Magasin PW</title>
    </head>
    <body>
        <main id="form_creation">
            <div class="fprofil">
                <nav>
                    <a href="../Accueil.php">← Accueil  </a>
                </nav>
                <form action="insertion.php" method="POST">
                    <h2>Inscription</h2>
                    <p> Déjà inscrit ?  </br> <a href="../connection/Connection.php"> >Connectez-vous< </a>
                    <?php
                    if (!empty($_SESSION["cart"])){ ?>
                        <h3 class="fs-title" style="text-align : center"> Vous devez vous inscrire pour continuer vos achats ! </h3>
                    <?php 
                    } ?>
                    <fieldset>
                        <select name="Type">
                            <option value ="1"> Client </option>
                            <option value ="2"> Fournisseur </option>
                        </select>
                        </br>
                        <input type="text" name="id_1" placeholder="Nom ou nom de société" />
                        </br>
                        <input type="text" name="id_2" placeholder="Prénom ou numéro de siret" />                        
                        </br>
                        <input type="text" name="Mail" placeholder="Adresse mail" />
                        </br>
                        <input type="text" name="Num_tel" placeholder="Numéro de téléphone" />
                        </br>
                        <input type="text" name="Adresse" placeholder="Adresse" />    
                        </br>
                        <input type="password" name="Passworld" placeholder="Mot de passe" />
                        </br>
                        <input type="password" name="cmp" placeholder="Confirmez votre mot de passe" />
                        </br>
                        <input type="submit" name="confirmer" value="Confirmer" />
                        </br>
                        <?php
                        if (isset($_GET['error'])){
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
                                <h3>8 caractères au minimum</h3>
                            <?php
                            } else if ($err=="error_cmp"){ ?>
                                <h3>les mots de passes ne sont pas identique</h3>
                            <?php
                            } else if ($err=="error_inscri"){ ?>
                                <h3>Votre adresse mail est déjà utilisée.</h3>
                            <?php
                            } 
                        } ?>
                    </fieldset>
                </form>
            </div>
        </main>
    </body>
</html>