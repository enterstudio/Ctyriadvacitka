//Init Ajax
$(function () {
    $.nette.init();
});

//Hide flash messages after 5 seconds
$(document).ready(function () {
    setTimeout(function () {
        $('.alert').fadeOut('fast');
    }, 5000); // <-- time in milliseconds
});
