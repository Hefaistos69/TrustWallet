$(window).on("load", function () {
    setTimeout(() => {
        $('.loading-wrapper').fadeOut('slow');
        ShowToasts();
    }, 500);
});

function ShowToasts()
{
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl);
    })
    toastList.forEach(toast => toast.show());
}

function ChangeCurrency(value)
{
    if(value == "EUR" || value == "USD" || value == "RON")
        $("#spanSuma").html(value);
}

