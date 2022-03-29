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
        AddMessage("Contul a fost șters!", "warning");
    }
    else
    {
        AddMessage("A apărut o eroare la ștergere!", "danger"); 
    }
}
else
{
 AddMessage("Ștergerea nu s-a putut efectua!", "danger"); 
}
header("Location: ../");
die();