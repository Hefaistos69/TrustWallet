

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

$(window).on("load", function () {
    $('.loading-wrapper').fadeOut('slow');
    ShowToasts();

});

$(function () {



    $('#addAccountForm').on("submit", function (event) {
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
                    event.currentTarget.submit();
                }
                else if (result.success == '0') {

                    $("#createErrorDiv").html(result.error);

                }

            }
        });

    });
    $('#editAccountForm').on("submit", function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "Ajax/ajax-validate-account.php",
            data: data,
            success: function (response) {
                var result = JSON.parse(response);
                if (result.success == '1') {
                    event.currentTarget.submit();
                }
                else if (result.success == '0') {
                    console.log(result.error);
                    $("#editErrorDiv").html(result.error);

                }

            }
        });

    })
});

