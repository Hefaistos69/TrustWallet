<?php

//General
function Loggedin()
{
  if (isset($_SESSION['userId']))
    return $_SESSION['userId'];
  return false;
}

function CurrencyToAmount($value)
{
  switch ($value) {
    case 'USD':
      return 'amountUSD';
    case 'EUR':
      return 'amountEUR';
    case 'RON':
      return 'amountRON';
    default:
      return false;
  }
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

function PrepareValues(...$values)
{
  return $values;
}

function ShowError()
{
  if (!isset($_SESSION['error']))
    return;
  $error = $_SESSION['error'];
  $message = '';
  switch ($error) {
    case 'invalidUsername':
      $message = '◍ Numele de utilizator este invalid! Folosește doar litere și numere (maxim 20 de caractere)!';
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
    case 'invalidAccountName':
      $message = '◍ Numele contului este invalid! Folosește doar litere și numere (maxim 20 de caractere)!';
      break;
    case 'invalidCurrency':
      $message = '◍ Valuta este invalidă!';
      break;
    case 'invalidAccountType':
      $message = '◍ Tipul contului este invalid!';
      break;
    case 'balanceNotNumeric':
      $message = '◍ Suma trebuie să fie un număr!';
      break;
    case 'balanceOverflow':
      $message = '◍ Suma trebuie să fie între 0 și 999,999,999!';
      break;
    case 'invalidTransactionType':
      $message = '◍ Tipul tranzacției este invalid!';
      break;
    case 'invalidMemo':
      $message = '◍ Notița trebuie să conțină cel mult 20 de caractere!';
      break;
  }

  unset($_SESSION['error']);
  return "<div class=\"text-danger fs-6 mb-3\">{$message}</div>";
}


function AddMessage($text, $type)
{
  if (!isset($_SESSION['messages']))
    $_SESSION['messages'] = [];
  $icon = '';
  switch ($type) {
    case 'danger':
      $icon = '<i class="bi bi-x-circle"></i>';
      break;
    case 'warning':
      $icon = '<i class="bi bi-exclamation-triangle"></i>';
      break;
    case 'success':
      $icon = '<i class="bi bi-check-circle"></i>';
  }
  $message = [
    'text' => $text,
    'type' => $type,
    'icon' => $icon
  ];
  $_SESSION['messages'][] = $message;
}

function ShowMessages()
{
  if (!isset($_SESSION['messages']))
    return;
  foreach ($_SESSION['messages'] as $message) {
?>
    <div class="toast shadow text-light bg-<?= $message['type'] ?> border-0" role="alert" style="z-index: 1990;">

      <div class="timer-animation">
        <div class="d-flex p-2 ">
          <div class="toast-body ">
            <div class="d-flex justify-content-center align-items-center">
              <div class="mx-2 fs-2"><?= $message['icon'] ?></div>
              <div class="fs-6"><?= htmlspecialchars($message['text']) ?></div>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

<?php
  }
  unset($_SESSION['messages']);
}

function QueryDatabase($conn, $query, $values = array())
{
  $valueString = "";
  foreach ($values as $value) {
    $valueString .= 's';
  }

  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $query)) {
    //error
    // AddMessage(mysqli_error($conn), "danger");
    // header("location: ../");
    // die();
    return false;
  }

  if (!empty($values)) {
    if (!mysqli_stmt_bind_param($stmt, $valueString, ...$values)) {
      //error
      // AddMessage("binding", "danger");
      // header("location: ../");
      // die();
      return false;
    }
  }

  if (!mysqli_stmt_execute($stmt)) {
    //error
    // AddMessage("execute", "danger");
    // header("location: ../");
    // die();
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
function EmptyInput(...$values)
{
  $result = false;
  foreach ($values as $value) {
    if (empty($value))
      $result = true;
  }

  return $result;
}

function InvalidUsername($username)
{
  $result = true;
  if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    $result = true;
  } else if (strlen($username) > 20) {
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
            WHERE BINARY usersUsername = ? OR BINARY usersEmail = ?;";
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
    AddMessage("A apărut o eroare la înregistrare!", "danger");
    header("Location: ../?pagina=signup");
    die();
  } else {
    //success
    AddMessage("Înregistrarea s-a efectuat cu succes!", "success");
    header("Location: ../?pagina=login");
    die();
  }
}
