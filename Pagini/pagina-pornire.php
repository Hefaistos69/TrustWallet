<?php
  
  if(!Loggedin())
  {
    header("Location: ./?pagina=login");
    die();
  }
?>
<h1>Pornire!</h1>

<a href="Scripturi/script-logout.php" class="btn btn-primary">Deconectare</a>