<div class="row row-cols-1 row-cols-md-4 g-2 p-2">

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <h3 class="fs-4 text-secondary mx-3 pt-2"><i class="bi bi-cash-stack "></i> Soldul</h3>

        <h1 class="text-success mx-3 fw-lighter">
          <?php
          switch ($_SESSION['selectedCurrency']) {
            case 'RON':
              print $accountData['amountRON'] . '<span class="fw-bolder"> lei</span>';
              break;
            case 'EUR':
              print $accountData['amountEUR'] . '<i class="bi bi-currency-euro"></i>';
              break;
            case 'USD':
              print $accountData['amountUSD'] . '<i class="bi bi-currency-dollar"></i>';
              break;
          }
          ?>
        </h1>
        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#createAccountModal">

        </button>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">
        <button class="btn btn-primary w-100">

        </button>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">
        <button class="btn btn-primary w-100">

        </button>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">
        <button class="btn btn-primary w-100">

        </button>
      </div>
    </div>
  </div>
</div>

</div>