<?php

include './class/config.php';
include './class/class_mysql.php';
include './class/class_paginador.php';

$pagina = $_GET['pagina'];

$p = new paginador();
$p->paginar("SELECT * FROM especialidades ",$pagina,4);

echo $p->tablaBootstrap();
