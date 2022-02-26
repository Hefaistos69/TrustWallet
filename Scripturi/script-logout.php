<?php
session_start();
include "../Module/modul-functii.php";

if($userId = Loggedin())
{
  unset($_SESSION['userId']);
}
header("Location: ../");
die();