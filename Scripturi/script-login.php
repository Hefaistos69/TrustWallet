<?php
  include_once "../Module/modul-db.php";
  include_once "../Module/modul-functii.php";


if(isset($_POST['username']) && isset($_POST['password']))
{
  $clean = [];
  $clean['username'] = mysqli_escape_string($conn, $_POST['username']);
  //$clean['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $clean['password'] = $_POST['password'];

  $query = "SELECT * FROM users 
            WHERE (usersUsername = '{$clean['username']}' OR usersEmail = '{$clean['username']}') 
            AND usersPassword = '{$clean['password']}'";

  $result = mysqli_query($conn, $query);

  if(!$result)
  {
    //eroare
    ?>
      <div>eroare: <?=mysqli_error($conn)?></div>
    <?php
    
  }
  else
  {
    if(mysqli_num_rows($result) == 1){
      $user = mysqli_fetch_assoc($result);
      $_SESSION['userId'] = $user['usersId'];

      header("Location: ../");
      die();
    }
    else
    {
      ?>
      <div>user sau parola incorecte</div>
    <?php
    }
  }
  
}
else
{
  //eroare
  ?>
      <div>eroare: <?=mysqli_error($conn)?></div>
    <?php
}