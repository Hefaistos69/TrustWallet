<?php
$currentCurrency = '';
$currencyAmount = '';
switch ($_SESSION['selectedCurrency']) {
  case 'RON':
    $currencyAmount = $accountData['amountRON'];
    $currentCurrency = '<span class="fw-bolder ms-1"> lei</span>';
    break;
  case 'EUR':
    $currencyAmount = $accountData['amountEUR'];
    $currentCurrency = '<i class="bi bi-currency-euro"></i>';
    break;
  case 'USD':
    $currencyAmount = $accountData['amountUSD'];
    $currentCurrency = '<i class="bi bi-currency-dollar"></i>';
    break;
}
?>

<div class="row row-cols-1 row-cols-md-4 g-2 p-2">

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <div class="d-flex justify-content-between mx-3 pt-2">
          <h3 class="fs-4 text-secondary "><i class="bi bi-cash-stack "></i> Soldul</h3>
          
          <div class="dropdown text-center">
            <a style="cursor: pointer;" class="text-decoration-none fs-5 text-secondary dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><?=htmlspecialchars($_SESSION['selectedCurrency'])?></a>


            <ul class="dropdown-menu dropdown-menu-dark " aria-labelledby="dropdownMenuButton1">
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('USD', <?=$accountId?>)">USD</a></li>
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('EUR', <?=$accountId?>)">EUR</a></li>
              <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrencyAccount('RON', <?=$accountId?>)">RON</a></li>
            </ul>
          </div>

        </div>
        <h1 class="text-success mx-3 fw-lighter pb-4 d-flex">
          <div id="accountBalance"><?=$currencyAmount?></div>
          <div><?=$currentCurrency?></div>
        </h1>

        </button>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <h3 class="fs-4 text-secondary mx-3 pt-2"><i class="bi bi-credit-card"></i> Cheltuieli lunare</h3>

        <h1 class="text-danger mx-3 fw-lighter pb-4">0</h1>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <h3 class="fs-4 text-secondary mx-3 pt-2"><i class="bi bi-piggy-bank"></i> Venituri lunare</h3>

        <h1 class="text-success mx-3 fw-lighter pb-4">0</h1>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card mx-2 bg-primary rounded-3">
      <div class="card-body">

        <h3 class="fs-4 text-secondary mx-3 pt-2"><i class="bi bi-arrow-left-right"></i> Tranzactii lunare</h3>
        <!--505-->

        <h1 class="text-warning mx-3 fw-lighter pb-4">0</h1>
      </div>
    </div>
  </div>
</div>

<!-- <h1 class="text-center f-1 text-secondary"><i class="bi bi-arrow-down-circle"></i> Schimba valuta</h1> 505 -->