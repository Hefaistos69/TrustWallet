<?php


if (!Loggedin()) {
  header("Location: ./?pagina=login");
  die();
}

$query = "SELECT * FROM accounts WHERE usersId = ?;";
$values[] = $_SESSION['userId'];
$result = QueryDatabase($conn, $query, $values);

?>

<div class="container-fluid bg-primary h-auto min-vh-100">

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

  <div class="row w-100">


    <div class="col-2">

      <ul class="nav nav-tabs flex-column fs-5">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="Scripturi/script-mesaj.php"><i class="bi bi-speedometer2"></i> Acasă</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="bi bi-bank2"></i> Conturi
          </a>
          <div class="collapse bg-dark" id="collapseExample">
            <div class="card card-body">
              <?php
              while ($data = mysqli_fetch_assoc($result)) {
              ?>
                <a class="nav-link" data-bs-toggle="collapse" href="" role="button" aria-expanded="false" aria-controls="collapseExample">
                  <?php
                    switch($data['accountType']){
                      case "Economie":
                        ?><i class="bi bi-piggy-bank"></i><?php
                        break;
                      case "Salariu":
                        ?><i class="bi bi-cash"></i><?php
                        break;
                      case "Credit":
                        ?><i class="bi bi-wallet2"></i><?php
                        break;
                    }
                  ?> <?=$data['accountName']?>
                </a>
              <?php
              }
              ?>
              
              <a style="cursor: pointer;" class="nav-link fs-5" data-bs-toggle="modal" data-bs-target="#createAccountModal">
                <i class="bi bi-plus-lg fw-bold"></i> Adaugă cont
              </a>
            </div>
          </div>
        </li>

      </ul>

    </div>

    <div class="col shadow bg-dark rounded-3">
      <div class="row row-cols-1 row-cols-md-3 g-4 mb-5 mt-4">
        <div class="col">
          <div class="card mx-2 bg-primary rounded-3">
            <div class="card-body">
              <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#createAccountModal">
                <p class="card-text text-center text-success" style="font-size: 8rem;"><i class="bi bi-bank2"></i></p>

                <h5 class="card-title text-info text-center pb-3">Adaugă un cont</h5>
              </button>
            </div>
          </div>



        </div>
        <div class="col">
          <div class="card mx-2 bg-primary rounded-3">
            <div class="card-body">
              <button class="btn btn-primary w-100">
                <p class="card-text text-center text-success" style="font-size: 8rem;"><i class="bi bi-bank"></i></p>

                <h5 class="card-title text-info  text-center pb-3">Crează un cont comun</h5>
              </button>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mx-2 bg-primary rounded-3">
            <div class="card-body">
              <button class="btn btn-primary w-100">
                <p class="card-text text-center text-success" style="font-size: 8rem;"><i class="bi bi-door-open-fill"></i></p>

                <h5 class="card-title text-info text-center pb-3">Alătură-te unui cont comun</h5>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Create account modal -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content text-info bg-dark">
      <div class="modal-header ">
        <h5 class="modal-title" id="exampleModalLabel">Adaugă un cont</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="Scripturi/script-add-account.php" method="post" class="mx-5 mt-3">
          <div class="row mb-4 g-3 align-items-center">
            <div class="col-2">
              <label for="accountName" class="form-label text-light fs-6">Nume cont</label>
            </div>
            <div class="col-9 offset-1">
              <input id="accountName" name="accountName" type="text" class="form-control text-light bg-dark border-secondary border-1" max="20" placeholder="Numele contului">
            </div>

            <div class="col-2">
              <label for="bankName" class="form-label text-light fs-6">Nume bancă</label>
            </div>
            <div class="col-9 offset-1">
              <input id="bankName" name="bankName" type="text" class="form-control text-light bg-dark border-secondary border-1" max="20" placeholder="Numele băncii">
            </div>

            <div class="col-2">
              <label for="accountType" class="form-label text-light fs-6">Tip cont</label>
            </div>
            <div class="col-9 offset-1">
              <select class="form-select text-light bg-dark border-secondary border-1" name="accountType" id="accountType">
                <option selected>Alege tipul contului</option>
                <option value="Economie">Economie</option>
                <option value="Salariu">Salariu</option>
                <option value="Credit">Credit</option>
              </select>
            </div>

            <div class="col-2">
              <label for="accountCurrency" class="form-label text-light fs-6">Valuta</label>
            </div>
            <div class="col-9 offset-1">
              <select onchange="ChangeCurrency(this.value)" class="form-select text-light bg-dark border-secondary border-1" name="accountCurrency" id="accountCurrency">
                <option selected>Alege valuta contului</option>
                <option value="USD">Dolar(USD)</option>
                <option value="EUR">Euro(EUR)</option>
                <option value="RON">Leu(RON)</option>
              </select>
            </div>

            <div class="col-2">
              <label for="accountBalance" class="form-label text-light fs-6">Suma</label>
            </div>
            <div class="col-9 offset-1">
              <div class="input-group mb-3">
                <span id="spanSuma" class="input-group-text text-light border-secondary bg-dark">USD</span>
                <input name="accountBalance" id="accountBalance" type="text" class="form-control text-light bg-dark border-secondary border-1" aria-label="Amount (to the nearest dollar)">
                <span class="input-group-text bg-dark border-secondary text-light">.00</span>
              </div>
            </div>
          </div>
          <div class="float-end mb-3">
            <button type="submit" class="btn btn-success text-light">Salvează</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>