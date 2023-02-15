<?php 
    /* La page d'information contient les informations légal du site
    F : 28/12/2022 18:17
    */
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page d'information général du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <header>
            <!--Contient le nom du site et la barre de navigation.-->
            <!--La barre de navigation pour la page d'information et pour la page de réclamation ne contient qu'un bouton retour.-->
            <div class="Head">
                <h1>BMB</h1>
            </div>
            <nav >
                <a href="javascript:history.go(-1)">Retour</a>
            </nav>
        </header>
        <main>
            <!--ici devra être placer un text d'information.-->
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
            <a href="information.php">Information</a>
            <?php 
            if(!empty($_SESSION["user"]["Type"])) { ?>
                <a href="Reclamation.php">Réclamation</a>
            <?php
            } ?>
        </footer>
    </body>
</html>