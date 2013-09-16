var loaderGif = '/img/loading.gif';

function showLoader(){
    $.fancybox(
        '<div style="text-align: center"><img src="' + loaderGif + '" /></div>',
        {
            'autoDimensions'	: false,
            'width'         	: 350,
            'height'        	: 70,
            'transitionIn'	: 'none',
            'transitionOut'	: 'none',
            'padding'           : '20px',
            'hideOnOverlayClick': false,
            'showCloseButton'   : false,
            'centerOnScroll'    : true,
            'overlayShow'	: false
        }
    );
}

function closeLoader(){
    $.fancybox.close();
}