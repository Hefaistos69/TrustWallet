<?php

//General
function Loggedin()
{
  if (isset($_SESSION['userId']))
    return $_SESSION['userId'];
  return false;
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

?>
  <div class="container py-3">
    <?php
    foreach ($_SESSION['messages'] as $message) {
    ?>
      <div class="alert alert-<?= $message['type'] ?>"><?= htmlspecialchars($message['text']) ?></div>
    <?php
    }

    ?>
  </div>
<?php
  unset($_SESSION['messages']);
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
  $stmt = mysqli_stmt_init($conn);
  $query = "SELECT * FROM users 
            WHERE usersUsername = ? OR usersEmail = ?;";

  if (!mysqli_stmt_prepare($stmt, $query)) {
    //error
  }

  mysqli_stmt_bind_param($stmt, "ss", $username_email, $username_email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);


  $data = mysqli_fetch_assoc($result);
  if (!$data) {
    //error
    return false;
  } else {
    return $data;
  }
  mysqli_stmt_close($stmt);
}

function CreateUser($conn, $username, $email, $password)
{
  $stmt = mysqli_stmt_init($conn);
  $query = "INSERT INTO users (usersId, usersEmail, usersUsername, usersPassword) 
            VALUES (NULL, ?, ?, ?);";
  if (!mysqli_stmt_prepare($stmt, $query)) {
    //error
  }

  mysqli_stmt_bind_param($stmt, "sss", $email, $username, $password);

  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  //success
  header("Location: ../?pagina=signup?error=none");
  die();
}
