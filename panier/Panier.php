<?php 
    /* La page de panier permet au client uniquement de géré son panier augmenté le quantité, supprimé des produit et "acheter" 
        F:30/12/2022 16:22
    */
    include('../Load.php');
    session_start();
    if(!empty($_SESSION["user"]["Type"])){ //On test si un utilisateur est déjà connecté et on récupére la valeur de son rôle. Tout les non-client son renvoyer à l'accueil
        $user = $_SESSION["user"]["Type"];
    } else {
        $user = 0;
    }
    if($user == 2 || $user == 3 || $user == 4){
        header('Location:../Accueil.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="../css_final/style.css">
        <meta charset="utf-8">
        <meta name="author" content="Louan Belval">
        <meta name="description" content="Page du panier du projet web : site d'achat-vente.">
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
                <a href="../Accueil.php">Accueil</a>
                <a href="../Magasin.php">Magasin</a>
                <a href="Panier.php" class="minor">Panier</a>
                <a href="../inscription/Inscription.php" class="minor">Inscription</a>
                <a href="../connection/Connection.php" class="minor">Connexion</a>
            </nav>
            <?php
                } elseif ($user ==1){
            ?>
            <nav >
                <!--La barre de navigation pour le client contient la page accueil, magasin, panier et profil-->
                <a href="../Accueil.php">Accueil</a>
                <a href="../Magasin.php">Magasin</a>
                <a href="Panier.php" class="minor">Panier</a>
                <a href="../profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==2){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de produit, liste des produit et profil-->
                <a href="../Accueil.php">Ajouter produit</a>
                <a href="../Magasin.php">Produits</a>
                <a href="../profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==3){
            ?>
            <nav >
                <!--La barre de navigation pour le gestionaire contient la page liste de fournisseur, liste des produit et profil-->
                <a href="../Accueil.php">Fournisseur</a>
                <a href="../Magasin.php">Produits</a>
                <a href="../profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } elseif ($user ==4){
            ?>
            <nav >
                <!--La barre de navigation pour le fournisseur contient la page ajout de fournisseur/client/gestionnaire/produit, liste des produit, liste des user et son profil-->
                <a href="../Accueil.php"> Accueil</a>
                <a href="../list_compte.html">Comptes usagers</a>
                <a href="../Magasin.php">Produits</a>
                <a href="../profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php } ?>
        </header>
        <main>
            <?php if($user==1){ ?>
            <div class="linky">
                <a href="Historique.php"> Historique d'achat</a>
            </div>
            <?php } ?>
            <div class="panier">
                <?php 
                    $sum_t = 0;
                    $sum_ttc = 0;
                    if (!isset($_SESSION['cart'])) { 
                        $_SESSION['cart'] = array();
                    }
                    $cart = $_SESSION['cart']; //On récupére la liste des ids es produit dans le panier et leur quantité associé
                    foreach($cart as $key => $value){ 
                        $id = intval($key);
                        $sql = "SELECT * FROM `produit` WHERE `ID` = '$id' "; //On récupére dans la base de donné les information du produit
                        $result = mysqli_query($con,$sql);
                        $row = mysqli_fetch_assoc($result);
                        $nom= $row["Nom"]; 
                        $prix = $row["Prix"];
                        $quantite = $row["Quantite"];
                        $url_image = $row["Url_image"];
                        $description = $row["Description"]; ?>
                        <div class="panier_produit">
                            <div class="description_panier_produit">
                                <h4> <?php echo $nom ?> </h4>
                                <div class="produit_description">
                                    <img src="<?php echo $url_image ?>" class="img" alt="Image représentant le produit décrit">
                                    <div class="description">
                                        <p> Disponible : <?php echo $quantite ?> </p>
                                        <p> <?php echo $description ?> </p>
                                    </div>
                                </div>
                                <hr>
                                <p class="price"><?php echo $prix ?> €</p>
                            </div>
                            <div class="count_panier_produit"> <!-- La quantité de chaque produit peut être changer avec les bouton + - afficher à leur droite -->
                                <div class="counter">
                                    <form method="POST" action="panier_gestion.php">
                                        <input type="hidden" name="ID" value = <?php echo $id ?>>
                                        <input type="hidden" name="Max" value = <?php echo $quantite ?>>
                                        <input type="hidden" name="Origine" value = "Panier.php">
                                        <input type="hidden" name="Action" value="add_to_cart">
                                        <button type="submit" class="button_counter_l" name="add_to_cart"> + </button>
                                    </form> 
                                    <p class="counter_value"> <?php echo $value ?> </p>
                                    <form method="POST" action="panier_gestion.php">
                                        <input type="hidden" name="ID" value = <?php echo $id ?>>
                                        <input type="hidden" name="Origine" value = "Panier.php">
                                        <input type="hidden" name="Action" value="remove_from_cart">
                                        <button type="submit" class="button_counter_r" name="remove_from_cart"> - </button>
                                    </form> 
                                </div>
                                <hr>
                                <hr>
                                <?php
                                    $sum_p = $prix * $value;
                                    $sum_t = $sum_p + $sum_t;
                                    $sum_ttc = round($sum_t*1.05,2);
                                ?>
                                <p class="counter_price"> <?php echo $sum_p ?>€ </p> <!-- On renvoie la somme par produit-->
                            </div>
                        </div>
                    <?php
                    } ?>
            </div>
            <hr>
            <p class ="tot"> Total : <?php echo $sum_t ?> €    Total ttc : <?php echo $sum_ttc ?> €</p> <!-- Le bouton delete_cart supprime le panier et payer ajoute le panier à la base de donné command(et supprime les élément de la base de donné produit)-->
            <form method="POST" action="panier_gestion.php">
                <input type="hidden" name="Action" value="delete_cart">
                <button type="submit" class="button_delp" name="delete_cart"> Supprimer </button>
            </form>
            <?php
            if($user==1){ ?>
                <form method="POST" action="panier_gestion.php">
                    <input type="hidden" name="Action" value="payer">
                    <button type="submit" class="button_buy" name="payer"> Acheter </button>
                </form> 
            <?php
            } else { ?>
                <form action="../inscription/Inscription.php">
                    <button type="submit" name="Inscription" class="button_buy"> Acheter </button>
                </form> 
            <?php
            } ?>
        </main>
        <footer>
            <a href="../Information.php">Information</a>
            <?php 
            if(!empty($_SESSION["user"]["Type"])) { ?>
                <a href="../Reclamation.php">Réclamation</a>
            <?php
            } ?>
        </footer>
    </body>
</html>