<?php
  include "Module/modul-functii.php";

  if(!Loggedin())
  {
    header("Location: Pagini/pagina-login.php");
  }
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
  <title>Pornire</title>
</head>
  <body>
    <h1 class="text-secondary">HEY</h1>

  <?php
    include_once "Module/modul-js.php";
    
  ?>
  </body>
</html>