<?php
session_start();
include "../Module/modul-functii.php";

if(Loggedin())
{
  session_unset();
}
AddMessage("Te-ai deconectat cu succes!", "success");

header("Location: ../");
die();