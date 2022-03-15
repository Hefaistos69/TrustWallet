<?php
session_start();
include_once "../Module/modul-db.php";
include_once "../Module/modul-functii.php";

if (isset($_POST['btnDemo'])) {
  $password = '1234';
  $username = 'Demo';
}
else if (isset($_POST['username']) && isset($_POST['password'])) {
  $password = $_POST['password']; //for verification
  $username = $_POST['username']; //for verification

} else {
  //error
  AddMessage("A apărut o eroare la logare!", "danger"); 
  header("Location: ../?pagina=login");
  die();
}

SetOldValues($username);

$data = UserExists($conn, $username);

if ($data) {
  if (password_verify($password, $data['usersPassword'])) {
    //success
    AddMessage("Te-ai conectat cu succes!", "success");
    $_SESSION['userId'] = $data['usersId'];
    header("Location: ../");
    DeleteOldValues();
    die();
  } else {

    //error
    header("Location: ../");
    $_SESSION['error'] = 'incorrectPassword';
    die();
  }
} else {
  //error
  header("Location: ../");
  $_SESSION['error'] = 'incorrectUser';
  die();
}
