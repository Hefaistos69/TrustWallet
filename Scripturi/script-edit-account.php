<?php

session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";

if (
    isset($_POST['accountName']) && isset($_POST['accountType'])
    && isset($_POST['accountId'])
) {
    $accountId = intval($_POST['accountId']);
    $values[] = $_POST['accountName'];
    $values[] = $_POST['accountType'];
    $values[] = $_POST['accountId'];
    $query = "UPDATE accounts 
              SET accountName = ?, accountType = ?
              WHERE accountId = ?;";

    if (QueryDatabase($conn, $query, $values)) {
        AddMessage("Contul a fost modificat!", "success");
    } else {
        AddMessage("A aparut o eroare la modificarea contului!", "danger");
    }
} else {
    AddMessage("Nu se poate modifica contul!", "danger");
}
header("location: ../?pagina=account&accountId={$accountId}");
die();
