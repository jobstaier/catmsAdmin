$(function(){
    $('.input-wrapper label, .content-group-fields .chevronized label').prepend('<i class="icon-chevron-right"></i>');
    $('.select-icons option[value="777"]').prepend('<i class="icon-ok-circle"></i>');
    $('.select-icons option[value="755"]').prepend('<i class="icon-warning-sign"></i>');
    $('.select-icons option[value="000"]').prepend('<i class="icon-remove-circle"></i>');

    $('.field-label input').keyup(function(){
        if ($(this).val() !== '') {
            $(this).parents('.row-fluid').find('input[type="checkbox"]').attr('checked', 'checked');
        }
    });

    $('.field-label input').each(function(){
         var checkbox = $(this).parents('.row-fluid').find('input[type="checkbox"]');
         if (!checkbox.attr('checked')) {
             $(this).val('').attr('disabled', 'disabled');
         }
    });

    $('.content-group-fields input[type="checkbox"]').change(function(){
        var input = $(this).parents('.row-fluid').find('input[type="text"]');
        if ($(this).is(':checked')) {
            input.attr("disabled", false);
        } else {
            input.attr("disabled", "disabled");
        }
    });
});