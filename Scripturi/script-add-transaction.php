<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";


if (
    isset($_POST['transactionCurrency']) && isset($_POST['transactionBalance'])
    && isset($_POST['transactionType']) && isset($_POST['transferToAccount'])
    && isset($_POST['transactionMemo']) && isset($_POST['accountId'])
) {
    $transactionCurrency = $_POST['transactionCurrency'];
    $transactionBalance = $_POST['transactionBalance'];
    $transactionType = $_POST['transactionType'];
    $transferToAccount = $_POST['transferToAccount'];
    $transactionMemo = $_POST['transactionMemo'];
    $accountId = $_POST['accountId'];

    $q = 'SELECT * FROM accounts WHERE accountId = ?;';
    $values = array($accountId);
    if (!$result = QueryDatabase($conn, $q, $values)) {
        AddMessage("A apărut o eroare la porcesarea tranzactiei!", "danger"); //505
    } else {

        $data = mysqli_fetch_assoc($result);
        switch ($transactionCurrency) {
            case 'EUR':
                $balance = $data["amountEUR"];
                break;
            case 'USD':
                $balance = $data["amountUSD"];
                break;
            case 'RON':
                $balance = $data["amountRON"];
                break;
        }

        if ($balance < $transactionBalance && in_array($transactionType, ['Cheltuire', 'Transfer'])) {
            AddMessage("Suma insuficienta pentru aceasta tranzactie!", "warning"); //505
        } else {

            $query = "INSERT INTO transactions (transactionId, accountId, transactionBalance, 
            transactionCurrency, transactionType, transferToAccount, transactionMemo, transactionDate)
            VALUES (NULL, ?, ?, ?, ?, ?, ?, NOW())";
            $values = array();
            $values[] = $accountId;
            $values[] = $transactionBalance;
            $values[] = $transactionCurrency;
            $values[] = $transactionType;
            $values[] = $transferToAccount;
            $values[] = $transactionMemo;

            if (QueryDatabase($conn, $query, $values)) {
                AddMessage("Tranzactia a fost procesata cu success!", "success");
            } else {
                AddMessage("A apărut o eroare la porcesarea tranzactiei!", "danger");
            }
        }
    }
} else {
    AddMessage("A apărut o eroare la porcesarea tranzactiei!", "danger");
}
header("Location: ../?pagina=account&accountId={$accountId}");
die();
