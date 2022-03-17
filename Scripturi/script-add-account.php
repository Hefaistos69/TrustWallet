<?php
session_start();
include "../Module/modul-functii.php";
include "../Module/modul-db.php";

if(isset($_POST['accountName']) && isset($_POST['bankName']) && isset($_POST['accountType'])
 && isset($_POST['accountCurrency']) && isset($_POST['accountBalance']))
{
  $accountName = $_POST['accountName'];
  $bankName = $_POST['bankName'];
  $accountType = $_POST['accountType'];
  $accountCurrency = $_POST['accountCurrency'];
  $accountBalance = $_POST['accountBalance'];

  //Error handling

  //Success
  
  $currency = '';
  switch($accountCurrency)
  {
    case 'EUR':
      $currency = "amountEUR";
      break;
    case 'USD':
      $currency = "amountUSD";
      break;
    case 'RON': 
      $currency = "amountRON";
      break;
  }

  $query = "INSERT INTO accounts (accountId, usersId, accountName, accountBank, accountType, {$currency}, creationDate)
            VALUES(NULL, ?, ?, ?, ?, ?, NOW());";
  $values[] = $_SESSION['userId'];
  $values[] = $accountName;
  $values[] = $bankName;
  $values[] = $accountType;
  $values[] = $accountBalance;
  if(QueryDatabase($conn, $query, $values))
  {
    AddMessage("Contul a fost adăugat cu succes!", "success");
  }
  else
  {
    AddMessage("Eroare!", "danger");
  }
  header("Location: ../");
  die();
}
else
{
  AddMessage("A apărut o eroare la adăugarea contului!", "danger");
  header("Location: ../");
  die();
}