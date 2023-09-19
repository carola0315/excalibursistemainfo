<?php

    $host = 'localhost';
    $user = 'root';
    $password = "";
    $base_datos= 'lao_excalibur';

    $conection = @mysqli_connect($host,$user,$password,$base_datos);

    if(!$conection){
        echo "Error en la conexión";
    }
    
?>