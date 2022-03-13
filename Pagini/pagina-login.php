<?php
if (Loggedin()) {
  header("Location: ./?pagina=pornire");
  die();
}
if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
}
?>

<div class="container-fluid d-flex h-auto min-vh-100 w-100 bg-primary justify-content-center align-items-center">
  <div class="container-sm d-flex h-auto align-items-center justify-content-center">
    <div class="card w-100 shadow bg-dark rounded-3">
      <div class="row g-0">
        <div class="col-lg-5 d-flex align-items-center justify-content-center p-3 rounded border border-3 border-success">
          <div class="my-auto">
            <img src="Imagini/logo-full.png" class="img-fluid">

          </div>
        </div>
        <div class="col-lg-7">
          <div class="card-body d-flex align-items-center justify-content-center">
            <div class="h-auto w-80">
              <h1 class="mt-5 text-light text-center">Autentificare</h1>
              <hr class="text-light mb-3">
              <form action="Scripturi/script-login.php" method="post">
                <div class="mb-2">
                  <label for="username" class="form-label text-light fs-4">
                    Utilizator sau email
                  </label>
                  <div class="<?= $error == 'incorrectUser' ? "border rounded-3 border-2 border-danger" : "" ?>">
                    <input type="text" class="form-control text-light bg-dark border-secondary border-1" id="username" name="username" placeholder="Utilizator sau email" value="<?= GetOldValue() ?>">
                  </div>
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label text-light fs-4">
                    Parolă
                  </label>
                  <div class="<?= $error == 'incorrectPassword' ? "border rounded-3 border-2 border-danger" : "" ?>">
                    <input type="password" class="form-control text-light bg-dark border-secondary border-1" id="password" name="password" placeholder="Parolă">
                  </div>
                </div>
                <?= ShowError() ?>
                <div class="d-grid gap-2 d-lg-flex justify-content-xl-end mb-1">
                  <button class="mx-1 btn btn-success text-light" type="submit">Deținător</button>
                  <button class="mx-1 btn btn-success text-light" type="submit">Membru</button>
                  <input class="mx-1 btn btn-success text-light" type="submit"  name="btnDemo" value="Demo">
                  <button class="mx-1 btn btn-success text-light" type="submit">Autentificare</button>
                </div>
                <div class="d-flex flex-column mb-5">
                  <p class="fs-5 text-light mb-0">Nu ai cont? <a class="text-success fs-5 text-decoration-none" href="./?pagina=signup">Crează cont</a></p>
                  <p class="fs-5 text-light mb-0">Ți-ai uitat parola? <a class="text-success fs-5 text-decoration-none" href="Scripturi/script-mesaj.php">Recuperare parolă</a></p>
                </div>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>



  </div>
</div>