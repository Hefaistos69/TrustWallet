<?php
if (Loggedin()) {
  header("Location: ./?pagina=lobby");
  die();
}
if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
}
?>

<div class="container-fluid d-flex h-auto min-vh-100 w-100 bg-primary justify-content-center align-items-center">
  <div class="container-sm d-flex h-auto align-items-center  justify-content-center">
    <div class="card w-100 bg-dark rounded-3">
      <div class="row g-0">
        <div class="col-lg-5 d-flex align-items-center justify-content-center p-3 rounded border border-3 border-success">
          <div class="my-auto">
            <img src="Imagini/logo-full.png" class="img-fluid">

          </div>
        </div>
        <div class="col-lg-7">
          <div class="card-body d-flex align-items-center justify-content-center">
            <div class="h-auto w-80">
              <h1 class="mt-5 text-light text-center">Înregistrare</h1>
              <hr class="text-light mb-3">
              <form action="Scripturi/script-signup.php" method="post">
                <div class="mb-2">
                  <label for="username" class="form-label text-light fs-4">
                    Nume utilizator
                  </label>
                  <div class="<?= $error == 'invalidUsername' ? "border rounded-3 border-2 border-danger" : "" ?>">
                    <input type="text" class="form-control text-light bg-dark border-secondary border-1" id="username" name="username" placeholder="Nume utilizator" value="<?= GetOldValue() ?>">
                  </div>
                </div>
                <div class="mb-2">
                  <label for="email" class="form-label text-light fs-4">
                    Email
                  </label>
                  <div class="<?= $error == 'invalidEmail' ? "border rounded-3 border-2 border-danger" : "" ?>">
                    <input type="text" class="form-control text-light bg-dark border-secondary border-1" id="email" name="email" placeholder="Email" value="<?= GetOldValue() ?>">
                  </div>
                </div>
                <div class="mb-2">
                  <label for="password" class="form-label text-light fs-4">
                    Parolă
                  </label>
                  <div class="<?= $error == 'invalidPassword' ? "border rounded-3 border-2 border-danger" : "" ?>">
                    <input type="password" class="form-control text-light bg-dark border-secondary border-1" id="password" name="password" placeholder="Parolă">
                  </div>
                </div>
                <div class="mb-4">
                  <label for="repeat-password" class="form-label text-light fs-4">
                    Repetă parola
                  </label>
                  <div class="<?= $error == 'pwddontmatch' ? "border rounded-3 border-2 border-danger" : "" ?>">
                    <input type="password" class="form-control text-light bg-dark border-secondary border-1" id="repeat-password" name="repeat-password" placeholder="Repetă parola">
                  </div>
                </div>
                <?= ShowError() ?>
                <div class="d-grid gap-2 d-lg-flex justify-content-xl-end mb-1">

                  <button class="mx-1 btn btn-outline-success" type="submit">Înregistrare</button>
                </div>
                <div class="d-flex flex-column mb-5">
                  <p class="fs-5 text-light mb-0">Ai cont? <a class="text-success fs-5 text-decoration-none" href="./?pagina=login">Autentifică-te</a></p>
                </div>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>



  </div>
</div>