<?php
//Code serant à l'authentification de l'utilisateur
//F:30/12/2022 16h55
include('../Load.php');
$error="";
if (!empty($_POST)){
    //formulaire envoyé
    //on vérifie que tous les champ requis sont remplis
    if ((isset($_POST["mail"],$_POST["pass"]))){
        if ((empty($_POST["mail"])) || (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL))){
            header('Location:Connection.php?error=error_mail');
        } else if ((empty($_POST["pass"]))) {
            header('Location:Connection.php?error=error_pass');
        }
        else{
            //le formulaire est complet
            //on récupere les donneés en les protégeant
            //init 
            $mail = $_POST['mail'];  
            $pass = $_POST['pass'];  

            //On traite les données pour pouvoir les utilisé dans la base de données
            $mail = stripcslashes($mail);  
            $pass = stripcslashes($pass);  

            $mail = mysqli_real_escape_string($con, $mail);  
            $pass = mysqli_real_escape_string($con, $pass); 

            //On traite le mail pour évité les @ dana la base de donné
            $mail = str_replace("@","[a]", $mail);

            //On vérifie si l'email est dans la base de données
            $sql = "SELECT * FROM `user` WHERE `Mail` = '$mail'";  
            $result = mysqli_query($con, $sql); 
            $count = mysqli_num_rows($result);

            if($count==1){
                $user = mysqli_fetch_assoc($result);
                //On vérifie le MDP
                if (md5($pass)==$user["Passworld"]){
                    echo "Marhbe";
                    //ouverture de la session
                    session_start();

                    //On stock les données du user
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
                } else {
                    header('Location:Connection.php?error=error_pass');
                }
            } else {
                header('Location:Connection.php?error=error_inscri');
            }
        }
    }
} ?>