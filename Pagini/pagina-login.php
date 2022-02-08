<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    include_once "../Module/modul-css.php";
  ?>
  <title>Autentificare</title>

</head>
<body>
    <div class="container-fluid d-flex vh-100 w-100 bg-dark justify-content-center align-items-center">
      <div class="d-flex bg-primary h-auto w-60 rounded-3 align-items-center justify-content-center">
        

        <div class="h-auto w-80 ">
          <h1 class="mt-5 text-light text-center">Autentificare</h1>
          <hr class="text-light mb-3">
          <form action="" method="post">
            <div class="mb-2">
              <label for="username" class="form-label text-light fs-4">
                Utilizator sau email
              </label>
              <input type="text" class="form-control bg-secondary border-0" id="username" name="username" placeholder="Utilizator sau email">
            </div>
            <div class="mb-4">
              <label for="password" class="form-label text-light fs-4">
                Parolă
              </label>
              <input type="password" class="form-control bg-secondary border-0" id="password" name="password" placeholder="Parolă">
            </div>
            <div class="d-flex flex-row-reverse mb-1">
              <button class="mx-1 btn btn-success btn-lg text-light" type="submit">Autentificare</button>
              <button class="mx-1 btn btn-success btn-lg text-light" type="submit">Demo</button>
              <button class="mx-1 btn btn-success btn-lg text-light" type="submit">Deținător</button>
            </div>  
            <div class="d-flex flex-column mb-5">
              <p class="fs-5 text-light mb-0">Nu ai cont? <a class="text-success fs-5 text-decoration-none" href="">Crează nou</a></p>
              <p class="fs-5 text-light mb-0">Ți-ai uitat parola? <a class="text-success fs-5 text-decoration-none" href="">Recuperare parolă</a></p>
            </div>

          </form>

        </div>
      </div>
    </div>
</body>
</html>