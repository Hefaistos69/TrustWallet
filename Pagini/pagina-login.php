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

  <div class="container-fluid d-flex h-auto min-vh-100 w-100 bg-dark justify-content-center align-items-center">
    <div class="container-sm d-flex h-auto align-items-center justify-content-center">
      <div class="card w-100 bg-primary rounded-3">
        <div class="row g-0">
          <div class="col-lg-5 d-flex align-items-center justify-content-center p-3 rounded border border-3 border-success">
            <div class="my-auto">
              <img src="../Imagini/logo-full.png" class="img-fluid">
              
            </div>
          </div>
          <div class="col-lg-7">
            <div class="card-body d-flex align-items-center justify-content-center">
              <div class="h-auto w-80">
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
                  <div class="d-grid gap-2 d-lg-flex justify-content-xl-end mb-1">
                    <button class="mx-1 btn btn-success btn-lg text-light" type="submit">Autentificare</button>
                    <button class="mx-1 btn btn-success btn-lg text-light" type="submit">Demo</button>
                    <button class="mx-1 btn btn-success btn-lg text-light" type="submit">Deținător</button>
                  </div>
                  <div class="d-flex flex-column mb-5">
                    <p class="fs-5 text-light mb-0">Nu ai cont? <a class="text-success fs-5 text-decoration-none" href="">Crează cont</a></p>
                    <p class="fs-5 text-light mb-0">Ți-ai uitat parola? <a class="text-success fs-5 text-decoration-none" href="">Recuperare parolă</a></p>
                  </div>

                </form>

              </div>
            </div>
          </div>
        </div>
      </div>



    </div>
  </div>
</body>

</html>