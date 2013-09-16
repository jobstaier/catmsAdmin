jQuery(document).ready(function($) {
     
     
    //* Global placeholder
    //-------------------------
    $('input[placeholder]').each(function(){  
        var input = $(this);        
        $(input).val(input.attr('placeholder'));
                
        $(input).focus(function(){
            if (input.val() == input.attr('placeholder')) {
                input.val('');
            }
        });
        
        $(input).blur(function(){
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.val(input.attr('placeholder'));
            }
        });
    });
                
                
    //* Popup
    //-------------------------         
     
    $(".inline").colorbox({
        inline:true, 
        opacity: 0.7, 
        closeButton: false
    });                
    $("#cboxTopLeft").hide();
    $("#cboxTopRight").hide();
    $("#cboxBottomLeft").hide();
    $("#cboxBottomRight").hide();
    $("#cboxMiddleLeft").hide();
    $("#cboxMiddleRight").hide();
    $("#cboxTopCenter").hide();
    $("#cboxBottomCenter").hide(); 
    
    
    
    
    //* Textarea placeholder
    //-------------------------                                
    var standard_message = $('.textAreaPlaceholder').val();
    $('.textAreaPlaceholder').focus(
        function() {
            if ($(this).val() == standard_message)
                $(this).val("");
        }
        );
    $('.textAreaPlaceholder').blur(
        function() {
            if ($(this).val() == "")
                $(this).val(standard_message);
        }
        );
    
    
    
    
    
     
});


