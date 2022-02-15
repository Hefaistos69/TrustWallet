<?php
session_start();
include "../Module/modul-db.php";
include "../Module/modul-functii.php";


if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeat-password'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $repeat_password = $_POST['repeat-password'];

  //ERROR HANDLING

  //verify empty input
  if (EmptyInput($username, $email, $password, $repeat_password) !== false) {
    //AddMessage("Toate campurile sunt obligatorii!", "danger");
    header("Location: ../?pagina=signup&error=emptyInput");
    die();
  }
  

  //verify username
  if (InvalidUsername($username) !== false) {
    //AddMessage("Nume de utilizator invalid!", "danger");
    header("Location: ../?pagina=signup&error=invalidUsername");
    die();
  }
  
  //verify email
  if (InvalidEmail($email) !== false) {
    //AddMessage("Email invalid!", "danger");
    header("Location: ../?pagina=signup&error=invalidEmail");
    die();
  }
  
  //verify password match
  if (PasswordDontMatch($password, $repeat_password) !== false) {
    //AddMessage("Parolele nu sunt identice!", "danger");
    header("Location: ../?pagina=signup&error=pwdnotmatch");
    die();
  }
  
  //verify user via username
  if (UserExists($conn, $username) !== false) {
    //AddMessage("Numele de utilizator exita deja!", "danger");
    header("Location: ../?pagina=signup&error=userExists");
    die();
  }
  
  //verify user via email
  if (UserExists($conn, $email) !== false) {
    //AddMessage("Emailul exita deja!", "danger");
    header("Location: ../?pagina=signup&error=emailExists");
    die();
  }
  
  $password = password_hash($password, PASSWORD_DEFAULT);
  CreateUser($conn, $username, $email, $password);

} else {
  //error
  header("Location: ../?pagina=signup");
  die();
}
