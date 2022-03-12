<?php
session_start();
include "../Module/modul-functii.php";

if(Loggedin())
{
  unset($_SESSION['userId']);
}
AddMessage("Te-ai deconectat cu succes!", "success");

header("Location: ../");
die();