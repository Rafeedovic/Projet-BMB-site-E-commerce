<?php
    //Ce code permet de supprimer les panier, payer les panier, ajouté et supprimer des produit du panier.
    //F:30/12/2022 16h30
    session_start();
    include('../Load.php');
    if (!empty($_POST)){
        //formulaire envoyé
        //on vérifie que tous les champ requis sont remplis
        if ((isset($_POST["Action"]))){
            $action= $_POST["Action"];
            switch ($action) {
                case 'add_to_cart': //Ajoute un produit au panier ou augmente de un la quantité dans le panier
                    if (!isset($_SESSION['cart'])) { //créer le panier si il est absent
                        $_SESSION['cart'] = array();
                    }
                    $quantity = 1;
                    $max = intval($_POST['Max']); //récupére la quantité max du roduit
                    if (isset($_SESSION['cart'][$_POST['ID']])) { //si le produit est dans le panier ajoute un élément.
                        $quantity += $_SESSION['cart'][$_POST['ID']];
                        if ($max<$quantity){ //sauf si tous les exemplaire son déjà dans le panier.
                            $quantity = $max;
                        }
                    }
                    $_SESSION['cart'][$_POST['ID']] = $quantity;
                    $back = 'location:';
                    $back .= $_POST['Origine'];
                    header($back); //renvoi le client à sa position d'origine
                    break;
                case 'remove_from_cart': //retire un élément du produit du panier
                    $quantity = $_SESSION['cart'][$_POST['ID']];
                    $quantity = intval($quantity)-1;
                    if($quantity<=0){ //Si il n'y a plus d'exemplaire du produit dan le panier, le retire du panier.
                        unset($_SESSION['cart'][$_POST['ID']]);
                    } else {
                        $_SESSION['cart'][$_POST['ID']] = $quantity;
                    }
                    header('location:Panier.php');
                    break;
                case 'delete_cart': //Supprime le panier de la Session
                    unset($_SESSION['cart']);
                    header('location:Panier.php');
                    break;
                case 'payer': //"paye" le panier
                    $cart=$_SESSION['cart'];
                    $id_user=$_SESSION['user']['ID'];
                    $date = date("Y-m-d");
                    $id_user=stripcslashes($id_user);
                    $date=stripcslashes($date);
                    $id_user=mysqli_real_escape_string($con,$id_user);
                    $date=mysqli_real_escape_string($con,$date);
                    foreach($cart as $key => $value){ //ajoute chaque produit à la base de donné command avec les donnée correspondante.
                        $id = intval($key);
                        $sql = "SELECT * FROM `produit` WHERE `ID` = '$id' ";
                        $result = mysqli_query($con,$sql);
                        $row = mysqli_fetch_assoc($result);
                        $prix = $row["Prix"];
                        $stock = $row["Quantite"];
                        $stock = $stock - $value;
                        $quantity = $value;
                        $prix_f = $prix*$value;
                        $prix_ttc = $prix_f*1.05;
                        
                        //on traite les variable pour s'en servir dans la base de données
                        $id = stripcslashes($id);
                        $prix = stripcslashes($prix);
                        $prix_ttc = stripcslashes($prix_ttc);
                        $quantity = stripcslashes($quantity);
                        $prix_f = stripcslashes($prix_f);
                        $id = mysqli_real_escape_string($con,$id);
                        $prix = mysqli_real_escape_string($con,$prix);
                        $prix_ttc = mysqli_real_escape_string($con,$prix_ttc);
                        $quantity = mysqli_real_escape_string($con,$quantity);
                        $prix_f = mysqli_real_escape_string($con,$prix_f);

                        $sql = "INSERT INTO `commande` (`ID_user`, `ID_produit`,`Quantite`,`Prix_U`,`Prix_T`,`Prix_F`,`Date`) VALUES ('$id_user', '$id', '$quantity','$prix','$prix_f','$prix_ttc','$date')";
                        $result = mysqli_query($con, $sql);

                        $sql2 = "UPDATE `produit` set `Quantite`='$stock' WHERE `ID` ='$id'";
                        $result = mysqli_query($con, $sql2);
                    }
                    unset($_SESSION['cart']); //Supprime le panier de la Session
                    header('location:Panier.php');
                    break;
                default:
                    echo "ok";
                    # code...
                    break;
            }
        }
    }
?>