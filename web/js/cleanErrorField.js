$(document).ready(function() {
    var container = $('.error-field ul');
    var msg = container.text();
    container.remove();
    $('.error-field').append(msg)
});
