<?php

//General
function Loggedin()
{
  if (isset($_SESSION['userId']))
    return $_SESSION['userId'];
  return false;
}

function SetOldValues($username, $email)
{
  $_SESSION['username'] = $username;
  $_SESSION['email'] = $email;
}

function DeleteOldValues()
{
  if (isset($_SESSION['username']))
    unset($_SESSION['username']);
  if (isset($_SESSION['email']))
    unset($_SESSION['email']);
}

function GetOldValue($value)
{
  $result = '';
  switch ($value) {
    case 'username':
      if (isset($_SESSION['username'])) {
        $result = $_SESSION['username'];
        unset($_SESSION['username']);
      }
      break;
    case 'email':
      if (isset($_SESSION['email'])) {
        $result = $_SESSION['email'];
        unset($_SESSION['email']);
      }
      break;
  }
  return htmlspecialchars($result);
}

function ShowError()
{
  if (!isset($_SESSION['error']))
    return;
  $error = $_SESSION['error'];
  $message = '';
  switch ($error) {
    case 'invalidUsername':
      $message = '◍ Numele de utilizator este invalid!';
      break;
    case 'invalidEmail':
      $message = '◍ Email-ul este invalid!';
      break;
    case 'pwddontmatch':
      $message = '◍ Parolele nu sunt identice!';
      break;
    case 'userExists':
      $message = '◍ Numele de utilizator exista deja!';
      break;
    case 'emailExists':
      $message = '◍ Exista un utilizator cu acest email deja!'; //505
      break;
    case 'emptyInput':
      $message = '◍ Toate campurile sunt obligatorii!'; //505
      break;
    case 'incorrectUser':
      $message = '◍ Numele de utilizator este incorect!';
      break;
    case 'incorrectPassword':
      $message = '◍ Parola este incorecta!';//505
      break;
  }
?>
  <div class="text-danger fs-6 mb-2"><?= htmlspecialchars($message) ?></div>
  <?php
  unset($_SESSION['error']);
}


function AddMessage($text, $type)
{
  if (!isset($_SESSION['messages']))
    $_SESSION['messages'] = [];
  $message = [
    'text' => $text,
    'type' => $type
  ];
  $_SESSION['messages'][] = $message;
}

function ShowMessages()
{
  if (!isset($_SESSION['messages']))
    return;

  foreach ($_SESSION['messages'] as $message) {
  ?>
    <div class="toast align-items-center text-white bg-<?= $message['type'] ?> border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <?= $message['text'] ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
<?php
  }
  unset($_SESSION['messages']);
}

function QueryDatabase($conn, $query, $values)
{
  $valueString = "";
  foreach ($values as $value) {
    $valueString .= 's';
  }

  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $query)) {
    //error
    return false;
  }

  if (!mysqli_stmt_bind_param($stmt, $valueString, ...$values)) {
    //error
    return false;
  }

  if (!mysqli_stmt_execute($stmt)) {
    //error
    return false;
  }



  if (!($result = mysqli_stmt_get_result($stmt))) {
    //query with no result
    return true;
  }
  mysqli_stmt_close($stmt);

  return $result;
}

//Sign up
function EmptyInput($username, $email, $password, $repeat_password)
{
  $result = true;
  if (empty($username) || empty($email) || empty($password) || empty($repeat_password)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function InvalidUsername($username)
{
  $result = true;
  if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function InvalidEmail($email)
{
  $result = true;
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function PasswordDontMatch($password, $repeat_password)
{
  $result = true;
  if ($password !== $repeat_password) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function UserExists($conn, $username_email)
{

  $query = "SELECT * FROM users 
            WHERE usersUsername = ? OR usersEmail = ?;";
  $values[] = $username_email;
  $values[] = $username_email;
  $result = QueryDatabase($conn, $query, $values);

  if (!$result) {
    return false;
  } else {
    $data = mysqli_fetch_assoc($result);
    if (!$data)
      return false;
    else
      return $data;
  }
}

function CreateUser($conn, ...$values)
{
  $query = "INSERT INTO users (usersId, usersUsername, usersEmail, usersPassword) 
            VALUES (NULL, ?, ?, ?);";
  if (!QueryDatabase($conn, $query, $values)) {
    //error
    header("Location: ../?pagina=signup&error=createUser");
    die();
  } else {
    //success
    header("Location: ../?pagina=login&error=none");
    die();
  }
}
