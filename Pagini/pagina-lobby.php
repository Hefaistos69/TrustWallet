<?php
//verificam daca e autentificat
if (!Loggedin()) {
  header("Location: ./?pagina=login");
  die();
}

//preluarea conturilor userului curent
$query = "SELECT * FROM accounts WHERE usersId = ?;";
$values = array();
$values[] = $_SESSION['userId'];
if (!$resultAccounts = QueryDatabase($conn, $query, $values)) {
  header("Location: ./?pagina=dberror");
  die();
}

$userAccounts = [];
while($data = mysqli_fetch_assoc($resultAccounts))
{
  $userAccounts[] = $data;
}

//preluarea datelor contului activ (daca exista)

if ($pagina == 'account' && isset($_GET['accountId'])) {
  $accountId = intval($_GET['accountId']);
  $query = "SELECT * FROM accounts WHERE accountId = ?;";
  $values = array();
  $values[] = $accountId;
  if ($result = QueryDatabase($conn, $query, $values)) {
    $accountData = mysqli_fetch_assoc($result);
  } else {
    header("Location: ./?pagina=dberror");
    die();
  }
  if ($accountData['usersId'] != $_SESSION['userId']) {
    header("Location: ./?pagina=noaccess");
    die();
  }
  if (!isset($_SESSION['selectedCurrency']))
    $_SESSION['selectedCurrency'] = $accountData['accountCurrency'];
}

//preluarea datelor userului activ
$query = "SELECT * FROM users WHERE usersId = ?";
$values = array();
$values[] = $_SESSION['userId'];
if (!$result = QueryDatabase($conn, $query, $values)) {
  header("Location: ./?pagina=dberror");
  die();
}
$userData = mysqli_fetch_assoc($result);

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


  <!-- Side nav -->

  <div class="row w-100 h-100">


    <div class="col-2">
      <div class="text-secondary fs-5 mt-2 mb-4">Cont personal</div>
      <ul class="nav nav-tabs flex-column fs-5">
        <li class="nav-item">
          <a class="nav-link <?= $pagina == 'lobby' ? 'active' : '' ?>" aria-current="page" href="./?pagina=lobby"><i class="bi bi-speedometer2"></i> Acasă</a>
        </li>
        <li class="nav-item ">
          <a class="nav-link <?= $pagina == 'account' ? 'active' : '' ?>" data-bs-toggle="collapse" href="#collapseNav" aria-expanded="true" aria-controls="collapseNav">
            <i class="bi bi-bank2"></i> Conturi
          </a>
          <div class="collapse bg-dark <?= $pagina == 'account' ? 'show' : '' ?>" id="collapseNav">
            <div class="card card-body">
              <?php
              foreach($userAccounts as $userAccount) {
              ?>
                <a class="nav-link <?= $pagina == 'account' ? ($accountData['accountId'] == $userAccount['accountId'] ? 'active' : '') : '' ?>" href="./?pagina=account&accountId=<?= htmlspecialchars($userAccount['accountId']) ?>">
                  <?php
                  switch ($userAccount['accountType']) {
                    case "Economie":
                  ?>
                      <i class="bi bi-piggy-bank"></i>
                    <?php
                      break;
                    case "Salariu":
                    ?>
                      <i class="bi bi-cash"></i>
                    <?php
                      break;
                    case "Credit":
                    ?>
                      <i class="bi bi-wallet2"></i>
                  <?php
                      break;
                  }
                  ?> <?= htmlspecialchars($userAccount['accountName']) ?>
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
    <!-- MAIN -->
    <div class="col w-100 h-100">
      <div class="d-flex justify-content-between mt-2 mb-5">
        <div class="text-secondary fs-5 ">
          <?= htmlspecialchars($pagina == 'account' ? "Conturi > {$accountData['accountName']}" : 'Acasă') ?>
        </div>
        <?php
        if ($pagina == 'account') {
        ?>
          <div class="d-flex mx-2">
            <button class="btn btn-outline-info me-2" type="button" data-bs-toggle="modal" data-bs-target="#editAccountModal"><i class="bi bi-pencil-fill"></i> Editează</button>
            <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal"><i class="bi bi-trash-fill"></i> Șterge</button>
          </div>
        <?php
        }
        ?>
      </div>
      <div class="shadow bg-dark rounded-3 pb-3">
        <?php
        if (in_array($pagina, ['lobby', 'account'])) {
          $fisierLayout = "Pagini/Profil/profil-{$pagina}.php";
        }

        if (file_exists($fisierLayout))
          include $fisierLayout;
        else
          header("Location: ./?pagina=404");
        ?>
        
      </div>
    </div>
  </div>
</div>

<!-- Create account modal -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content text-info bg-dark">
      <div class="modal-header ">
        <h5 class="modal-title" id="createModalLabel">Adaugă un cont</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createAccountForm" action="Scripturi/script-add-account.php" method="post" class="mx-5 mt-3">
          <input type="hidden" name="createAccountForm">
          <div class="row mb-4 g-3 align-items-center">
            <div class="col-2">
              <label for="accountName" class="form-label text-light fs-6">Nume cont</label>
            </div>
            <div class="col-9 offset-1">
              <input id="accountName" name="accountName" type="text" class="form-control text-light bg-dark border-secondary border-1" max="10" placeholder="Numele contului">
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
              <select  onchange="ChangeCurrency(this.value, '#spanSuma')" class="form-select text-light bg-dark border-secondary border-1" name="accountCurrency" id="accountCurrency">
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
          <div id="createErrorDiv"></div>
          <div class="float-end mb-3">
            <button type="submit" class="btn btn-success text-light">Salvează</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit account modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content text-info bg-dark">
      <div class="modal-header ">
        <h5 class="modal-title" id="editModalLabel">Modifică contul <?= htmlspecialchars($accountData['accountName']) ?></h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editAccountForm" action="Scripturi/script-edit-account.php" method="post" class="mx-5 mt-3">
          <input type="hidden" name="editAccountForm">

          <input type="hidden" name="accountId" value="<?= htmlspecialchars($accountData['accountId']) ?>">
          <div class="row mb-4 g-3 align-items-center">
            <div class="col-2">
              <label for="accountName" class="form-label text-light fs-6">Nume cont</label>
            </div>
            <div class="col-9 offset-1">
              <input value="<?= htmlspecialchars($accountData['accountName']) ?>" id="accountName" name="accountName" type="text" class="form-control text-light bg-dark border-secondary border-1" max="10" placeholder="Numele contului">
            </div>

            <div class="col-2">
              <label for="accountType" class="form-label text-light fs-6">Tip cont</label>
            </div>
            <div class="col-9 offset-1">
              <select class="form-select text-light bg-dark border-secondary border-1" name="accountType" id="accountType">
                <option <?= $accountData['accountType'] == 'Economie' ? 'selected' : '' ?> value="Economie">Economie</option>
                <option <?= $accountData['accountType'] == 'Salariu' ? 'selected' : '' ?> value="Salariu">Salariu</option>
                <option <?= $accountData['accountType'] == 'Credit' ? 'selected' : '' ?> value="Credit">Credit</option>
              </select>
            </div>

          </div>
          <div id="editErrorDiv"></div>
          <div class="float-end mb-3">
            <button type="submit" class="btn btn-success text-light">Salvează</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal Delete -->
<div class="modal fade" id="deleteAccountModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-info bg-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Ștergere cont <?= htmlspecialchars($accountData['accountName']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="text-danger" style="font-size: 10rem">
          <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="text-light fs-5">
          Ești sigur că vrei să ștergi contul?
        </div>
      </div>
      <div class="modal-footer">
        <div class="mx-auto">
          <?php
          if (!isset($_GET['accountId'])) {
            $accountId = -1;
          }
          ?>
          <a href="Scripturi/script-delete-account.php?accountId=<?= htmlspecialchars($accountId) ?>" class="btn btn-outline-danger">Șterge</a>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Închide</button>
        </div>
      </div>
    </div>
  </div>
</div>