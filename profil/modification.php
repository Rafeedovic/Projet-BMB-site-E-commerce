<?php
// On différencie modifictaion.php et edit.php pour évité que les programme admin soit accessible à un client.
// F : 30/12/2022 15h30
include('../Load.php');
if (!empty($_POST)){
    //formulaire envoyé
    //on vérifie que tous les champs requis sont remplis
    if(isset($_POST["id_2"],$_POST["id_1"],$_POST["Num_tel"],$_POST["Adresse"],$_POST["old_pass"],$_POST["new_pass"],$_POST["Mail"])){

        session_start();
        $prev_mail = $_SESSION["user"]["Mail"]; //On récupére le mail enregistrer à la base dans la session
        $sql = "SELECT * FROM `user` WHERE `Mail` = '$prev_mail'";  
        $result = mysqli_query($con, $sql);
        $user = mysqli_fetch_assoc($result); 
        $mdp = $user["Passworld"];
        $ID = $user["ID"];

        if (empty($_POST["id_2"])){ // On renvoie les erreus éventuelle
            header('Location:Profil.php?error=error_prenom');
        }
        else if (empty($_POST["id_1"])){
            header('Location:Profil.php?error=error_nom');
        }
        else if ((empty($_POST["Num_tel"])) || (!mb_eregi("^([0])([0-9]){9}$", $_POST["Num_tel"]))){
            header('Location:Profil.php?error=error_num_tel');
        }
        else if (empty($_POST["Adresse"])){
            header('Location:Profil.php?error=error_adresse');
        }
        else if ((empty($_POST["old_pass"])) || !(md5($_POST["old_pass"])==$mdp)){
            header('Location:Profil.php?error=error_pass');
        }
        else if ((empty($_POST["new_pass"])) || (strlen($_POST["new_pass"])<7)){
            header('Location:Profil.php?error=error_cmp');
        } 
        else if ((empty($_POST["Mail"])) || (!filter_var($_POST["Mail"], FILTER_VALIDATE_EMAIL))){
            header('Location:Profil.php?error=error_mail');
        }
        else{
            //le formulaire est complet
            //on récupere les donneés en les protégeant
            $id_2 = $_POST['id_2'];  
            $id_1 = $_POST['id_1'];  
            $Num_tel = $_POST['Num_tel'];  
            $Adresse = $_POST['Adresse'];  
            $Mail = $_POST['Mail'];  
            $Passworld = md5($_POST['new_pass']);

            //On traite les données pour pouvoir les utilisé dans la base de données
            $id_2 = stripcslashes($id_2); 
            $id_1 = stripcslashes($id_1); 
            $Num_tel = stripcslashes($Num_tel); 
            $Adresse = stripcslashes($Adresse); 
            $Mail = stripcslashes($Mail);  
            $Passworld = stripcslashes($Passworld);  

            $id_2 = mysqli_real_escape_string($con, $id_2);  
            $id_1 = mysqli_real_escape_string($con, $id_1);  
            $Num_tel = mysqli_real_escape_string($con, $Num_tel);  
            $Adresse = mysqli_real_escape_string($con, $Adresse);  
            $Mail = mysqli_real_escape_string($con, $Mail);  
            $Passworld = mysqli_real_escape_string($con, $Passworld);

            //On traite le mail pour évité les @ dans la base de données
            $Mail = str_replace("@","[a]", $Mail);

            //On test si le mail est déjà utilisé
            $sql = "SELECT * FROM `user` WHERE `Mail` = '$Mail'";  
            $result = mysqli_query($con, $sql); 
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $row2 = mysqli_fetch_array($result);
            $count = mysqli_num_rows($result);
            if (($count==0)||($count==1 && $row2["ID"]==$ID)) {
                //On met à jour la base de données
                $sql = "UPDATE `user` SET `id_1`='$id_1',`id_2`='$id_2',`Num_tel`='$Num_tel',`Adresse`='$Adresse',`Mail`='$Mail',`Passworld`='$Passworld' WHERE `ID`='$ID'";
                $result = mysqli_query($con,$sql);
                //On met à jour la session
                if ($result){
                    $_SESSION["user"]["id_2"]  = $id_2;  
                    $_SESSION["user"]["id_1"]  = $id_1;  
                    $_SESSION["user"]["Num_tel"]  = $Num_tel;  
                    $_SESSION["user"]["Adresse"]  = $Adresse;
                    $_SESSION["user"]["Mail"] = $Mail;

                    header('location:Profil.php');
                } else{
                    die(mysqli_error($con));
                }
            } else{
                header('Location:Profil.php?error=error_inscri');
            }
        }
    } else {
        header('location:Profil.php');
    }
}