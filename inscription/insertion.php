<?php
//Ajoute un utilisateur à la base de donné utilisateur.
// On différencie insertion.php et edit.php pour évité que les programme admin soit accessible à un client.
//F:30/12/2022 16:32
include('../Load.php');
if (!empty($_POST)){
    //formulaire envoyé
    //On vérifie que tous les champs requis sont remplis
    if ((isset($_POST["Type"],$_POST["id_1"],$_POST["id_2"],$_POST["Mail"],$_POST["Num_tel"],$_POST["Adresse"],$_POST["Passworld"],$_POST["cmp"]))){        
        if (empty($_POST["id_2"])){
            header('Location:Inscription.php?error=error_prenom');
        }
        else if (empty($_POST["id_1"])){
            header('Location:Inscription.php?error=error_nom');
        }
        else if ((empty($_POST["Num_tel"])) || (!mb_eregi("^([0])([0-9]){9}$", $_POST["Num_tel"]))){
            header('Location:Inscription.php?error=error_num_tel');
        }
        else if (empty($_POST["Adresse"])){
            header('Location:Inscription.php?error=error_adresse');
        }
        else if ((empty($_POST["Mail"])) || (!filter_var($_POST["Mail"], FILTER_VALIDATE_EMAIL))){
            header('Location:Inscription.php?error=error_mail');
        }
        else if ((empty($_POST["Passworld"])) || (strlen($_POST["Passworld"])<7)){
            header('Location:Inscription.php?error=error_pass');
        }
        else if ((empty($_POST["cmp"])) || ($_POST["cmp"]!=$_POST["Passworld"])){
            header('Location:Inscription.php?error=error_cmp');
        }
        else{
            //le formulaire est complet
            //on récupere les donneés en les protégeant
            $Type = $_POST['Type'];
            $id_2 = $_POST['id_2'];  
            $id_1 = $_POST['id_1'];  
            $Num_tel = $_POST['Num_tel'];  
            $Adresse = $_POST['Adresse'];  
            $Mail = $_POST['Mail'];  
            $Passworld = md5($_POST['Passworld']);

            //On traite les données pour pouvoir les utilisé dans la base de données
            $Type = stripcslashes($Type);
            $id_2 = stripcslashes($id_2); 
            $id_1 = stripcslashes($id_1); 
            $Num_tel = stripcslashes($Num_tel); 
            $Adresse = stripcslashes($Adresse); 
            $Mail = stripcslashes($Mail);  
            $Passworld = stripcslashes($Passworld);  

            $Type = mysqli_real_escape_string($con, $Type);
            $id_2 = mysqli_real_escape_string($con, $id_2);  
            $id_1 = mysqli_real_escape_string($con, $id_1);  
            $Num_tel = mysqli_real_escape_string($con, $Num_tel);  
            $Adresse = mysqli_real_escape_string($con, $Adresse);  
            $Mail = mysqli_real_escape_string($con, $Mail);  
            $Passworld = mysqli_real_escape_string($con, $Passworld);  
            
            //On traite le mail pour évité les @ dana la base de donné
            $Mail = str_replace("@","[a]", $Mail);

            //On test si le mail est déjà utilisé
            $sql = "SELECT * FROM `user` WHERE `Mail` = '$Mail'";  
            $result = mysqli_query($con, $sql); 
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
            $count = mysqli_num_rows($result);

            if($count==0){
                //On ajoute l'utilisateur
                $sql = "INSERT INTO `user` (`Mail`,`id_1`,`id_2`,`Num_tel`,`Adresse`,`Passworld`,`Type`) VALUES ('$Mail','$id_1','$id_2','$Num_tel','$Adresse','$Passworld','$Type')";
                $result = mysqli_query($con, $sql);
                session_start();

                $sql = "SELECT * FROM `user` WHERE `Mail` = '$Mail'";  
                $result = mysqli_query($con, $sql); 
                $count = mysqli_num_rows($result);
                if ($count == 1) {
                    //L'utilisateur existe
                    $user = mysqli_fetch_assoc($result);
                    
                    //On stock les données du user dans la Session
                    $_SESSION["user"] = [
                        "ID" => $user["ID"],
                        "Mail" => $user["Mail"],
                        "id_1" => $user["id_1"],
                        "id_2" => $user["id_2"],
                        "Num_tel" => $user["Num_tel"],
                        "Adresse" => $user["Adresse"],
                        "Type" => $user["Type"]
                    ];

                    //redirection vers la page d'accueil
                    header('Location:../Accueil.php');
                }
            } else{
                header('Location:Inscription.php?error=error_inscri');
            }
        }
    }
} ?>