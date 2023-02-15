<?php      
    //Code servant à ce connecté à la base de données.
    //F : 28/12/2022 20:18
    $host = "localhost";  
    $user = "root";  
    $password = '';  
    $db_name = "site_1";  
    $con = mysqli_connect($host, $user, $password, $db_name);  
    if(mysqli_connect_errno()) {  
        die("Failed to connect with MySQL: ". mysqli_connect_error());  
    }  
?>  