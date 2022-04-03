$(window).on("load", function () {
    setTimeout(() => {
        $('.loading-wrapper').fadeOut('slow');
        ShowToasts();
    }, 500);
});

function ShowToasts() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl);
    })
    toastList.forEach(toast => toast.show());
}

function ChangeCurrency(value) {
    if (value == "EUR" || value == "USD" || value == "RON")
        $("#spanSuma").html(value);
}

function ChangeCurrencyAccount(value, accountId)
{   
    if (value == "EUR" || value == "USD" || value == "RON")
        $("#dropdownMenuButton1").html(value);
    let data = {'currency': value, 'accountId': accountId};
    $.ajax({
        type: "POST",
        url: "Ajax/ajax-get-account-data.php",
        data: data,
        success: function(response){
            let result = JSON.parse(response);
            console.log(result.success);
            if(result.success == '1')
            {
                console.log('ok');
            }
            else
            {
                console.log('not ok');
            }
        }
    });
}


$(function () {
    $('#addAccountForm').on("submit", function (event) {
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

