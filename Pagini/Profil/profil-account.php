<div class="row row-cols-1 row-cols-md-4 g-2 p-2">

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <div class="d-flex justify-content-between mx-3 pt-2">
          <h3 class="fs-4 text-secondary "><i class="bi bi-cash-stack "></i> Soldul</h3>

          <div class="dropdown text-center">
            <a style="cursor: pointer;" class="text-decoration-none fs-5 text-secondary dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><?= htmlspecialchars($_SESSION['selectedCurrency']) ?></a>


            <ul class="dropdown-menu dropdown-menu-dark " aria-labelledby="dropdownMenuButton1">
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('USD', <?= $accountId ?>)">USD</a></li>
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('EUR', <?= $accountId ?>)">EUR</a></li>
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('RON', <?= $accountId ?>)">RON</a></li>
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
    <div class="d-flex justify-content-between">
      <h1 class="text-secondary fs-4 ms-2">Adaugă o tranzacție</h1>

      <a class="text-decoration-none text-secondary fs-4" data-bs-toggle="collapse" href="#collapseAddTransaction" role="button" aria-expanded="true" aria-controls="collapseAddTransaction">
        <i class="bi bi-caret-down-fill"></i>
      </a>
    </div>

    <div class="collapse show" id="collapseAddTransaction">
      <div class="card card-body">

        <form action="">
          <select onchange="TransactionTypeForm(this.value)" class="form-select text-light bg-dark border-secondary border-1 w-auto" name="transactionType" id="">
            <option value="">Alege tipul tranzacției</option>
            <option value="">Depunere</option>
            <option value="">Cheltuire</option>
            <option value="">Transfer</option>
            <option value="">Schimb valutar</option>
          </select>

          <div class="collapse" id="collapseTransactionType">
            <div class="card card-body">


              <div class="d-flex align-items-center mt-3 mx-3">

                <label for="accountBalance" class="form-label text-light fs-6 me-2">Suma</label>

                <div class="input-group mb-3">
                  <span id="spanSuma" class="input-group-text text-light border-secondary bg-dark">USD</span>
                  <input name="accountBalance" id="accountBalance" type="text" class="form-control text-light bg-dark border-secondary border-1" aria-label="Amount (to the nearest dollar)">
                  <span class="input-group-text bg-dark border-secondary text-light">.00</span>
                </div>

              </div>
            </div>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>

  <div class="col ">
    <div class="bg-primary rounded-3 mx-2 px-2">
      <div class="d-flex justify-content-between">
        <h1 class="text-secondary fs-4 ms-2">Schimb valutar</h1>

        <a class="text-decoration-none text-secondary fs-4" data-bs-toggle="collapse" href="#collapseAddTransaction" role="button" aria-expanded="true" aria-controls="collapseAddTransaction">
          <i class="bi bi-caret-down-fill"></i>
        </a>
      </div>
    </div>
  </div>


</div>



<script>
  ChangeCurrencyAccount('<?= $_SESSION['selectedCurrency'] ?>', <?= $accountId ?>)
</script>