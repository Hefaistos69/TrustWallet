<?php
session_start();
include "../Module/modul-functii.php";
AddMessage("bla bla bla", "danger");
AddMessage("bla bla bla", "warning");
AddMessage("bla bla bla", "success");

header('Location: ../?pagina=login');
die();