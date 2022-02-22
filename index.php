<?php
session_start();

require_once "Module/modul-functii.php";
require_once "Module/modul-db.php";

$pagina = '';
$numePagina = '';
if (isset($_GET['pagina']))
  if (in_array($_GET['pagina'], ['login', 'signup', 'pornire'])){
    $pagina = $_GET['pagina'];
    switch($pagina){
      case 'login': 
        $numePagina = 'Autentificare';
        break;
      case 'signup':
        $numePagina = 'Inregistrare';
        break;
      case 'pornire':
        $numePagina = 'Pornire';
        break;
      default:
        $numePagina = 'Ce ma?';
        break;
    }
  }
  else
    $pagina = '404';



if ($pagina == '') {

  if (!Loggedin()) {
    $pagina = 'login';
  } else {
    $pagina = 'pornire';
  }
}

$fisier = "Pagini/pagina-{$pagina}.php";

ShowMessages();




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  include_once "Module/modul-css.php";
  ?>
  <title><?=$numePagina?></title>

</head>

<body>

  <?php
    if (file_exists($fisier))
      include $fisier;
    else
      include "Pagini/pagina-404.php";
  
    include_once "Module/modul-js.php";
  ?>
</body>
</html>