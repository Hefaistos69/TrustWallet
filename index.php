<?php
session_start();

require_once "Module/modul-functii.php";
require_once "Module/modul-db.php";

$pagina = '';
if (isset($_GET['pagina']))
  if (in_array($_GET['pagina'], ['login', 'signup', 'pornire']))
    $pagina = $_GET['pagina'];
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


if (file_exists($fisier))
  include $fisier;
else
  include "Pagini/pagina-404.php";


?>