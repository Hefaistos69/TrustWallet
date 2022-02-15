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
  }
  else{  
    //success
    header("Location: ../?pagina=signup&error=none");
    die();
  }
}
