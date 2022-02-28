<?php
session_start();
include_once "../Module/modul-db.php";
include_once "../Module/modul-functii.php";


if (isset($_POST['username']) && isset($_POST['password'])) {
  $values = []; // for query
  $values[] = $_POST['username'];
  $values[] = $_POST['password'];
  $password = $_POST['password']; //for verification
  SetOldValues($_POST['username']);

  $data = UserExists($conn, $_POST['username']);
  // $query = "SELECT * FROM users 
  //           WHERE (BINARY usersUsername = ? OR BINARY usersEmail = ?);";

  // $result = QueryDatabase($conn, $query, $values);
   if ($data) {
  //   $data = mysqli_fetch_assoc($result);
    if (password_verify($password, $data['usersPassword'])) {
      //success
      $_SESSION['userId'] = $data['usersId'];
      header("Location: ../");
      DeleteOldValues();
      die();
    } else {

      //error
      header("Location: ../?pagina=login");
      $_SESSION['error'] = 'incorrectPassword';
      die();
    }
  } else {
    //error
    header("Location: ../?pagina=login");
    $_SESSION['error'] = 'incorrectUser';
    die();
  }
} else {
  //error
?>
  <div>eroare</div>
<?php
}
