<?php
    /* Code permettant de creer des sujets de reclamation, d'ajouté des messages à un sujet, de clore le sujet.
    */
    session_start();
    include('Load.php');
    if (!empty($_POST)){
        //formulaire reçus
        //on vérifie que tous les champs requis sont remplis
        if ((isset($_POST["Action"]))){
            $action=$_POST["Action"];
            switch($action){
                case 'nouvelle_reclame' :
                    $back = 'Location:Reclamation.php';
                    $message = $_POST["message"];
                    $id_user = $_POST["id_user"];
                    $num_message = '0';
                    $sql = "SELECT * FROM `user` WHERE `ID`='$id_user'";
                    $result = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $type = $row["Type"];
                    if ($type==1){
                        $niv = '2';
                    } elseif ($type==2){
                        $niv = '0';
                    } elseif ($type==3){
                        $niv = '1';
                    }
                    $sql2 = "SELECT MAX(`id_reclame`) FROM `reclame` WHERE 1";
                    $result2 = mysqli_query($con, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                    $id_reclame = $row2["MAX(`id_reclame`)"] +1;

                    $message = stripcslashes($message);
                    $id_user = stripcslashes($id_user);
                    $id_reclame = stripcslashes($id_reclame);
                    $niv_reclame = stripcslashes($niv);
                    $num_message = stripcslashes($num_message);
                            
                    $message = mysqli_real_escape_string($con,$message);
                    $id_user = mysqli_real_escape_string($con,$id_user);
                    $id_reclame = mysqli_real_escape_string($con,$id_reclame);
                    $niv_reclame = mysqli_real_escape_string($con,$niv);
                    $num_message = mysqli_real_escape_string($con,$num_message);
                    
                    $sql3 = "INSERT INTO `reclame` (`id_user`,`id_reclame`,`niv_reclame`,`num_message`,`message`) VALUES ('$id_user','$id_reclame','$niv_reclame','$num_message','$message')";
                    $result3 = mysqli_query($con, $sql3);
                    header($back);
                    break;
                case 'reponse' :
                    $back = 'Location:Reclamation_messagerie.php';
                    $message = $_POST["message"];
                    $id_user = $_POST["id_user"];
                    $id_reclame = $_POST["id_conv"];
                    $niv_reclame = $_POST["niv_reclame"];
                    $sql = "SELECT MAX(`num_message`) FROM `reclame` WHERE `id_reclame`='$id_reclame'";
                    $result = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $num_message = $row["MAX(`num_message`)"] +1;

                    $message = stripcslashes($message);
                    $id_user = stripcslashes($id_user);
                    $id_reclame = stripcslashes($id_reclame);
                    $niv_reclame = stripcslashes($niv_reclame);
                    $num_message = stripcslashes($num_message);
                            
                    $message = mysqli_real_escape_string($con,$message);
                    $id_user = mysqli_real_escape_string($con,$id_user);
                    $id_reclame = mysqli_real_escape_string($con,$id_reclame);
                    $niv_reclame = mysqli_real_escape_string($con,$niv_reclame);
                    $num_message = mysqli_real_escape_string($con,$num_message);

                    $sql3 = "INSERT INTO `reclame` (`id_user`,`id_reclame`,`niv_reclame`,`num_message`,`message`) VALUES ('$id_user','$id_reclame','$niv_reclame','$num_message','$message')";
                    $result3 = mysqli_query($con, $sql3);
                    header($back);
                    break;
                case 'clos' :
                    $back = 'Location:Reclamation.php';
                    $message = $_POST["message"];
                    $id_user = $_POST["id_user"];
                    $id_reclame = $_POST["id_conv"];
                    $niv_reclame = 3;
                    $sql = "SELECT MAX(`num_message`) FROM `reclame` WHERE `id_reclame`='$id_reclame'";
                    $result = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $num_message = $row["MAX(`num_message`)"] +1;

                    $message = stripcslashes($message);
                    $id_user = stripcslashes($id_user);
                    $id_reclame = stripcslashes($id_reclame);
                    $niv_reclame = stripcslashes($niv_reclame);
                    $num_message = stripcslashes($num_message);
                            
                    $message = mysqli_real_escape_string($con,$message);
                    $id_user = mysqli_real_escape_string($con,$id_user);
                    $id_reclame = mysqli_real_escape_string($con,$id_reclame);
                    $niv_reclame = mysqli_real_escape_string($con,$niv_reclame);
                    $num_message = mysqli_real_escape_string($con,$num_message);

                    $sql3 = "INSERT INTO `reclame` (`id_user`,`id_reclame`,`niv_reclame`,`num_message`,`message`) VALUES ('$id_user','$id_reclame','$niv_reclame','$num_message','$message')";
                    $result3 = mysqli_query($con, $sql3);
                    header($back);
                    break;
            }
        }
    }
?>