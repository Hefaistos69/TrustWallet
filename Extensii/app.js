/////////////
//VARIABLES//
/////////////

var transactionsData;
var monthlyDeposits, monthlySpendings, monthlyTransactions;
var accountCurrentCurrency;

/////////////
//FUNCTIONS//
/////////////

//HTML RELATED
function ShowToasts() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl);
    })
    toastList.forEach(toast => toast.show());
}

function Escape(unsafe) {
    return unsafe.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
}

function CreateToast(message, type) {
    let icon = '';
    switch (type) {
        case 'danger':
            icon = '<i class="bi bi-x-circle"></i>';
            break;
        case 'warning':
            icon = '<i class="bi bi-exclamation-triangle"></i>';
            break;
        case 'success':
            icon = '<i class="bi bi-check-circle"></i>';
    }
    let T = `<div class="toast shadow text-light bg-${Escape(type)} border-0" role="alert" style="z-index: 1990;">

    <div class="timer-animation">
      <div class="d-flex p-2 ">
        <div class="toast-body ">
          <div class="d-flex justify-content-center align-items-center">
            <div class="mx-2 fs-2">${icon}</div>
            <div class="fs-6">${Escape(message)}</div>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>`;
  $('#messages').html(T);
  ShowToasts();
}

function ChangeCurrency(value, id, inputId = '') {
    ShowMonthlyData(value);
    if (value == "EUR" || value == "USD" || value == "RON") {
        $(id).html(value);
        if (inputId != '') {
            $(inputId).val(value);
        }
    }
}


async function ChangeCurrencyAccount(value, accountId, itemId = '') {
    accountCurrentCurrency = value;
    ChangeCurrency(value, itemId);
    let data = { 'currency': value, 'accountId': accountId };
    let accountData = await GetAccountDataAjax(data);

    if (accountData !== false) {
        let currentCurrency = '';
        let currencyAmount = '';
        switch (value) {
            case 'RON':
                currencyAmount = accountData.amountRON;
                currentCurrency = '<span class="fw-bolder ms-1"> lei</span>';
                break;
            case 'EUR':
                currencyAmount = accountData.amountEUR;
                currentCurrency = '<i class="bi bi-currency-euro"></i>';
                break;
            case 'USD':
                currencyAmount = accountData.amountUSD;
                currentCurrency = '<i class="bi bi-currency-dollar"></i>';
                break;
        }

        $('#accountBalance').html(currencyAmount);
        $(".accountCurrency").html(currentCurrency);
    }
    else {
        console.log('not ok');
    }

}

function TransactionTypeSelect(value) {
    if (value == 'Transfer')
        $('#transferToAccount').removeClass('d-none');
    else
        $('#transferToAccount').addClass('d-none');

}

function ChangeActiveTransactionType(value) {
    let id = '';
    for (let element of ['#btnDepunere', '#btnCheltuire', '#btnTransfer', '#btnToate'])
        $(element).removeClass('active');
    switch (value) {
        case 'Depunere':
            id = '#btnDepunere';
            break;
        case 'Cheltuire':
            id = '#btnCheltuire';
            break;
        case 'Transfer':
            id = '#btnTransfer';
            break;
        default:
            id = '#btnToate';
    }
    $(id).addClass('active');
}

function ShowMonthlyData(currency) {

    let spendings, deposits;
    switch (currency) {
        case 'USD':
            spendings = monthlySpendings['USD'];
            deposits = monthlyDeposits['USD'];
            break;
        case 'RON':
            spendings = monthlySpendings['RON'];
            deposits = monthlyDeposits['RON'];
            break;
        case 'EUR':
            spendings = monthlySpendings['EUR'];
            deposits = monthlyDeposits['EUR'];
            break;
    }
    $('#MonthlyDeposits').html(deposits.toFixed(2));
    $('#MonthlySpendings').html(spendings.toFixed(2));
    $('#MonthlyTransactions').html(monthlyTransactions);
}


//AJAX RELATED

async function GetCurrentMonthTransactions(accountId) {
    await $.ajax({
        type: 'POST',
        data: { 'accountId': accountId },
        url: 'Ajax/ajax-get-transactions-data.php',
        success: function (response) {
            let result = JSON.parse(response);
            ChangeMonthlyData(accountId, result.data);
        }
    });
}

async function GetAccountDataAjax(data) {

    var result;
    await $.ajax({
        type: "POST",
        url: "Ajax/ajax-get-account-data.php",
        data: data,
        success: function (response) {
            result = JSON.parse(response);
        }
    });

    if (result.success == '1')
        return result.accountData;
    else
        return false;

}

function ValidateAjax(id, errorId) {
    $(id).on("submit", function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "Ajax/ajax-validate-account.php",
            data: data,
            success: function (response) {

                var result = JSON.parse(response);
                if (result.success == '1') {
                    if (result.transaction == true) {
                        $(id).addClass('d-none');
                        $('#spinner').removeClass('d-none');
                        setTimeout(() => {
                            event.currentTarget.submit();
                        }, Math.floor(Math.random() * 2500) + 1000);
                    }
                    else
                        event.currentTarget.submit();
                }
                else if (result.success == '0') {
                    $(errorId).html(result.error);

                }

            }
        });
    });

}

async function DeleteTransaction(transactionId, transactionType, accountId, transactionBalance, transactionCurrency, transferToAccount, transferFromAccount) {
    
    let success = false;
    await $.ajax({
        type: 'POST',
        data: {
            'transactionId': transactionId,
            'transactionType': transactionType,
            'transactionBalance': transactionBalance,
            'transactionCurrency': transactionCurrency,
            'accountId': transferFromAccount,
            'transferToAccount': transferToAccount
        },
        url: 'Ajax/ajax-delete-transaction.php',
        success: function (response) {
            let result = JSON.parse(response);
            if (result.success == 1) {
                success = true;
                GetTransactionsAjax(accountId, result.transactionType);
                GetCurrentMonthTransactions(accountId);
                ChangeCurrencyAccount(accountCurrentCurrency, accountId);
            }
            else {
                console.log(result.error);
            }
        }
    });
    if(success)
    {
        CreateToast("Tranzacția a fors ștearsă cu succes!", "warning");
    }
    else{
        CreateToast("A apărut o eroare la ștergerea tranzacției!", "danger");
    }
}

function ShowTransactionTable(data, accountId, rows) {
    $.ajax({
        type: 'POST',
        data: { 'rows': rows },
        url: 'Ajax/ajax-session-rows.php'
    });
    var T = '';
    for (let element of data) {
        if (rows <= 0)
            break;
        rows--;
        if (element != null) {
            let id = 'transactionButton-' + element.transactionId;
            let idEdit = 'buttonEdit-' + element.transactionId;
            let idDelete = 'buttonDelete-' + element.transactionId;
            T += `
        <tr id="${id}">
            <td class="fw-bold text-${element.transactionType == 'Depunere' ? 'success' : element.transactionType == 'Cheltuire' ? 'danger' : 'warning'}">
            ${element.transactionType == 'Transfer' ? element.transferToAccount == accountId ? '<i class="bi bi-arrow-down-left"></i>' : '<i class="bi bi-arrow-up-right"></i>' : ''}
            ${element.transactionBalance}
            ${element.transactionCurrency == 'USD' ? '<i class="bi bi-currency-dollar"></i>' : element.transactionCurrency == 'EUR' ? '<i class="bi bi-currency-euro"></i>' : ' lei'}
            </td>
            <td >
                <div  class="d-flex flex-column">
                    <div class="text-info">
                    ${element.transactionMemo}  
                    </div>
                    <div>
                        ${element.transactionType}
                    </div>
                </div>
            </td>
            <td class="text-info">${element.transactionDate}</td>
            <td class="w-15">
                <div class="d-flex justify-content-center">
                    <button id='${idEdit}' class="btn btn-outline-success mx-1 d-none"><is class="bi bi-pencil-square"></i></button>
                    <button onclick="DeleteTransaction(${element.transactionId}, '${element.transactionType}',${accountId}, ${element.transactionBalance}, '${element.transactionCurrency}', '${element.transferToAccount}', ${element.accountId})" id='${idDelete}' class="btn btn-outline-danger mx-1 d-none"><i class="bi bi-trash3"></i></button>
                </div>
            </td>
        </tr>`;

        }
    };
    if (T != '') {
        $('#transactionTable').removeClass('d-none');
        if (!$('#noTransactions').hasClass('d-none')) {
            $('#noTransactions').addClass('d-none');
        }

        $('#transactionTableBody').html(T);
        for (let element of data) {

            if (element != null) {
                let id = 'transactionButton-' + element.transactionId;
                let idEdit = 'buttonEdit-' + element.transactionId;
                let idDelete = 'buttonDelete-' + element.transactionId;
                $('#' + id).on('mouseenter', function () {
                    $('#' + idEdit).removeClass('d-none');
                    $('#' + idDelete).removeClass('d-none');
                }).on('mouseleave', function () {
                    $('#' + idEdit).addClass('d-none');
                    $('#' + idDelete).addClass('d-none');
                });
            }
        }

    }
    else {
        if (!$('#transactionTable').hasClass('d-none')) {
            $('#transactionTable').addClass('d-none');
        }
        $('#noTransactions').removeClass('d-none');
    }
}


function GetTransactionsAjax(accountId, transactionType = 'Toate') {
    ChangeActiveTransactionType(transactionType);
    var data = {
        'accountId': accountId,
        'transactionType': transactionType
    };
    $.ajax({
        type: "POST",
        url: "Ajax/ajax-get-transactions-data.php",
        data: data,
        success: function (response) {
            result = JSON.parse(response);
            if (result.success == 1) {
                ShowTransactionTable(result.data, accountId, $('#rows').val());
                transactionsData = result.data;
            }
            else {
                console.log(result.error);
            }
        }
    });
}

//RANDOM FUNCTIONS

function ChangeMonthlyData(accountId, data) {


    let transactions = data.length - 1;
    let deposits = { 'USD': 0, 'RON': 0, 'EUR': 0 };
    let spendings = { 'USD': 0, 'RON': 0, 'EUR': 0 };
    for (let element of data) {
        if (element === null)
            break;
        switch (element.transactionType) {
            case 'Depunere':
                deposits[element.transactionCurrency] += parseFloat(element.transactionBalance);
                break;
            case 'Cheltuire':
                spendings[element.transactionCurrency] += parseFloat(element.transactionBalance);
                break;
            case 'Transfer':
                if (accountId == element.accountId)
                    spendings[element.transactionCurrency] += parseFloat(element.transactionBalance);
                else
                    deposits[element.transactionCurrency] += parseFloat(element.transactionBalance);
                break;
        }
    }
    monthlyDeposits = deposits;
    monthlySpendings = spendings;
    monthlyTransactions = transactions;
    ShowMonthlyData(accountCurrentCurrency);

}

async function ProfileAccontFunctionsAsync(accountId, accountCurrency, transactionType, itemId) {
    accountCurrentCurrency = accountCurrency;
    await GetCurrentMonthTransactions(accountId);
    GetTransactionsAjax(accountId, transactionType);
    await ChangeCurrencyAccount(accountCurrency, accountId, itemId);

}

///////////////////
//CODE TO EXECUTE//
///////////////////

$(window).on("load", function () {
    $('.loading-wrapper').fadeOut('slow');
    ShowToasts();

});


$(function () {

    var forms = [
        {
            'formId': '#createAccountForm',
            'errorId': '#createErrorDiv'
        },
        {
            'formId': '#editAccountForm',
            'errorId': '#editErrorDiv'
        },
        {
            'formId': '#addTransactionForm',
            'errorId': '#transactionErrorDiv'
        }
    ];
    forms.forEach(element => {
        ValidateAjax(element.formId, element.errorId);
    });

});

