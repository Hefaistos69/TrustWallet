<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";
if(isset($_GET['accountId']))
{
    //delete the account
    $accountId = intval($_GET['accountId']);
    $query = "DELETE FROM accounts WHERE accountId = ?;";
    $values = array();
    $values[] = $accountId;

    if(QueryDatabase($conn, $query, $values))
    {
        unset($_SESSION['selectedCurrency']);
        AddMessage("Contul a fost șters!", "warning");
    }
    else
    {
        AddMessage("A apărut o eroare la ștergere!", "danger"); 
    }

    //delete the transactions
    $query = " DELETE FROM transactions
    WHERE accountId NOT IN (
      SELECT accountId FROM accounts
    ) AND transferToAccount NOT IN(
      SELECT accountId FROM accounts
    );";
    if(!QueryDatabase($conn, $query))
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