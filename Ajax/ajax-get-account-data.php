<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";

if(isset($_POST['currency']) && isset($_POST['accountId'])){
  
  $_SESSION['selectedCurrency'] = $_POST['currency'];
  $accountId = $_POST['accountId'];
  $query = "SELECT * FROM accounts WHERE accountId = ?;";
  $values = [];
  $values[] = $accountId;
  if(!$result = QueryDatabase($conn, $query, $values))
  {
    echo json_encode(array('success' => '0', 'error' => 'db'));
    die();
  }

  $data = mysqli_fetch_assoc($result);
  
  
  echo json_encode(array('success' => '1', 'accountData' => $data));
}
else
  echo json_encode(array('success' => '0'));
die();