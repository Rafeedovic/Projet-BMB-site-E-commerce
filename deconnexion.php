<?php
    /*code provoquant la déconnexion et le retour à la page d'accueil
    F : 28/12/2022 17h36
    */
    session_start();
    var_dump($_SESSION);
    session_destroy();
    header('location:Accueil.php')
?>