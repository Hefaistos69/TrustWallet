<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";
if(isset($_GET['accountId']))
{
    $accountId = intval($_GET['accountId']);
    $query = "DELETE FROM accounts WHERE accountId = ?;";
    $values = array();
    $values[] = $accountId;

    if(QueryDatabase($conn, $query, $values))
    {
        AddMessage("Contul a fost sters!", "warning");//505
    }
    else
    {
        AddMessage("A aparut o eroare la stergere!", "danger"); //505
    }
}
else
{
 AddMessage("Stergerea nu s-a putut efectua!", "danger"); //505
}
header("Location: ../");
die();