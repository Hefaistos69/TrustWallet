<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";

if (isset($_POST['accountId']) && isset($_POST['transactionType'])) {
  
  $transactionType = $_POST['transactionType'];
  $query = 'SELECT * FROM transactions WHERE accountId = ? OR transferToAccount = ? ORDER BY transactionDate DESC;';
  switch ($transactionType) {
    case 'Transfer':
      $query = "SELECT * FROM transactions WHERE (accountId = ? OR transferToAccount = ?) AND transactionType = 'Transfer' ORDER BY transactionDate DESC;";
      break;
    case 'Depunere':
      $query = "SELECT * FROM transactions WHERE (accountId = ? OR transferToAccount = ?) AND transactionType = 'Depunere' ORDER BY transactionDate DESC;";
      break;
    case 'Cheltuire':
      $query = "SELECT * FROM transactions WHERE (accountId = ? OR transferToAccount = ?) AND transactionType = 'Cheltuire' ORDER BY transactionDate DESC;";
      break;
  }
  $values = PrepareValues($_POST['accountId'], $_POST['accountId']);
  if (!$result = QueryDatabase($conn, $query, $values)) {
    echo json_encode(array('success' => 0, 'error' => 'db'));
    die();
  } else {
    $data = array();
    while ($data[] = mysqli_fetch_assoc($result));
    $_SESSION['transactionType'] = $transactionType;
    echo json_encode(array('success' => 1, 'data' => $data));
    die();
  }
}
