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

    //calling the db to get the current account's data
    $q = 'SELECT * FROM accounts WHERE accountId = ?;';
    $values = array($accountId);
    if (!$result = QueryDatabase($conn, $q, $values)) {
        AddMessage("A apărut o eroare la porcesarea tranzacției!", "danger");
        header("Location: ../?pagina=account&accountId={$accountId}");
        die();
    }

    $data = mysqli_fetch_assoc($result);
    $balance = 0;
    $currency = '';
    switch ($transactionCurrency) {
        case 'EUR':
            $balance = $data["amountEUR"];
            $currency = "amountEUR";
            break;
        case 'USD':
            $balance = $data["amountUSD"];
            $currency = "amountUSD";
            break;
        case 'RON':
            $currency = "amountRON";
            $balance = $data["amountRON"];
            break;
    }

    $sign = 1;
    if (in_array($transactionType, ['Cheltuire', 'Transfer'])) {

        $sign = -1;
        if ($balance < $transactionBalance) {
            AddMessage("Suma insuficientă pentru această tranzacție!", "warning");
            header("Location: ../?pagina=account&accountId={$accountId}");
            die();
        }
    }
    if ($transactionType == 'Transfer') {
        $query = "UPDATE accounts
                  SET {$currency} =  {$currency} + ?
                  WHERE accountId = ?;";
        $values = PrepareValues($transactionBalance, $transferToAccount);
        if (!QueryDatabase($conn, $query, $values)) {
            AddMessage("A apărut o eroare la porcesarea tranzacției!", "danger");
            header("Location: ../?pagina=account&accountId={$accountId}");
            die();
        }
    }
    $balance += $sign * $transactionBalance;

    //calling the db to change the balance to the current account
    $query = "UPDATE accounts
              SET {$currency} = ?
              WHERE accountId = ?";
    $values = PrepareValues($balance, $accountId);
    if (!QueryDatabase($conn, $query, $values)) {
        AddMessage("A apărut o eroare la porcesarea tranzacției!", "danger");
        header("Location: ../?pagina=account&accountId={$accountId}");
        die();
    }

    //calling the db to add the new transaction
    $query = "INSERT INTO transactions (transactionId, accountId, transactionBalance, 
            transactionCurrency, transactionType, transferToAccount, transactionMemo, transactionDate)
            VALUES (NULL, ?, ?, ?, ?, ?, ?, NOW())";

    $values = PrepareValues(
        $accountId,
        $transactionBalance,
        $transactionCurrency,
        $transactionType,
        $transferToAccount,
        $transactionMemo
    );

    if (QueryDatabase($conn, $query, $values)) {
        AddMessage("Tranzacția a fost procesată cu success!", "success");
    } else {
        AddMessage("A apărut o eroare la porcesarea tranzacției!", "danger");
    }
} else {
    AddMessage("A apărut o eroare la porcesarea tranzacției!", "danger");
}
header("Location: ../?pagina=account&accountId={$accountId}");
die();
