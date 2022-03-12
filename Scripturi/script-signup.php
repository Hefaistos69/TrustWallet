<?php
session_start();
include "../Module/modul-db.php";
include "../Module/modul-functii.php";


if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeat-password'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $repeat_password = $_POST['repeat-password'];
  SetOldValues($username, $email);
  
  //ERROR HANDLING

  //verify empty input
  if (EmptyInput($username, $email, $password, $repeat_password) !== false) {
    header("Location: ../?pagina=signup");
    $_SESSION['error'] = 'emptyInput';
    die();
  }


  //verify username
  if (InvalidUsername($username) !== false) {
    header("Location: ../?pagina=signup");
    $_SESSION['error'] = 'invalidUsername';
    die();
  }

  //verify email
  if (InvalidEmail($email) !== false) {
    header("Location: ../?pagina=signup");
    $_SESSION['error'] = 'invalidEmail';
    die();
  }
  
  //verify password match
  if (PasswordDontMatch($password, $repeat_password) !== false) {
    header("Location: ../?pagina=signup");
    $_SESSION['error'] = 'pwddontmatch';
    die();
  }
  
  //verify user via username
  if (UserExists($conn, $username) !== false) {
    header("Location: ../?pagina=signup");
    $_SESSION['error'] = 'userExists';
    die();
  }
  
  //verify user via email
  if (UserExists($conn, $email) !== false) {
    header("Location: ../?pagina=signup");
    $_SESSION['error'] = 'emailExists';
    die();
  }
  DeleteOldValues();
  $password = password_hash($password, PASSWORD_DEFAULT);
  CreateUser($conn, $username, $email, $password);
} else {
  //error
  AddMessage("A apărut o eroare la înregistrare!", "danger");
  header("Location: ../?pagina=signup");
  die();
}
