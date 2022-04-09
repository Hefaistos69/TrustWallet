<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";

if(isset($_POST['accountId']))
{
  $query = 'SELECT * FROM transactions WHERE accountId = ? OR transferToAccount = ? ORDER BY transactionDate DESC;';
  $values = PrepareValues($_POST['accountId'], $_POST['accountId']);
  if(!$result = QueryDatabase($conn, $query, $values))
  {
    echo json_encode(array('success' => 0, 'error' => 'db'));
    die();
  }
  else
  {
    $data = array();
    while($data[] = mysqli_fetch_assoc($result));
    $_SESSION['transactionsData'] = $data;
    echo json_encode(array('success' => 1, 'data' => $data));
    die();
  }
}