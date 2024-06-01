<?php

//Datos de conexion para MySQL

$host="localhost";

$bd="sitio";

$usuario="root";

$contrasenia="";

try {

    $conexion=new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);

    //if($conexion){echo "Conectado exitosamente ";}

} catch (Exeption $ex) {

    echo $ex->getMessage();
    
} ?>