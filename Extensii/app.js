/////////////
//VARIABLES//
/////////////

var transactionsData;
var userAccounts = [];
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

function CollapseArrow(id) {
    id = '#' + id;

    if (!$(id).hasClass('i')) {
        $(id + '-arrow').html('<i class="bi bi-caret-down-fill"></i>');
        $(id).addClass('i');
    }
    else {
        $(id + '-arrow').html('<i class="bi bi-caret-up-fill"></i>');
        $(id).removeClass('i');
    }
}

function ChangeCurrency(value, id, inputId = '') {

    if (value == "EUR" || value == "USD" || value == "RON") {
        $(id).html(value);
        if (inputId != '') {
            $(inputId).val(value);
        }
    }
}


async function ChangeCurrencyAccount(value, accountId, itemId = '') {
    accountCurrentCurrency = value;
    ShowMonthlyData(value);
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

function TransactionTypeSelect(value, id) {
    if (value == 'Transfer')
        $(id).removeClass('d-none');
    else
        $(id).addClass('d-none');

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
                    else;
                       event.currentTarget.submit();
                }
                else if (result.success == '0') {
                    $(errorId).html(result.error);

                }

            }
        });
    });

}

function ValidateTransactionEditAjax(form, errorId, transaction)
{
    transaction = JSON.parse(transaction);
    $.ajax({
        type: "POST",
        url: "Ajax/ajax-validate-account.php",
        data: $(form).serialize(),
        success: function (response) {
            console.log(response);
            var result = JSON.parse(response);
            if (result.success == '1') {
                $(errorId).html('');
                $.ajax({
                    
                });

            }
            else if (result.success == '0') {
                $(errorId).html(result.error);

            }

        }
    });
    return false;
}

function EditTransaction(id, element, accountId) {
    let itemsSelect = '';
    for (item of userAccounts) {
        if (item.accountId == element.accountId)
            continue;
        itemsSelect += `<option ${item.accountId == element.transferToAccount ? 'selected' : ''} value="${item.accountId}">${item.accountName}</option>`
    }
    let selectAccount = '';
    if (itemsSelect == '') {
        selectAccount = `<div id="transferToAccount-edit-${element.transactionId}" class="fs-6 text-warning ${element.transactionType == 'Transfer' ? '' : 'd-none'}">Nu exista cont de transfer!</div>
                        <input type="hidden" name="transferToAccount" value="">`;
    }
    else {
        selectAccount = `
    <div>
        <div class="${element.transactionType == 'Transfer' ? '' : 'd-none'}" id="transferToAccount-edit-${element.transactionId}">
            
            <select form="editTransactionForm-${element.transactionId}" name="transferToAccount" class="form-select form-select-sm text-info bg-dark border-secondary border-1 w-auto">
                <option value="">Alege contul</option>
                ${itemsSelect}
             </select>
                
        </div>
    </div>`;
    }
    let trsType = '';
    if (accountId == element.transferToAccount) {
        let account = undefined;
        for (item of userAccounts) {
            if (item.accountId == element.accountId) {
                account = item;
                break;
            }
        }
        if (account !== undefined)
            trsType = `<div  class="fs-6 text-info">Transfer din contul ${account.accountName}.</div>`;
        else
            trsType = `<div  class="fs-6 text-info">Transfer.</div>`;

        trsType += `<input type="hidden" name="transactionType" value="Transfer" form="editTransactionForm-${element.transactionId}">`;

    }
    else {
        trsType = `
        <div class="me-2">
            <select form="editTransactionForm-${element.transactionId}" onchange="TransactionTypeSelect(this.value, '#transferToAccount-edit-${element.transactionId}')" class="form-select form-select-sm text-info bg-dark border-secondary border-1 w-auto" name="transactionType">
                <option value="">Tipul tranzacției</option>
                <option ${element.transactionType == 'Depunere' ? 'selected' : ''} value="Depunere">Depunere</option>
                <option ${element.transactionType == 'Cheltuire' ? 'selected' : ''} value="Cheltuire">Cheltuire</option>
                <option ${element.transactionType == 'Transfer' ? 'selected' : ''} value="Transfer">Transfer</option>
            </select>
        </div>
        ${selectAccount}`;
    }

    let T = `
    <form onsubmit="return ValidateTransactionEditAjax(this,'#editTransactionErrorDiv-${element.transactionId}', '${Escape(JSON.stringify(element))}');" action="" method="POST" id="editTransactionForm-${element.transactionId}" class="d-flex align-items-center"></form>
    <td class="w-25">
        <input type="hidden" name="editTransaction" form="editTransactionForm-${element.transactionId}">
        <input type="hidden" name="transferToAccount" value="${element.transferToAccount}" form="editTransactionForm-${element.transactionId}">
        <input type="hidden" name="transactionId" value="${element.transactionId}" form="editTransactionForm-${element.transactionId}">

        <div class="d-flex align-items-center">
        <label for="transactionBalanceEdit-${element.transactionId}" class="from-label text-info fs-6 me-2 my-auto">Suma</label>
        <div class="input-group input-group-sm my-auto ">


          <span class="input-group-text border-secondary bg-dark text-info">
            <div class="dropdown text-center">
              <a style="cursor: pointer;" class="text-decoration-none fs-6 text-info dropdown-toggle" id="editTranzactiiValutaDropdown" data-bs-toggle="dropdown" aria-expanded="false">${element.transactionCurrency}</a>

              <ul class="dropdown-menu dropdown-menu-dark " aria-labelledby="editTranzactiiValutaDropdown">
                <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrency('USD', '#editTranzactiiValutaDropdown', '#editTransactionCurrency')">USD</a></li>
                <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrency('EUR', '#editTranzactiiValutaDropdown', '#editTransactionCurrency')">EUR</a></li>
                <li><a style="cursor: pointer;" class="dropdown-item" onclick="ChangeCurrency('RON', '#editTranzactiiValutaDropdown', '#editTransactionCurrency')">RON</a></li>
              </ul>
            </div>

            </span>
            <input form="editTransactionForm-${element.transactionId}" name="transactionCurrency" id="editTransactionCurrency" type="hidden" value="${element.transactionCurrency}">

            <input form="editTransactionForm-${element.transactionId}" value="${element.transactionBalance}" name="transactionBalance" id="transactionBalanceEdit-${element.transactionId}" type="text" class="form-control text-info bg-dark border-secondary border-1" aria-label="Amount (to the nearest dollar)">
            <span class="input-group-text bg-dark border-secondary text-info">.00</span>
            </div>
        </div>

        
    </td>
    <td class="w-25 ms-2">
        <div class="d-flex align-items-center mb-3">
            <label for="transactionMemoEdit-${element.transactionId}" class="form-label text-info fs-6 my-auto me-2">Notiță</label>
            <input form="editTransactionForm-${element.transactionId}" value="${element.transactionMemo}" id="transactionMemoEdit-${element.transactionId}" name="transactionMemo" type="text" class="form-control form-control-sm text-info bg-dark border-secondary border-1" placeholder="max. 20 de caractere">
        </div>
        <div id="editTransactionErrorDiv-${element.transactionId}">
        </div>
    </td>
    <td class="w-25 ms-2">
    <div class="d-flex ">
        ${trsType}
    </div>
    </td>
    <td class="w-15">
        <div class="d-flex justify-content-center ">
            <button onclick="ExitEdit('${Escape(JSON.stringify(element))}', '${id}', ${accountId})" class="btn btn-outline-danger mx-1"><i class="bi bi-x-lg"></i></button>
            <button type="submit" form="editTransactionForm-${element.transactionId}" class="btn btn-outline-success mx-1"><i class="bi bi-check-lg"></i></button>
        </div>
    </td>
    
    `;
    $('#' + id).html(T);
}

function ExitEdit(element, id, accountId) {
    element = JSON.parse(element);
    $('#' + id).html(MakeTransactionRow(element, id, accountId))
}

function MakeTransactionRow(element, id, accountId) {
    let idEdit = 'buttonEdit-' + element.transactionId;
    let idDelete = 'buttonDelete-' + element.transactionId;

    let trsType = '';
    if (element.transactionType == 'Transfer') {
        let otherAccountId
        if (element.transferToAccount == accountId) {
            otherAccountId = element.accountId;
        }
        else {
            otherAccountId = element.transferToAccount;
        }

        let otherAccount = undefined;
        for (item of userAccounts) {
            if (item.accountId == otherAccountId) {
                otherAccount = item;
                break;
            }
        }
        if(otherAccount === undefined)
        {
            trsType = 'Transfer';
        }
        else
        {
            trsType = `Transfer ${element.accountId == accountId? 'către' : 'din'} ${otherAccount.accountName}.`;
        }
    }
    else {
        trsType = element.transactionType;
    }

    let T = `
        <td class="w-25 ms-2  text-${element.transactionType == 'Depunere' ? 'success' : element.transactionType == 'Cheltuire' ? 'danger' : 'warning'}">
        ${element.transactionType == 'Transfer' ? element.transferToAccount == accountId ? '<i class="bi bi-arrow-down-left"></i>' : '<i class="bi bi-arrow-up-right"></i>' : ''}
        ${element.transactionBalance}
        ${element.transactionCurrency == 'USD' ? '<i class="bi bi-currency-dollar"></i>' : element.transactionCurrency == 'EUR' ? '<i class="bi bi-currency-euro"></i>' : ' lei'}
        </td>
        <td class="w-25 ms-2">
            <div  class="d-flex flex-column">
                <div class="text-info">
                ${element.transactionMemo}  
                </div>
                <div>
                ${trsType}
                </div>
            </div>
        </td>
        <td class="w-25 ms-2 text-info">${element.transactionDate}</td>
        <td class="w-15">
            <div class="d-flex justify-content-center">
                
                <button onclick="EditTransaction('${id}', ${Escape(JSON.stringify(element))}, ${accountId})" id='${idEdit}' class="btn btn-outline-success mx-1 d-none"><i class="bi bi-pencil-square"></i></button>
                <button onclick="DeleteTransaction(${element.transactionId}, '${element.transactionType}',${accountId}, ${element.transactionBalance}, '${element.transactionCurrency}', '${element.transferToAccount}', ${element.accountId})" id='${idDelete}' class="btn btn-outline-danger mx-1 d-none"><i class="bi bi-trash3"></i></button>
            </div>
        </td>`;
    return T;
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
    if (success) {
        CreateToast("Tranzacția a fors ștearsă cu succes!", "warning");
    }
    else {
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

            T += `<tr id="${id}">`;
            T += MakeTransactionRow(element, id, accountId);
            T += `</tr>`

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
        },
        
    ];
    forms.forEach(element => {
        ValidateAjax(element.formId, element.errorId);
    });

});

