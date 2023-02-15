<?php
    /* Code permettant de modifier/créer/supprimer des produits et des comptes
    F : 28/12/2022 18h12 
    */
    session_start();
    include('Load.php');
    if (!empty($_POST)){
        //formulaire reçu
        //on vérifie que tous les champs requis sont remplis
        if ((isset($_POST["Action"]))){
            $action=$_POST["Action"];
            switch($action){
                case 'Mod_compte': //sert à modifier un compte ou a creer un
                    if(isset($_POST["ID"])){ //Si l'ID n'est pas définit dans le post, donc il s'agit d'une création de compte par un admin
                        $ID = $_POST["ID"];
                    }
                    $back = 'location:';
                    $back .= $_POST['Origine'];
                    //on verifie la conformité du formulaire.
                    if (empty($_POST["id_2"])){
                        $back .= '?error=error_prenom';
                        header($back);
                    }
                    else if (empty($_POST["id_1"])){
                        $back .= '?error=error_nom';
                        header($back);
                    }
                    else if ((empty($_POST["Num_tel"])) || (!mb_eregi("^([0])([0-9]){9}$", $_POST["Num_tel"]))){
                        $back .= '?error=error_num_tel';
                        header($back);
                    }
                    else if (empty($_POST["Adresse"])){
                        $back .= '?error=error_adresse';
                        header($back);
                    }
                    else if ((empty($_POST["Mail"])) || (!filter_var($_POST["Mail"], FILTER_VALIDATE_EMAIL))){
                        $back .= '?error=error_mail';
                        header($back);
                    }
                    else{
                        //le formulaire est complet, on verifie plus loin dans le code que l'adresse mail n'est pas usé par un autre compte
                        //on récupere les donneés en les protégeant
                        $id_1 = $_POST["id_1"];
                        $id_2 = $_POST["id_2"];
                        $Num_tel = $_POST["Num_tel"];
                        $Adresse = $_POST["Adresse"];
                        $Mail = $_POST["Mail"];
                        $Type = $_POST["Type"];
                        if(isset($_POST["ID"])){ //Si l'id est présent un nouveau mdp n'est pas nécessaire en revanche si elle est absente il s'agit d'une création de compte donc le mdp est nécessaire
                            if (strlen($_POST["new_pass"])>7){
                                $mdp = md5($_POST["new_pass"]);
                                $mdp = stripcslashes($mdp);
                                $mdp = mysqli_real_escape_string($con,$mdp);
                            }
                        } else {
                            if (strlen($_POST["new_pass"])>7){
                                $mdp = md5($_POST["new_pass"]);
                                $mdp = stripcslashes($mdp);
                                $mdp = mysqli_real_escape_string($con,$mdp);
                            } else {
                                $back .= '?error=error_cmp';
                                header($back);
                            }
                        }
                        //On traite les inputs pour pouvoir les utilisé dans la base de données sans probléme.
                        $id_2 = stripcslashes($id_2); 
                        $id_1 = stripcslashes($id_1); 
                        $Num_tel = stripcslashes($Num_tel); 
                        $Adresse = stripcslashes($Adresse); 
                        $Mail = stripcslashes($Mail);
                        $Type = stripcslashes($Type);

                        $id_2 = mysqli_real_escape_string($con, $id_2);  
                        $id_1 = mysqli_real_escape_string($con, $id_1);  
                        $Num_tel = mysqli_real_escape_string($con, $Num_tel);  
                        $Adresse = mysqli_real_escape_string($con, $Adresse);  
                        $Mail = mysqli_real_escape_string($con, $Mail);
                        $Type = mysqli_real_escape_string($con, $Type);

                        //On traite le mail pour éviter les @ dans la base de données
                        $Mail = str_replace("@","[a]", $Mail);
                        //On test si le mail est déjà utilisé
                        $sql = "SELECT * FROM `user` WHERE `Mail` = '$Mail'";  
                        $result = mysqli_query($con, $sql); 
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        $count = mysqli_num_rows($result);
                        if(isset($_POST["ID"])){
                            if (($count==0)||($count==1 && $row["ID"]==$ID)) { //Si un id est présent on vérifie que le mail entré n'est pas dans la base de données ou dans celui d'origine
                                if(isset($mdp)){
                                    $sql = "UPDATE `user` SET `id_1`='$id_1',`id_2`='$id_2',`Num_tel`='$Num_tel',`Adresse`='$Adresse',`Mail`='$Mail',`Passworld`='$mdp',`Type`='$Type' WHERE `ID`='$ID'";
                                    $result = mysqli_query($con,$sql);
                                    header($back);
                                } else { //si le mdp n'as pas était changer on execute une instruction différente pour éviter de modifier le mdp stocker.
                                    $sql = "UPDATE `user` SET `id_1`='$id_1',`id_2`='$id_2',`Num_tel`='$Num_tel',`Adresse`='$Adresse',`Mail`='$Mail',`Type`='$Type' WHERE `ID`='$ID'";
                                    $result = mysqli_query($con,$sql);
                                    header($back);
                                }
                            } else { 
                                $back .= '?error=error_inscri';
                                header($back);
                            }
                        } else { // Si l'id est absent on vérifie que le mail n'est pas déjà dans la base de données puis on ajoute le nouveau compte.
                            if (($count==0)) {
                                $sql = "INSERT INTO `user` (`Mail`,`id_1`,`id_2`,`Num_tel`,`Adresse`,`Passworld`,`Type`) VALUES ('$Mail','$id_1','$id_2','$Num_tel','$Adresse','$mdp','$Type')";
                                $result = mysqli_query($con, $sql);
                                header($back);
                            } else {
                                $back .= '?error=error_inscri';
                                header($back);
                            }
                        }
                    }
                    header($back);
                    break;
                case 'Sup_compte': //L'instrution récupére l'ID poster et supprime l'user associer dans la base de données ainsi que tout les produits lié à l'user(si il était fournisseur)
                    $ID = $_POST["ID"];
                    $sql = "DELETE FROM `user` WHERE `ID`='$ID'";
                    mysqli_query($con, $sql);
                    $sql2 = "DELETE FROM `produit` WHERE `Fournisseur`='$ID'";
                    mysqli_query($con, $sql2);
                    header('location:Accueil.php');
                    break;
                case 'Mod_produit': //sert à modifier un produit ou a creer un
                    if(isset($_POST["ID"])){ //Si l'id est dans le post il s'agit d'une modification
                        $ID = $_POST["ID"];
                    }
                    $back = 'location:';
                    $back .= $_POST['Origine'];
                    //on verifie la conformité du formulaire.
                    if (empty($_POST["Nom"])){
                        $back .= '?error=error_nom';
                        header($back);
                    }
                    else if (empty($_POST["Prix"])){
                        $back .= '?error=error_prix';
                        header($back);
                    }
                    else if (empty($_POST["Quantity"]) || intval($_POST["Quantity"])<=0){
                        $back .= '?error=error_Quantity';
                        header($back);
                    }
                    else{
                        //le formulaire est complet
                        //on récupere les donneés en les protégeant
                        $Nom = $_POST["Nom"];
                        $Prix = $_POST["Prix"];
                        $Prix = floatval($Prix);
                        if(!isset($Prix)){
                            $Prix =0.0;
                        }
                        $Quantity = $_POST["Quantity"];
                        $Url_image = $_POST["Url_image"];
                        $Mea = $_POST["Mea"];
                        $Categorie = $_POST["Categorie"];
                        $Description = $_POST["Description"];
                        $Fournisseur = $_POST["Fournisseur"];
                        //On traite les input pour pouvoir les utilisées dans la base de données.
                        $Nom = stripcslashes($Nom); 
                        $Prix = stripcslashes($Prix); 
                        $Url_image = stripcslashes($Url_image); 
                        $Mea = stripcslashes($Mea); 
                        $Categorie = stripcslashes($Categorie);
                        $Description = stripcslashes($Description);
                        $Fournisseur = stripcslashes($Fournisseur);
                        $Quantity = stripcslashes($Quantity);

                        $Nom = mysqli_real_escape_string($con, $Nom);  
                        $Prix = mysqli_real_escape_string($con, $Prix);  
                        $Url_image = mysqli_real_escape_string($con, $Url_image);  
                        $Mea = mysqli_real_escape_string($con, $Mea);  
                        $Categorie = mysqli_real_escape_string($con, $Categorie);
                        $Description = mysqli_real_escape_string($con, $Description);
                        $Fournisseur = mysqli_real_escape_string($con, $Fournisseur);
                        $Quantity = mysqli_real_escape_string($con, $Quantity);

                        //On test si le nom est déjà utilisé
                        $sql = "SELECT * FROM `produit` WHERE `Nom` = '$Nom'";  
                        $result = mysqli_query($con, $sql); 
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        $count = mysqli_num_rows($result);
                        if(isset($_POST["ID"])){
                            if (($count==0)||($count==1 && $row["ID"]==$ID)) { //Si un id est présent on vérifie que le nom enntré n'est pas dans la base de données ou est celui d'origine
                                $sql = "UPDATE `produit` SET `Quantite`='$Quantity', `Nom`='$Nom',`Prix`='$Prix',`Url_image`='$Url_image',`Mea`='$Mea',`Categorie`='$Categorie',`Description`='$Description',`Fournisseur`='$Fournisseur' WHERE `ID`='$ID'";
                                    $result = mysqli_query($con,$sql);
                                    header($back);
                            } else {
                                $back .= '?error=error_nom';
                                header($back);
                            }
                        } else {
                            if (($count==0)) { //sinon on vérifie que le nom n'est pas dans la base de données. 
                                $sql = "INSERT INTO `produit` (`Nom`,`Prix`,`Url_image`,`Mea`,`Categorie`,`Description`,`Fournisseur`,`Quantite`) VALUES ('$Nom','$Prix','$Url_image','$Mea','$Categorie','$Description','$Fournisseur','$Quantity')";
                                $result = mysqli_query($con, $sql);
                                header($back);
                            } else {
                                $back .= '?error=error_nom';
                                header($back);
                            }
                        }
                    }
                    header($back);
                    break;
                case 'Sup_produit': //L'instrution récupére l'ID poster et supprime le produit associer dans la base de données
                    $ID = $_POST["ID"];
                    $sql = "DELETE FROM `Produit` WHERE `ID`='$ID'";
                    mysqli_query($con, $sql);
                    header('location:Accueil.php');
                    break;
            }
        }
    }
?>