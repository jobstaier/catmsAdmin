tinymce.init({
    selector: "textarea.tiny_full",
    width : 938,
    height: 420,
    remove_linebreaks : false,
    plugins: "code, textcolor, table",
    tools: "inserttable",
    toolbar: "undo redo | styleselect | bold italic underline strikethrough | lignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code | forecolor"
});

tinymce.init({
    selector: "textarea.tiny_half",
    width : 433,
    height: 170,
    remove_linebreaks : false,
    plugins: "code, textcolor, table",
    tools: "inserttable",
    toolbar: "undo redo | styleselect | bold italic underline strikethrough | lignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code | forecolor"
});

$(document).ready(function() {
    
    /*
    $('.before-tiny').next().hide();
    $('.content-manager-form label.before-tiny').click(function(){
        var tinyHandler = $(this).next();
        if (tinyHandler.hasClass('visible-tiny')) {
            tinyHandler.removeClass('visible-tiny');
            tinyHandler.slideUp(300);
            $(this).children('i').removeClass('icon-chevron-up');
            $(this).children('i').addClass('icon-chevron-down');
        } else {
            tinyHandler.slideDown(300);
            $(this).next().addClass('visible-tiny');
            $(this).children('i').removeClass('icon-chevron-down');
            $(this).children('i').addClass('icon-chevron-up');
        }
    });
    */
});