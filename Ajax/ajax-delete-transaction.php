<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";

if (
  isset($_POST['transactionId']) && isset($_POST['transactionType']) && isset($_POST['transactionBalance'])
  && isset($_POST['transactionCurrency']) && isset($_POST['accountId']) && isset($_POST['transferToAccount'])
) {

  $transactionType = $_POST['transactionType'];
  $transactionBalance = $_POST['transactionBalance'];
  $transactionCurrency = $_POST['transactionCurrency'];
  $accountId = $_POST['accountId'];
  $transactionId = $_POST['transactionId'];
  $transferToAccount = $_POST['transferToAccount'];

  $currency = '';
  if (!$currency = CurrencyToAmount($transactionCurrency)) {
    echo (json_encode(array('success' => 0, 'error' => 'invalidData')));
    die();
  }

  //Get account balance
  $values = PrepareValues($accountId);
  $query = "SELECT {$currency} FROM accounts WHERE accountId = ?;";
  if (!$result = QueryDatabase($conn, $query, $values)) {
    echo (json_encode(array('success' => 0, 'error' => 'db')));
    die();
  }
  $data = mysqli_fetch_assoc($result);

  //Realocating the money
  if ($transactionType == 'Depunere') {
    $data[$currency] -= $transactionBalance;
    if ($data[$currency] < 0)
      $data[$currency] = 0;
  } else if ($transactionType == 'Cheltuire') {
    $data[$currency] += $transactionBalance;
  } else if ($transactionType == 'Transfer') {
    //Get the other account balance
    $values = PrepareValues($transferToAccount);
    $query = "SELECT {$currency} FROM accounts WHERE accountId = ?;";
    if (!$result = QueryDatabase($conn, $query, $values)) {
      echo (json_encode(array('success' => 0, 'error' => 'db')));
      die();
    }
    $data2 = mysqli_fetch_assoc($result);
    if ($data2 != null) {
      $data2[$currency] -= $transactionBalance;
      if ($data2[$currency] < 0)
        $data2[$currency] = 0;

      //Update the other account balance
      $values = PrepareValues($data2[$currency], $transferToAccount);
      $query = "UPDATE accounts
            SET {$currency} = ?
            WHERE accountId = ?;";
      if (!QueryDatabase($conn, $query, $values)) {
        echo (json_encode(array('success' => 0, 'error' => 'db-update')));
        die();
      }
    }
    if ($data != null)
      $data[$currency] += $transactionBalance;
  }

  //Update account balance
  if ($data != null) {
    $values = PrepareValues($data[$currency], $accountId);
    $query = "UPDATE accounts
            SET {$currency} = ?
            WHERE accountId = ?;";
    if (!QueryDatabase($conn, $query, $values)) {
      echo (json_encode(array('success' => 0, 'error' => 'db-update')));
      die();
    }
  }

  //Delete the tranasaction
  $values = PrepareValues($transactionId);
  $query = "DELETE FROM transactions WHERE transactionId = ?;";
  if (!QueryDatabase($conn, $query, $values)) {
    echo (json_encode(array('success' => 0, 'error' => 'db-delete')));
    die();
  } else {
    echo (json_encode(array('success' => 1, 'transactionType' => $_SESSION['transactionType'])));
    die();
  }
} else {
  echo (json_encode(array('success' => 1, 'transactionType' => $_SESSION['transactionType'])));
  die();
}
