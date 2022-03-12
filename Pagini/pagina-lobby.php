<?php

if (!Loggedin()) {
  header("Location: ./?pagina=login");
  die();
}
?>

<div class="container-fluid bg-primary h-100" style="">

  <!-- Header -->
  <nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <a class="navbar-brand ms-2" href="./">
        <img src="Imagini/logo-full.png" width="240" height="60">
      </a>
      <a class="nav-link fw-normal fs-5" href="Scripturi/script-logout.php">Deconectare <i class="bi bi-box-arrow-right"></i></a>
    </div>
  </nav>

  <div class="row mt-3 mb-2 fs-5">
    <div class="col-2 text-secondary">Cont personal</div>
    <div class="col text-secondary">Acasă</div>
  </div>

  <!-- Side nav -->

  <div class="row h-80 me-3" style="">


    <div class="col-2">

      <ul class="nav flex-column fs-5">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="Scripturi/script-mesaj.php"><i class="bi bi-house-door-fill"></i> Acasă</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="bi bi-bank2"></i> Conturi
          </a>
          <div class="collapse" id="collapseExample">
            <div class="card card-body ms-2">
              <a class="nav-link" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                Conturi
              </a><a class="nav-link" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                Conturi
              </a><a class="nav-link" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                Conturi
              </a>
            </div>
          </div>
        </li>

      </ul>

    </div>

    <div class="col shadow bg-dark rounded-3">
      <div class="row m-4 g-2">
        <div class="col-4">
          <div class="card mx-2 bg-primary rounded-3">
            <div class="card-body">
              <a class="btn btn-primary w-100">
                <p class="card-text text-center text-success" style="font-size: 8rem;"><i class="bi bi-bank2"></i></p>
              
              <h5 class="card-title text-info text-center pb-3">Crează un cont</h5>
              </a>
            </div>
          </div>
        </div>
        <div class="col-4  ">
        <div class="card mx-2 bg-primary rounded-3">
            <div class="card-body">
              <a class="btn btn-primary w-100">
                <p class="card-text text-center text-success" style="font-size: 8rem;"><i class="bi bi-bank"></i></p>
              
              <h5 class="card-title text-info  text-center pb-3">Crează un cont comun</h5>
              </a>
            </div>
          </div>
        </div>
        <div class="col-4">
        <div class="card mx-2 bg-primary rounded-3">
            <div class="card-body">
              <a class="btn btn-primary w-100">
                <p class="card-text text-center text-success" style="font-size: 8rem;"><i class="bi bi-door-open-fill"></i></p>
              
              <h5 class="card-title text-info text-center pb-3">Alătură-te unui cont comun</h5>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>