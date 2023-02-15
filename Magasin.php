<?php 
    /* La page de magasin permet de réaliser les action lier au magasin ou à la liste des poduits pour chaque utilisateur :
        -Le client voit la page de magasin(nom des produit, prix, stock, description) et à un bouton d'achat(ajout à son panier).
        -le visiteur voit la page de magasin(nom des produit, prix, stock, description) met n'as pas de bouton d'achat.
        -Le gestionnaire voit la liste des produit et à un bouton d'édition(page édition de produit) où il peut modifier toute les donné du produit.
        -Le fournisseur voit la liste des produit dont il est fournisseur et à un bouton d'édition(page édition de produit) où il peut modifier toute les donné du produit sauf le fournisseur et la mise en avant.
        -L'admin voit la liste des produit et à un bouton d'édition(page édition de produit) où il peut modifier toute les donné du produit.
        F : 28/12/2022 22:13
    */
    include('Load.php');
    session_start();
    //On test si un utilisateur est déjà connecté et on récupére la valeur de son rôle.
    if(!empty($_SESSION["user"]["Type"])){
        $user = $_SESSION["user"]["Type"];
    } else {
        $user = 0;
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page listant les produits du projet web : site d'achat-vente.">
        <title>Magasin PW</title>
    </head>
    <body>
        <header>
            <!--Contient un text et la barre de navigation.-->
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
                <!--La barre de navigation pour le fournisseur contient la page ajout de produit, liste des produit et profil-->
                <a href="Accueil.php">Ajouter produit</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==3){
            ?>
            <nav >
                <!--La barre de navigation pour le gestionaire contient la page liste de fournisseur, liste des produit et profil-->
                <a href="Liste_user.php">Fournisseur</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==4){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de fournisseur/client/gestionnaire/produit, liste des produit, liste des user et son profil-->
                <a href="Accueil.php"> Accueil</a>
                <a href="Liste_user.php">Comptes usagers</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php } ?>
        </header>
        <main>
            <?php
                $sql = "SELECT * FROM `produit` WHERE 1 ";
                if (isset($_POST["Prix"]) && $_POST["Prix"]!=""){ //On regarde si un prix max à était indiqué
                    $max_prix = $_POST["Prix"];
                    $max_prix = stripcslashes($max_prix);
                    $max_prix = mysqli_real_escape_string($con,$max_prix);
                    $sql .= "AND `Prix` < '$max_prix' ";
                } 
                if (isset($_POST["Categorie"]) && $_POST["Categorie"]!=""){ // On regarde si une categorie à était indiqué
                    $Categorie=$_POST["Categorie"];
                    $Categorie = stripcslashes($Categorie);
                    $Categorie = mysqli_real_escape_string($con,$Categorie);
                    $sql .= "AND `Categorie` = '$Categorie' ";
                }

                if($user==2){ //On récupére la liste des produit, si l'utilisateur est fournisseur on ne récupére que ces propres produits.
                    $cache = $_SESSION['user']['ID'];
                    $sql .= "AND `Fournisseur` = '$cache' ";
                    $result = mysqli_query($con,$sql);
                }

                if (isset($_POST["Trie"]) && $_POST["Trie"]!=""){ //On regarde si un type de trie à était indiqué
                    $trie = $_POST["Trie"];
                    if ($trie =="croissant"){
                        $sql .= "ORDER BY `Prix` ";
                    } elseif($trie =="decroissant"){
                        $sql .= "ORDER BY `Prix` DESC ";
                    } elseif($trie =="alphabet"){
                        $sql .= "ORDER BY `Nom` ";
                    }
                } ?>
                <form action="Magasin.php" method="POST" class="triage">
                    <p> Prix max :</p><input type="text" name="Prix" placeholder="Prix Max" value='' />
                    <p> Categorie :</p>
                    <select name="Categorie" id="type-select">
                        <option value =""> --NO choice-- </option>
                        <?php
                            $sql3 = "SELECT * FROM `produit` GROUP BY `Categorie`";
                            $result3 = mysqli_query($con,$sql3);
                            while($row3 = mysqli_fetch_assoc($result3)){ ?>
                                <option value ="<?php echo $row3["Categorie"] ?>"> <?php echo $row3["Categorie"] ?> </option>
                            <?php
                            } ?>
                    </select>
                    <p> Trier par :</p>
                    <select name="Trie" id="type-select">
                        <option value =""> --NO choice-- </option>
                        <option value ="croissant"> Prix croissant </option>
                        <option value ="decroissant"> Prix decroissant </option>
                        <option value ="alphabet"> Ordre alphabetique </option>
                    </select>
                    <input type="submit" name="confirmer" class="confirmer action-button" value="Valider" />
                </form>
                </br>
            <div class="list_produit">
                <?php
                $result = mysqli_query($con,$sql);
                if($result){
                    while($row = mysqli_fetch_assoc($result)){ //On affiche les information dans une case et on recommence pour chaque produit
                        $id = $row["ID"];
                        $nom= $row["Nom"];
                        $prix = $row["Prix"];
                        $quantite = $row["Quantite"];
                        $url_image = $row["Url_image"];
                        $description = $row["Description"];
                        $fournisseur_id = $row["Fournisseur"];
                        $mea = $row["Mea"];
                        $sql2 = "SELECT `id_1` FROM `user` WHERE `ID`='$fournisseur_id'";
                        $result2 = mysqli_query($con,$sql2);
                        $row2 = mysqli_fetch_assoc($result2);
                        $fournisseur = $row2["id_1"];
                        ?>
                        <div class="produit_case">
                            <h4> <?php echo $nom ?> </h4>
                            <div class="produit_description">
                                <img src="<?php echo $url_image ?>" class="img" alt="Image représentant le produit décrit"/>
                                <div class="description">
                                    <?php //On affiche les information que chaque utilisateur à le droit de voir(en fonction de leurs rôles)
                                    if($user==1 || $user==0){ ?>
                                        <p> <?php echo $description ?> </p>
                                    <?php 
                                    } elseif($user==2){ ?>
                                        <p> Stock : <?php echo $quantite ?> </p>
                                        <p> <?php echo $description ?> </p>
                                    <?php
                                    } elseif ($user==3 || $user==4){ ?>
                                        <p> Fournisseur : <?php echo $fournisseur ?> </p>
                                        <p> Mise en avant : <?php echo $mea ?> </p>
                                        <p> Stock : <?php echo $quantite ?> </p>
                                        <p> <?php echo $description ?> </p>
                                    <?php
                                    } ?>
                                </div>
                            </div>
                            <hr>
                            <p class="price"><?php echo $prix ?> €</p>
                            <?php
                            if($user==1 || $user==0){ //Si l'utilisateur est client on envoie un post à panier_gestion pour ajouter le produit au panier?>
                                <form method="POST" action="panier/panier_gestion.php"> 
                                    <input type="hidden" name="ID" value = <?php echo $id ?>>
                                    <input type="hidden" name="Max" value = <?php echo $quantite ?>>
                                    <input type="hidden" name="Origine" value = "../Magasin.php">
                                    <input type="hidden" name="Action" value="add_to_cart">
                                    <button type="submit" class="button_liste" name="add_to_cart">Ajouter au panier</button>
                                </form>  
                            <?php
                            } elseif($user==2 || $user==3 || $user==4){ //sinon on envoie l'utilisateur sur l'éditeur de produit avec l'id du produit en post. ?>
                                <form method="POST" action="Produit_edit.php">
                                    <input type="hidden" name="ID" value = <?php echo $id ?>>
                                    <button type="submit" class="button_liste" name="edit_produit">Editer</button>
                                </form>  
                            <?php
                            } ?>
                        </div>
                    <?php
                    }
                } ?>
            </div>
        </main>
        <?php
            if(!empty($_SESSION["user"]["Type"])){ ?>
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
