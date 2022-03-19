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
                    // $.ajax({
                    //     type: 'POST',
                    //     url: 'Scripturi/script-add-account.php',
                    //     data: data,
                    //     success: function(response){
                    //         var result = JSON.parse(response);
                    //         if(result.success == '1')
                    //         {
                                
                    //         }
                    //     }
                    // });
                }
                else if (result.success == '0') {

                    $("#errorDiv").html(result.error);

                }

            }
        });
        
    })
});

