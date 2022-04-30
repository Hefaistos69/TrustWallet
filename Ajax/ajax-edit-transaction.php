<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";

if (isset($_POST['editTransaction'])) {
  if (
    isset($_POST['transactionCurrency']) && isset($_POST['transactionBalance'])
    && isset($_POST['transactionType']) && isset($_POST['transferToAccount'])
    && isset($_POST['transactionMemo']) && isset($_POST['transactionId'])
  ) {

    $transactionCurrency = $_POST['transactionCurrency'];
    $transactionBalance = $_POST['transactionBalance'];
    $transactionType = $_POST['transactionType'];
    $transferToAccount = $_POST['transferToAccount'];
    $transactionMemo = $_POST['transactionMemo'];
    $transactionId = $_POST['transactionId'];
    if ($transactionType !== 'Transfer')
      $transferToAccount = '';
    //the initial values 
    $query = 'SELECT * FROM transactions WHERE transactionId = ?;';
    $values = PrepareValues($transactionId);
    $transactionData = null;
    if (!$result = QueryDatabase($conn, $query, $values)) {
      echo json_encode(array('success' => 0, 'error' => 'db'));
      die();
    } else {
      $transactionData = mysqli_fetch_assoc($result);
    }

    //undo transaction

    $currency = '';
    if (!$currency = CurrencyToAmount($transactionData['transactionCurrency'])) {
      echo (json_encode(array('success' => 0, 'error' => 'invalidData')));
      die();
    }

    //Get account balance
    $values = PrepareValues($transactionData['accountId']);
    $query = "SELECT amountRON, amountUSD, amountEUR FROM accounts WHERE accountId = ?;";
    if (!$result = QueryDatabase($conn, $query, $values)) {
      echo (json_encode(array('success' => 0, 'error' => 'db')));
      die();
    }
    $data = mysqli_fetch_assoc($result);

    //verify if the edit is possible
    $currentBalance = $data[$currency];
    $oldTransactionBalance = $transactionData['transactionBalance'];
    $newTransactionBalance = $transactionBalance;
    $sign = 1;
    if ($transactionData['transactionType'] == 'Depunere')
      $sign = -1;

    $currentBalance += $sign * $oldTransactionBalance;

    if ($transactionCurrency != $transactionData['transactionCurrency']) {
      if ($currentBalance < 0) {
        echo (json_encode(array('success' => 0, 'error' => 'noMoney')));
        die();
      } else {
        $temp = '';
        if (!$temp = CurrencyToAmount($transactionCurrency)) {
          echo (json_encode(array('success' => 0, 'error' => 'invalidData')));
          die();
        }

        $currentBalance = $data[$temp];
        unset($temp);
      }
    }

    $sign = 1;
    if ($transactionType != 'Depunere')
      $sign = -1;
    $currentBalance += $sign * $newTransactionBalance;
    if ($currentBalance < 0) {
      echo (json_encode(array('success' => 0, 'error' => 'noMoney')));
      die();
    }

    //Realocating the money
    if ($transactionData['transactionType'] == 'Depunere') {
      $data[$currency] -= $transactionData['transactionBalance'];
    } else if ($transactionData['transactionType'] == 'Cheltuire') {
      $data[$currency] += $transactionData['transactionBalance'];
    } else if ($transactionData['transactionType'] == 'Transfer') {
      //Get the other account balance
      $values = PrepareValues($transactionData['transferToAccount']);
      $query = "SELECT {$currency} FROM accounts WHERE accountId = ?;";
      if (!$result = QueryDatabase($conn, $query, $values)) {
        echo (json_encode(array('success' => 0, 'error' => 'db')));
        die();
      }
      $data2 = mysqli_fetch_assoc($result);
      if ($data2 != null) {
        $data2[$currency] -= $transactionData['transactionBalance'];
        if ($data2[$currency] < 0)
          $data2[$currency] = 0;

        //Update the other account balance
        $values = PrepareValues($data2[$currency], $transactionData['transferToAccount']);
        $query = "UPDATE accounts
            SET {$currency} = ?
            WHERE accountId = ?;";
        if (!QueryDatabase($conn, $query, $values)) {
          echo (json_encode(array('success' => 0, 'error' => 'db-update')));
          die();
        }
      }
      if ($data != null)
        $data[$currency] += $transactionData['transactionBalance'];
    }

    //Update account balance
    if ($data != null) {
      $values = PrepareValues($data[$currency], $transactionData['accountId']);
      $query = "UPDATE accounts
            SET {$currency} = ?
            WHERE accountId = ?;";
      if (!QueryDatabase($conn, $query, $values)) {
        echo (json_encode(array('success' => 0, 'error' => 'db-update')));
        die();
      }
    }
    unset($data);

    //redo the transaction with the new values

    //calling the db to get the current account's data
    $q = 'SELECT * FROM accounts WHERE accountId = ?;';
    $values = PrepareValues($transactionData['accountId']);
    if (!$result = QueryDatabase($conn, $q, $values)) {
      echo (json_encode(array('success' => 0, 'error' => 'db')));
      die();
    }

    $data = mysqli_fetch_assoc($result);
    $balance = 0;
    $currency = '';
    if (!$currency = CurrencyToAmount($transactionCurrency)) {
      echo (json_encode(array('success' => 0, 'error' => 'invalidData')));
      die();
    }
    $balance = $data[$currency];
    $sign = 1;
    if (in_array($transactionType, ['Cheltuire', 'Transfer'])) {

      $sign = -1;
    }
    if ($transactionType == 'Transfer') {
      $query = "UPDATE accounts
                  SET {$currency} =  {$currency} + ?
                  WHERE accountId = ?;";
      $values = PrepareValues($transactionBalance, $transferToAccount);
      if (!QueryDatabase($conn, $query, $values)) {
        echo (json_encode(array('success' => 0, 'error' => 'db')));
        die();
      }
    }
    $balance += $sign * $transactionBalance;

    //calling the db to change the balance to the current account
    $query = "UPDATE accounts
              SET {$currency} = ?
              WHERE accountId = ?";
    $values = PrepareValues($balance, $transactionData['accountId']);
    if (!QueryDatabase($conn, $query, $values)) {
      echo (json_encode(array('success' => 0, 'error' => 'db')));
      die();
    }

    //update the transaction
    $query = "UPDATE transactions
              SET transactionType = ?, transactionBalance = ?, transactionCurrency = ?, transferToAccount = ?, transactionMemo = ?
              WHERE transactionId = ?;";
    $values = PrepareValues($transactionType, $transactionBalance, $transactionCurrency, $transferToAccount, $transactionMemo, $transactionId);
    if (!QueryDatabase($conn, $query, $values)) {
      echo (json_encode(array('success' => 0, 'error' => 'db')));
      die();
    }

    echo (json_encode(array('success' => 1, 'accountId' => $transactionData['accountId'])));
  }
}
