$(window).on("load", function () {
    setTimeout(() => {
        $('.loading-wrapper').fadeOut('slow');
    }, 1000);
});


$(document).ready(function () {
    
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl)
    })
    toastList.forEach(toast => toast.show())
});