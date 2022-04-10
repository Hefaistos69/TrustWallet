<?php
  if(!isset($_SESSION['numRows'])){
    $_SESSION['numRows'] = 5;
    ?>
    <script>
    console.log(<?=$_SESSION['numRows']?>);
</script>
<?php
  }
?>


<div class="row row-cols-1 row-cols-md-4 g-2 p-2">

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <div class="d-flex justify-content-between mx-3 pt-2">
          <h3 class="fs-4 text-secondary "><i class="bi bi-cash-stack "></i> Soldul</h3>

          <div class="dropdown text-center">
            <a style="cursor: pointer;" class="text-decoration-none fs-5 text-secondary dropdown-toggle" id="soldValutaDropdown" data-bs-toggle="dropdown" aria-expanded="false"><?= htmlspecialchars($accountData['accountCurrency']) ?></a>


            <ul class="dropdown-menu dropdown-menu-dark " aria-labelledby="soldValutaDropdown">
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('USD', <?= $accountId ?>, '#soldValutaDropdown')">USD</a></li>
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('EUR', <?= $accountId ?>, '#soldValutaDropdown')">EUR</a></li>
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('RON', <?= $accountId ?>, '#soldValutaDropdown')">RON</a></li>
            </ul>
          </div>

        </div>
        <h1 class="text-success mx-3 fw-lighter pb-4 d-flex">
          <div id="accountBalance"></div>
          <div class="accountCurrency"></div>
        </h1>

        </button>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <h3 class="fs-4 text-secondary mx-3 pt-2"><i class="bi bi-basket2"></i> Cheltuieli lunare</h3>

        <h1 class="text-danger mx-3 fw-lighter pb-4 d-flex">
          <div>0</div>
          <div class="accountCurrency"></div>
        </h1>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <h3 class="fs-4 text-secondary mx-3 pt-2"><i class="bi bi-cash-coin"></i> Venituri lunare</h3>

        <h1 class="text-success mx-3 fw-lighter pb-4 d-flex">
          <div>0</div>
          <div class="accountCurrency"></div>
        </h1>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <h3 class="fs-4 text-secondary mx-3 pt-2"><i class="bi bi-arrow-left-right"></i> Tranzacții lunare</h3>

        <h1 class="text-warning mx-3 fw-lighter pb-4">
          <div>0</div>
        </h1>
      </div>
    </div>
  </div>
</div>

<div class="row row-cols-1 row-cols-md-2 g-2 p-2">
  <div class="col">
    <div class="bg-primary rounded-3 mx-2 px-2">
      <div class="d-flex justify-content-between pt-1">
        <h1 class="text-secondary fs-4 ms-2">Adaugă o tranzacție</h1>

        <a class="text-decoration-none text-secondary fs-4" data-bs-toggle="collapse" href="#collapseAddTransaction" role="button" aria-expanded="true" aria-controls="collapseAddTransaction">
          <i class="bi bi-caret-down-fill"></i>
        </a>
      </div>

      <div class="collapse show" id="collapseAddTransaction">
        <div class="card card-body">

          <div id="spinner" class="text-center  d-none  py-5">
            <div class="spinner-border text-center text-success" style="width: 3rem; height: 3rem;" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-secondary fs-6 mt-2">Te rugăm să aștepți procesarea tranzacției!</p>
          </div>
          <form id="addTransactionForm" action="Scripturi/script-add-transaction.php" method="POST">
            <input type="hidden" name="accountId" value="<?= $accountId ?>">
            <input type="hidden" name="addTransaction">
            <div class="ms-3 pb-3 me-4">
              <div class="d-flex py-2 align-items-center justify-content-between">
                <div class="d-flex w-50">
                  <label for="transactionBalance" class="from-label text-info fs-5 me-2">Suma</label>
                  <div class="input-group  my-auto ">


                    <span class="input-group-text border-secondary bg-dark text-info">
                      <div class="dropdown text-center">
                        <a style="cursor: pointer;" class="text-decoration-none fs-6 text-info dropdown-toggle" id="tranzactiiValutaDropdown" data-bs-toggle="dropdown" aria-expanded="false">USD</a>

                        <ul class="dropdown-menu dropdown-menu-dark " aria-labelledby="tranzactiiValutaDropdown">
                          <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrency('USD', '#tranzactiiValutaDropdown', '#transactionCurrency')">USD</a></li>
                          <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrency('EUR', '#tranzactiiValutaDropdown', '#transactionCurrency')">EUR</a></li>
                          <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrency('RON', '#tranzactiiValutaDropdown', '#transactionCurrency')">RON</a></li>
                        </ul>
                      </div>

                    </span>
                    <input name="transactionCurrency" id="transactionCurrency" type="hidden" value="USD">

                    <input name="transactionBalance" id="transactionBalance" type="text" class="form-control text-info bg-dark border-secondary border-1" aria-label="Amount (to the nearest dollar)">
                    <span class="input-group-text bg-dark border-secondary text-info">.00</span>
                  </div>
                </div>

                <div class="mx-2">
                  <select onchange="TransactionTypeSelect(this.value)" class="form-select text-info bg-dark border-secondary border-1 w-auto" name="transactionType">
                    <option selected value="">Tipul tranzacției</option>
                    <option value="Depunere">Depunere</option>
                    <option value="Cheltuire">Cheltuire</option>
                    <option value="Transfer">Transfer</option>
                  </select>
                </div>

                <div class="w-auto ">
                  <div class="d-none" id="transferToAccount">
                    <?php
                    if (count($userAccounts) != 1) {
                    ?>
                      <select name="transferToAccount" class="form-select  text-info bg-dark border-secondary border-1 w-auto">
                        <option selected value="">Alege contul</option>
                        <?php
                        foreach ($userAccounts as $userAccount) {
                          if ($userAccount['accountId'] != $accountId) {
                        ?>
                            <option value="<?= $userAccount['accountId'] ?>"><?= $userAccount['accountName'] ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    <?php
                    } else {
                    ?>
                      <div class="fs-6 text-warning">Nu exista cont de transfer!</div>
                    <?php
                    }
                    ?>
                  </div>
                </div>

              </div>

              <div class="d-flex justify-content-center mb-3">
                <label for="transactionMemo" class="form-label text-info fs-5 me-2">Notiță</label>
                <input id="transactionMemo" name="transactionMemo" type="text" class="form-control text-info bg-dark border-secondary border-1" placeholder="max. 20 de caractere">
              </div>
              <div id="transactionErrorDiv"></div>
              <div class="text-end">
                <button type="submit" class="btn btn-success">Adaugă</button>
              </div>

            </div>
          </form>
        </div>
      </div>

    </div>
  </div>

  <div class="col">
    <div class="bg-primary rounded-3 mx-2 px-2">
      <div class="d-flex justify-content-between pt-1">
        <h1 class="text-secondary fs-4 ms-2">Schimb valutar</h1>

        <a class="text-decoration-none text-secondary fs-4" data-bs-toggle="collapse" href="#collapseExchange" role="button" aria-expanded="true" aria-controls="collapseExchange">
          <i class="bi bi-caret-down-fill"></i>
        </a>
      </div>
      <div class="collapse show" id="collapseExchange">
        <div class="card card-body h-100">
          <p class="text-info">sal</p>
        </div>
      </div>
    </div>
  </div>


</div>

<div class=" bg-primary p-3 m-3 rounded-3">
  <div class="d-flex justify-content-between pt-1">
    <h1 class="text-secondary fs-4">Tranzacții</h1>

    <a class="text-decoration-none text-secondary fs-4" data-bs-toggle="collapse" href="#collapseTransactionTable" role="button" aria-expanded="true" aria-controls="collapseTransactionTable">
      <i class="bi bi-caret-down-fill"></i>
    </a>
  </div>

  <div class="collapse show" id="collapseTransactionTable">
    <div class="card card-body">
      <div id="noTransactions" class="text-warning d-none text-center">Nu există nicio tranzacție!</div>
      <div id="transactionTable">
        <div class="d-flex align-items-center mx-2 mb-2">
          <label for="rows" class="form-label text-info fs-5 me-2">Numărul de tranzacții</label>
          <select onchange="ShowTransactionTable(transactionsData, <?=$accountId?>, this.value)" id="rows" class="form-select  text-info bg-dark border-secondary border-1 w-auto">
            <option <?=$_SESSION['numRows'] == 5? 'selected': ''?> value="5">5</option>
            <option <?=$_SESSION['numRows'] == 10? 'selected': ''?> value="10">10</option>
            <option <?=$_SESSION['numRows'] == 15? 'selected': ''?> value="15">15</option>
            <option <?=$_SESSION['numRows'] == 20? 'selected': ''?> value="20">20</option>
          </select>
        </div>
        <table class="table table-hover">
          <thead class="text-info border-bottom ">
            <tr>
              <th scope="col">Suma</th>
              <th scope="col">Notiță</th>
              <th scope="col">Data</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody id="transactionTableBody" class="text-secondary">

          </tbody>
          <tfoot>
            <tr></tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  GetTransactionsAjax(<?= $accountId ?>);
  ChangeCurrencyAccount('<?= $accountData['accountCurrency'] ?>', <?= $accountId ?>, '#soldValutaDropdown')
</script>