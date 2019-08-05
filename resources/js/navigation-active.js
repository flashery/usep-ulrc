$(document).ready(function() {
    var url = window.location.pathname;
    $('.nav-item > a[href="' + url + '"]')
        .parent()
        .addClass("active");
});
