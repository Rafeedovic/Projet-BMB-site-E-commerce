<?php 
    /* La page de connection permet de se connecté à son profil. Les utilisateur non visiteur sont rediriger vers la page d'accueil
        F:30/12/2022 16h52
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
        <meta name="description" content="Page de connection(pour les utilisateurs : visiteur) du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <main id="form_creation">
            <div class="fprofil">
                <nav>
                    <a href="../Accueil.php">← Accueil  </a>
                </nav>
                <form action="authentification.php" method="POST">
                    <fieldset>
                        <h2>Connexion</h2>
                        <p> Pas encore inscrit ?  </br> <a href="../inscription/Inscription.php"> >Inscrivez-vous< </a>
                        </br></br>
                        <input type="text" name="mail" placeholder="Adresse mail" />
                        <br>
                        <input type="password" name="pass" placeholder="Mot de passe" />
                        <br>
                        <?php
                        if (isset($_GET['error'])) {
                            $err = $_GET['error'];
                            if ($err == "error_mail") { ?>
                            <h3>Mail invalide </br></h3>
                            <?php
                            } else if ($err == "error_pass") { ?>
                            <h3>Mot de passe invalide </br></h3>
                            <?php
                            } else if ($err == "error_inscri"){ ?>
                            <h3>Vous n'etes pas encore inscrit </br></h3>
                            <?php
                            }
                        } ?>
                        <input type="submit" name="confirmer" value="Confirmer" />
                    </fieldset>
                </form>
            </div>
            </br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
        </main>
    </body>
</html>