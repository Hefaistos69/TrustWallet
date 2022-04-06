

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

function ChangeCurrencyAccount(value, accountId, itemId) {
    ChangeCurrency(value, itemId);
    let data = { 'currency': value, 'accountId': accountId };
    $.ajax({
        type: "POST",
        url: "Ajax/ajax-get-account-data.php",
        data: data,
        success: function (response) {
            let result = JSON.parse(response);
            if (result.success == '1') {
                let currentCurrency = '';
                let currencyAmount = '';
                switch (value) {
                    case 'RON':
                        currencyAmount = result.accountData.amountRON;
                        currentCurrency = '<span class="fw-bolder ms-1"> lei</span>';
                        break;
                    case 'EUR':
                        currencyAmount = result.accountData.amountEUR;
                        currentCurrency = '<i class="bi bi-currency-euro"></i>';
                        break;
                    case 'USD':
                        currencyAmount = result.accountData.amountUSD;
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
    });
}

function TransactionTypeSelect(value) {
    if (value == 'Transfer')
        $('#transferToAccount').removeClass('d-none');
    else
        $('#transferToAccount').addClass('d-none');

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
                console.log(response);

                var result = JSON.parse(response);
                if (result.success == '1') {
                    if (result.transaction == true) {
                        $(id).addClass('d-none');
                        $('#spinner').removeClass('d-none');
                        setTimeout(() => {
                            // $('#spinner').addClass('d-none');
                            // $(id).removeClass('d-none');
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

