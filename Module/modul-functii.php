<?php

//General
function Loggedin()
{
  if (isset($_SESSION['userId']))
    return $_SESSION['userId'];
  return false;
}

function SetOldValues(...$data)
{
  foreach ($data as $element) {
    $_SESSION['data'][] = $element;
  }
}

function DeleteOldValues()
{
  unset($_SESSION['data']);
}

function GetOldValue()
{
  $result = '';
  if (isset($_SESSION['data'])) {
    if (!empty($_SESSION['data'])) {
      foreach ($_SESSION['data'] as $i => $v) {
        $result = $v;
        unset($_SESSION['data'][$i]);
        break;
      }
    } else {
      unset($_SESSION['data']);
    }
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
      $message = '◍ Există un utilizator cu acest email deja!'; 
      break;
    case 'emptyInput':
      $message = '◍ Toate câmpurile sunt obligatorii!'; 
      break;
    case 'incorrectUser':
      $message = '◍ Numele de utilizator este incorect!';
      break;
    case 'incorrectPassword':
      $message = '◍ Parola este incorectă!'; 
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
