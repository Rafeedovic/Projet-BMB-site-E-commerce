<?php
//Le programme récupére l'id dans la session est supprime l'utilisateur associer dans la base de données, puis supprime les produits associer s'il était fournisseur.
//Le programme supprime enfin les données de la session.
//F : 30/12/2022 15h32
include('../Load.php');
session_start();
$Mail = $_SESSION["user"]["Mail"];
$sql = "DELETE FROM `user` WHERE `Mail` = '$Mail'";
mysqli_query($con, $sql);
$ID = $_SESSION["user"]["ID"];
$sql2 = "DELETE FROM `produit` WHERE `Fournisseur`='$ID'";
mysqli_query($con, $sql2);
var_dump($_SESSION);
session_destroy();
header('location:../Accueil.php');
?>