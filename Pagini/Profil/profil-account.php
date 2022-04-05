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

        <h3 class="fs-4 text-secondary mx-3 pt-2"><i class="bi bi-credit-card"></i> Cheltuieli lunare</h3>

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

          <form action="">
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

        <a class="text-decoration-none text-secondary fs-4" data-bs-toggle="collapse" href="#collapseAddTransaction" role="button" aria-expanded="true" aria-controls="collapseAddTransaction">
          <i class="bi bi-caret-down-fill"></i>
        </a>
      </div>
    </div>
  </div>


</div>



<script>
  ChangeCurrencyAccount('<?= $accountData['accountCurrency'] ?>', <?= $accountId ?>, '#soldValutaDropdown')
</script>