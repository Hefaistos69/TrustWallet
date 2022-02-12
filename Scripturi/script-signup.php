<?php

include "../Module/modul-db.php";
include "../Module/modul-functii.php";


if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeat-password'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['passsword'];
  $repeat_password = $_POST['repeat-password'];

  //ERROR HANDLING

  //verify empty input
  if (EmptyInput($username, $email, $password, $repeat_password) !== false) {
    header("Location: ../?pagina=signup");
    die();
  }

  //verify username
  if (InvalidUsername($username) !== false) {
    header("Location: ../?pagina=signup");
    die();
  }

  //verify email
  if (InvalidEmail($email) !== false) {
    header("Location: ../?pagina=signup");
    die();
  }

  //verify password match
  if (PasswordDontMatch($password, $repeat_password) !== false) {
    header("Location: ../?pagina=signup");
    die();
  }

  //verify user via username
  if (UserExists($conn, $username) !== false) {
    header("Location: ../?pagina=signup");
    die();
  }

  //verify user via email
  if (UserExists($conn, $email) !== false) {
    header("Location: ../?pagina=signup");
    die();
  }

  CreateUser($conn, $username, $email, $password);

} else {
  //error
  header("Location: ../?pagina=signup");
  die();
}
