<?php

include './class/config.php';
include './class/class_mysql.php';

$sql = Mysqli_Query::Eliminar('especialidades', "id='".$_GET['id']."'");
if($sql){
    echo 'Bien';
}

