$(function(){
    $('a.sample-trigger').click(function(){
        
        eq = Math.floor((Math.random()*6));

        tinymce.editors[0].execCommand(
            'mceSetContent', 
            false, 
            $('.sample-content-container div:eq(' + eq + ')').html()
        );        

        return false;
    }); 
});