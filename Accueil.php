<?php 
    /* La page d'accueil permet de réaliser les actions principal de chaque utilisateur :
        -Le client et le visiteur voit la page de mise en avant du magasin
        -Le fournisseur peut ajouté des produits
        -L'admin peut ajouté des usagers(client/fournisseur/gestionnaire) et des produits
        F : 28/12/2022 17:35   
    */
    //On test si un utilisateur est déjà connecté et on récupére la valeur de son rôle.
    include('Load.php');
    session_start();
    if(!empty($_SESSION["user"]["Type"])){
        $user = $_SESSION["user"]["Type"];
    } else {
        $user = 0;
    }
    if($user==3){ //On envoie le gestionnaire à une page où son rôle lui permet d'être
        header('Location:Liste_user.php');
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
            <nav class="re_flex">
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
            <nav class="re_flex">
                <!--La barre de navigation pour le client contient la page accueil, magasin, panier et profil-->
                <a href="Accueil.php">Accueil</a>
                <a href="Magasin.php">Magasin</a>
                <a href="panier/Panier.php" class="minor">Panier</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } else if ($user ==2){
            ?>
            <nav class="re_flex">
                <!--La barre de navigation pour le fournisseur contient la page ajout de produit, liste des produit et profil-->
                <a href="Accueil.php">Ajouter produit</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } else if ($user ==3){
            ?>
            <nav class="re_flex">
                <!--La barre de navigation pour le gestionaire contient la page liste de fournisseur, liste des produits et profil-->
                <a href="Liste_user.php">Fournisseur</a>
                <a href="Magasin.php">Produits</a>
                <a href="profil/Profil.php" class="minor">Profil</a>
            </nav>
            <?php
                } else if ($user ==4){
            ?>
            <nav class="re_flex">
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
                if ($user==0 || $user==1) { ?> <!-- L'utilisateur est un client ou un visiteur -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            include ("Load.php");
                            $sql = "SELECT * FROM `produit` WHERE `Mea`='1';";
                            $result = mysqli_query($con,$sql);
                            if ($result) {
                                $i = 0;
                                $ch = "";
                                $class = "carousel-item active";
                                while ($row = mysqli_fetch_assoc($result)) {
                                $url_image = $row["Url_image"];
                                $id = $row["ID"];
                                $nom= $row["Nom"];
                                $prix = $row["Prix"];
                                $quantite = $row["Quantite"];
                                $url_image = $row["Url_image"];
                                $description = $row["Description"];
                                echo '
                                    <div class="'.$class.'">
                                        <div class="PMea" style="background-image: url('.$url_image.')">
                                            <h4 class="Nom_Mea"> '.$nom.' </h4>
                                            <p class="Description_Mea"> '.$description.' </p>
                                            <p class="Price_Mea">'.$prix.' €</p>
                                            <form method="POST" action="panier/panier_gestion.php">
                                                <input type="hidden" name="ID" value = '.$id.' >
                                                <input type="hidden" name="Max" value = '.$quantite.'>
                                                <input type="hidden" name="Origine" value = "../Accueil.php">
                                                <input type="hidden" name="Action" value="add_to_cart">
                                                <button type="submit" class="Button_Mea" name="add_to_cart">Ajouter au panier</button>
                                            </form>
                                        </div>
                                    </div>';
                                $class = "carousel-item";
                                $i = $i + 1;
                                }
                            }
                            ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                        </a>
                    </div>
                <?php 
                } else if ($user==2){ ?> <!-- L'utilisateur est un fournisseur -->
                    <div class="formulaire_ajout">
                        <form action="edit.php" method="POST">
                            <fieldset> <!-- On créer un formulaire pour ajouter un produit à la base de données -->
                                <div class="formulaire_ajout_casier">
                                    <div class="formulaire_ajout_case">
                                        <P><b> Nom du produit :</b></p>
                                        <p><b> Prix : </b></p>
                                        <p><b> Quantité : </b></p>
                                        <p><b> Url de l'image : </b></p>
                                        <p><b> Categorie : </b></p>
                                        <p><b> Description : </b></p>
                                    </div>
                                    <div class="formulaire_ajout_case">
                                        <input type="hidden" name="Origine" value = "Accueil.php"/>
                                        <input type="hidden" name="Action" value = "Mod_produit"/>
                                        <input type="hidden" name ="Fournisseur" value = '<?php echo $_SESSION["user"]["ID"] ?>' />
                                        <input type="text" name="Nom" placeholder="Nom" value='' />
                                        <input type="text" name="Prix" placeholder="Prix" value='' />
                                        <input type="text" name="Quantity" placeholder="Quantity" value= '' />
                                        <input type="text" name="Url_image" placeholder="Url_image" value='' />
                                        <input type="text" name="Categorie" placeholder="Categorie" value='' />
                                        <input type="text" name="Description" placeholder="Description" value='' />
                                    </div>
                                </div>
                                <?php //On envoie le formulaire, s'il est incomplet ,une erreur est retourné sur la page
                                    if (isset($_GET['error'])){ ?>
                                        <h3> Formulaire incomplet ou incorrect </h3>
                                    <?php
                                    } ?>
                                <input type="submit" name="confirmer" class="confirmer action-button" value="Valider" />
                            </fieldset>
                        </form>
                    </div>
                <?php
                } else{ ?> <!-- L'utilisateur est un admin -->
                    <div class="formulaire_ajout_armoire">
                        <div class="formulaire_ajout">
                            <form action="edit.php" method="POST">
                                <fieldset> <!-- On créer un formulaire pour ajouté un produit à la base de données , ici la mise en avant est possible et l'on selectionne le fournisseur-->
                                    <h3>Ajouter un produit :</h3>
                                    <div class="formulaire_ajout_casier">
                                        <div class="formulaire_ajout_case">
                                            <P><b> Nom du produit :</b></p>
                                            <p><b> Prix : </b></p>
                                            <p><b> Quantité : </b></p>
                                            <p><b> Url de l'image : </b></p>
                                            <p><b> Categorie : </b></p>
                                            <p><b> Description : </b></p>
                                            <p><b> Fournisseur : </b></p>
                                            <p><b> Mise en avant : </b></p>
                                        </div>
                                        <div class="formulaire_ajout_case">
                                            <input type="hidden" name="Origine" value = "Accueil.php"/>
                                            <input type="hidden" name="Action" value = "Mod_produit"/>
                                            <input type="text" name="Nom" placeholder="Nom" value='' />
                                            <input type="text" name="Prix" placeholder="Prix" value='' />
                                            <input type="text" name="Quantity" placeholder="Quantity" value= '' />
                                            <input type="text" name="Url_image" placeholder="Url_image" value='' />
                                            <input type="text" name="Categorie" placeholder="Categorie" value='' />
                                            <input type="text" name="Description" placeholder="Description" value='' />
                                            <select name="Fournisseur" id="type-select">
                                                <?php
                                                $sql3 = "SELECT * FROM `user` WHERE `Type`='2'";
                                                $result3 = mysqli_query($con,$sql3);
                                                while($row3 = mysqli_fetch_assoc($result3)){ ?>
                                                    <option value ="<?php echo $row3["ID"] ?>"> <?php echo $row3["id_1"] ?> </option>
                                                <?php
                                                } ?>
                                            </select>
                                            <select name="Mea" id="type-select">
                                                    <option value ="0"> Non </option>
                                                    <option value ="1"> Oui </option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php //On envoie le formulaire, s'il est incomplet, une erreur est retourné sur la page
                                        if (isset($_GET['error'])){ ?>
                                            <h3> Formulaire incomplet ou incorrect</h3>
                                        <?php
                                        } ?>
                                    <input type="submit" name="confirmer" class="confirmer action-button" value="Valider" />
                                </fieldset>
                            </form>
                        </div>
                        <div class="formulaire_ajout">
                            <form action="edit.php" method="POST">
                                <fieldset> <!-- On créer un formulaire pour ajouter un utilisateur à la base de données-->
                                    <h3>Ajouter un utilisateur :</h3>
                                    <div class="formulaire_ajout_casier">
                                        <div class="formulaire_ajout_case_mini">
                                            <p><b> Nom ou nom de société : </b></p>
                                            <p><b> Prénom ou numéro de siret : </b></p>
                                            <p><b> Numéro de téléphone : </b></p>
                                            <p><b> Adresse : </b></p>
                                            <p><b> Mail : </b></p>
                                            <p><b> Type de compte : </b></p> 
                                            <p><b> Mots de passe : </b></p>
                                        </div>
                                        <div class="formulaire_ajout_case">
                                            <input type="hidden" name="Origine" value = "Accueil.php"/>
                                            <input type="hidden" name="Action" value = "Mod_compte"/>
                                            <input type="text" name="id_1" placeholder="id_1" value='' />
                                            <input type="text" name="id_2" placeholder="id_2" value='' />
                                            <input type="text" name="Num_tel" placeholder="Num_tel" value= '' />
                                            <input type="text" name="Adresse" placeholder="Adresse" value='' />
                                            <input type="text" name="Mail" placeholder="Mail" value='' />
                                            <select name="Type" id="type-select">
                                                <option value ="1"> Client </option>
                                                <option value ="2"> Fournisseur </option>
                                                <option value ="3"> Gestionaire </option>
                                                <option value ="4"> Admin </option>
                                            </select>
                                            <input type="password" name="new_pass" placeholder="mot de passe" />
                                        </div>
                                    </div>
                                    <br>
                                    <?php //On envoie le formulaire, s'il est incomplet, une erreur est retourné sur la page
                                        if (isset($_GET['error'])){ ?>
                                            <h3> Formulaire incomplet ou incorrect</h3>
                                        <?php
                                        } ?>
                                    <input type="submit" name="confirmer" class="confirmer action-button" value="Valider" />
                                </fieldset>
                            </form>
                        </div>
                    </div>
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
        <footer class="re_flex">
            <a href="Information.php">Information</a>
            <?php 
            if(!empty($_SESSION["user"]["Type"])) { ?>
                <a href="Reclamation.php">Réclamation</a>
            <?php
            } ?>
        </footer>
    </body>
</html>