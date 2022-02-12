<?php

function Loggedin()
{
  if(isset($_SESSION['userId']))
    return $_SESSION['userId'];
  return false;
}

function EmptyInput($username, $email, $password, $repeat_password)
{
  $result = true;
  if(empty($username) || empty($email) || empty($password) || empty($repeat_password))
  {
    $result = true;
  }
  else
  {
    $result = false;
  }
  return $result;
}

function InvalidUsername($username)
{
  $result = true;
  if(!preg_match("/^[a-zA-Z0-9]*$/", $username))
  {
    $result = true;
  }
  else
  {
    $result = false;
  }
  return $result;
}

function InvalidEmail($email)
{
  $result = true;
  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    $result = true;
  }
  else
  {
    $result = false;
  }
  return $result;
}

function PasswordDontMatch($password, $repeat_password)
{
  $result = true;
  if($password !== $repeat_password)
  {
    $result = true;
  }
  else
  {
    $result = false;
  }
  return $result;
}

function UserExists($conn, $username_email)
{
  $clean = mysqli_escape_string($conn, $username_email);
  $query = "SELECT * FROM users 
            WHERE usersUsername = '{$clean}' OR usersEmail = '{$clean}';";

  $result = mysqli_query($conn, $query);

  if(!$result)
  {
    return false;
  }
  else
  {
    $data = mysqli_fetch_assoc($result);
    if(!$data)
    {
      //error
    }
    else
    {
      return $data;
    }
  }
}

function CreateUser($conn, $username, $email, $password)
{
  $clean = [];
  $clean['username'] = mysqli_escape_string($conn, $username);
  $clean['email'] = mysqli_escape_string($conn, $email);
  $clean['password'] = password_hash($password, PASSWORD_DEFAULT);

  $stmt = mysqli_stmt_init($conn);
  $query = "INSERT INTO users (usersId, usersEmail, usersUsername, usersPassword) 
            VALUES (NULL, ?, ?, ?);";
  if(!mysqli_stmt_prepare($stmt, $query))
  {
    //error
  }

  mysqli_stmt_bind_param($stmt, "sss", $clean['email'], $clean['username'], $clean['password']);
  
  if(!mysqli_stmt_execute($stmt))
  {
    //error
  }
  mysqli_stmt_close($stmt);
  //success
  header("Location: ../?pagina=signup");
}


