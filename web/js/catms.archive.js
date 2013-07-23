$(function(){
    $('.archive-container').hide();
    
    $('.archive-trigger-container a').click(function(){

        if ($(this).hasClass('active')){
            $(this).removeClass('active');
            $(this).children('i').removeClass('icon-chevron-right').addClass('icon-chevron-left');
            $('.archive-container').fadeOut(500);
        } else {
            $(this).addClass('active');
            $(this).children('i').removeClass('icon-chevron-left').addClass('icon-chevron-right');
            $('.archive-container').fadeIn(500);
        }

        return false;
    });
    
    $('.import-archive').click(function(){
        
        getArchiveAjax($(this));
        
        return false;
    });
});

function getArchiveAjax(trigger) {
    var URL = trigger.attr('href');
    showLoader();
    $.ajax({
        type: 'GET',
        url: URL,
        dataType: 'json',
        data: null,
        success: function(data) {
            importContent(data);
            closeLoader();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(errorThrown);
            closeLoader();
        }
    });
    
}

function importContent(data) {
    $('#catms_adminbundle_contentmanagertype_slug').val(data.slug);
    $('#catms_adminbundle_contentmanagertype_description').val(data.description);
    $('#catms_adminbundle_contentmanagertype_priority').val(data.priority);
    
    tinymce.editors[0].execCommand('mceSetContent', false, data.fullText);

    try {
        content = (data.shortText !== null) ? data.shortText : '';
        tinymce.editors[1].execCommand('mceSetContent', false, content);
    } catch(err) {
        console.log(err);
    }
        


}