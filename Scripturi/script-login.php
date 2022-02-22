<?php
  session_start();
  include_once "../Module/modul-db.php";
  include_once "../Module/modul-functii.php";


if(isset($_POST['username']) && isset($_POST['password']))
{
  $values = [];// for query
  $values[] = $_POST['username'];
  $values[] = $_POST['password'];
  $password = $_POST['password'];//for verification

  $query = "SELECT * FROM users 
            WHERE (usersUsername = ? OR usersEmail = ?);";

  $result = QueryDatabase($conn, $query, $values);
  if(mysqli_num_rows($result) == 1)
  {
    $data = mysqli_fetch_assoc($result);
    if(password_verify($password, $data['usersPassword']))
    {
      //success
      $_SESSION['userId'] = $data['usersId'];
      header("Location: ../");
      die();
    }
    else
    {
      
      //error
      header("Location: ../?pagina=login&error=incorrectPassword");
      die();
    }
  }
  else
  {
    //error
    header("Location: ../?pagina=login&eroare=incorrectUser");
    die();
  }
}
else
{
  //error
  ?>
      <div>eroare</div>
    <?php
}