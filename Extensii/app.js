/////////////
//VARIABLES//
/////////////

var transactionsData;

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

function ChangeCurrency(value, id, inputId = '') {
    if (value == "EUR" || value == "USD" || value == "RON") {
        $(id).html(value);
        if (inputId != '') {
            $(inputId).val(value);
        }
    }
}

function SelectTransactions(value) {
    switch (value) {
        case 'Toate':

    }
}

async function ChangeCurrencyAccount(value, accountId, itemId) {
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
    for(let element of ['#btnDepunere', '#btnCheltuire', '#btnTransfer', '#btnToate'])
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


//AJAX RELATED

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
            T += `
        <tr >
            <td class="fw-bold text-${element.transactionType == 'Depunere' ? 'success' : element.transactionType == 'Cheltuire' ? 'danger' : 'warning'}">
            ${element.transactionType == 'Transfer' ? element.transferToAccount == accountId ? '<i class="bi bi-arrow-down-left"></i>' : '<i class="bi bi-arrow-up-right"></i>' : ''}
            ${element.transactionBalance}
            ${element.transactionCurrency == 'USD' ? '<i class="bi bi-currency-dollar"></i>' : element.transactionCurrency == 'EUR' ? '<i class="bi bi-currency-euro"></i>' : ' lei'}
            </td>
            <td>
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
            <td></td>
        </tr>`;
        }
    };
    if (T != '') {
        $('#transactionTable').removeClass('d-none');
        if (!$('#noTransactions').hasClass('d-none')) {
            $('#noTransactions').addClass('d-none');
        }

        $('#transactionTableBody').html(T);
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

