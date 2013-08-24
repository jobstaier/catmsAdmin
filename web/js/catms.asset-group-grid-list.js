$(function() {
    showLoader();
    
    $('.more-btn-container').hide();
    
    var URL = $('#getGroupImagesList').attr('href');
    var page = parseInt($('.grid-list').attr('data-view') + 1);
    
    $.ajax({
        type: 'GET',
        url: URL,
        dataType: 'json',
        data: {'page': page},
        success: function(data) {
            if (data.images) {
                renderList(data, $('.grid-list'));
                $('.grid-list').attr('data-view', page);
            } else {
                $('.grid').html(notice);
            }
            closeLoader();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            pinesNotify('Error occured!', errorThrown, 'error');
            closeLoader();
        }
    });
    
    $('.more-btn-container a.btn').click(function() {
        showLoader();
        var page = parseInt($('.grid-list').attr('data-view')) + 1;

        $.ajax({
            type: 'GET',
            url: URL,
            dataType: 'json',
            data: {'page': page},
            success: function(data) {
                renderList(data, $('.grid-list'));
                closeLoader();
                $('.grid-list').attr('data-view', page);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                pinesNotify('Error occured!', errorThrown, 'error');
                closeLoader();
            }
        });
        return false;
    });
    
    // New
    $('.modal-trigger').live('click', function() {
        
        regenerateForm($(this).parents('li').find('.image-id').attr('rel'));
        
        $('#modalQuickEdit').modal();
        showModalLoader();
        return false;
    });    
    
        $('.save-trigger').live('click', function() {
        saveChanges($(this));
    });
    
    $('.remove-image').live('click', function() {
        return false;
    });
    
    $('.remove-image').live('mousedown', function() {
        var placement = ($(this).data('placement') !== 'undefined') ? $(this).data('placement') : 'left';
        var url = $(this).attr('href');

        $(this).popover({
            content: 
                    '<div class="delete-popvoer">Are you sure you want to delete this item?<br />' + 
                    '<a data-path="' + url + '" href="" class="btn btn-primary btn-mini remove-image-confirm">Confirm</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-inverse btn-mini dismiss">Dismiss</a></div>',
            placement: placement,
            html: true           
        });
    });
    
    $('.dismiss').live('click', function() {
        $(this).parents('div.popover').prev('a.remove-image').popover('hide');
    });

    $('.remove-image-confirm').live('click', function() {
        $(this).parents('div.popover').prev('a.remove-image').popover('hide');
        $(this).parents('li').fadeOut(500);
        window.setTimeout(function() {
            $(this).parents('li').remove();
        }, 500);
        
        showLoader();
        
        var URL = $(this).data('path');

        $.ajax({
            type: 'POST',
            url: URL,
            dataType: 'json',
            data: null,
            success: function(data) {
                if(data.result === 'success') {
                    pinesNotify(noticeSuccessTitle, noticeSuccessDeleteText, 'success');
                    closeLoader();
                } else if(data.result === 'error') {
                    pinesNotify(noticeErrorTitle, noticeErrorText, 'error');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                pinesNotify('Error occured!', errorThrown, 'error');
                closeLoader();
            }
        });

        return false;
    });
    // End

});

function renderList(data, container) {
    var list = '';
    var dir = $('#dirPath').attr('href');
    var editPath = $('#editPath').attr('href');
    $.each(data.images, function(i, obj){
        list = list + '<li>' + renderMimeTypeThumbnail(obj, dir) +
            '<div class="image-grid-btns">' + 
                '<a class="hide image-id" rel="' + obj.id + '"></a>' +
                '<div class="edit-form-prototype hide"></div>' +
                '<a data-placement="top" data-toggle="popover" title="Remove" class="remove-image"  href="' + obj.deletePath + '"><i class="icon-trash"></i></a>' +
                '<a data-placement="top" data-toggle="tooltip" title="Copy Source" href="' + dir + obj.path + '" class="copy-source"><i class="icon-screenshot"></i></a>' +
                '<a data-placement="top" data-toggle="tooltip" title="Quick edit" class="modal-trigger"   href="' + editPath + '/' + obj.id + '"><i class="icon-pencil"></i></a>' +
                '<a data-placement="top" data-toggle="tooltip" title="Edit" href="' + editPath + '/' + obj.id + '/' + obj.imageGroup +'"><i class="icon-edit"></i><a/>' +
            '</div></li>';
    });
    container.append(list);
    
    if (data.hasMore) {
        $('.more-btn-container').show();
    } else {
        $('.more-btn-container').hide();
    }
}

function saveChanges(el) {
    showLoader();
    
    var form = el.parents('#modalQuickEdit').find($('form.inline-edit-form'));
    var data = {
        'asset_form[id]'            : form.find($('input[name="asset_form[id]"]')).val(),
        'asset_form[title]'         : form.find($('input[name="asset_form[title]"]')).val(),
        'asset_form[priority]'      : form.find($('input[name="asset_form[priority]"]')).val(),
        'asset_form[redirect]'      : form.find($('input[name="asset_form[redirect]"]')).val(),
        'asset_form[slug]'          : form.find($('input[name="asset_form[slug]"]')).val()
    };

    var URL = $('#editInlinePath').attr('href');

    $.ajax({
        type: 'POST',
        url: URL,
        dataType: 'json',
        data: data,
        success: function(data) {
            if(data.result === 'success') {
                pinesNotify(noticeSuccessTitle, noticeSuccessText, 'success');
                closeLoader();
                $('#modalQuickEdit').modal('hide');
            } else if(data.result === 'error') {
                closeLoader();
                pinesNotify(noticeErrorValidationTitle, noticeErrorText, 'error');
                $.each(data.errors, function(key, error) {
                    $.each(error, function(i, message) {
                    pinesNotify(noticeErrorTitle, message, 'error');
                    });
                });
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            pinesNotify('Error occured!', errorThrown, 'error');
            $('#modalQuickEdit').modal('hide');
            closeLoader();
        }
    });
    
    return false;
}

function regenerateForm(assetId) {
    showLoader();
    var URL = $('#editInlineRegeneratePath').attr('href');
    $.ajax({
        type: 'GET',
        url: URL,
        dataType: 'json',
        data: {'id': assetId},
        success: function(data) {
            $('.modal-body').html(data.editFormPrototype);
            closeLoader();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            pinesNotify('Error occured!', errorThrown, 'error');
            $('#modalQuickEdit').modal('hide');
            closeLoader();
        }
    });
    closeLoader();
}

var notice = '<div class="alert">' +
    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
    '<strong>Empty databse!</strong> No records defined.' +
    '</div>';
    
var noticeSuccessTitle = 'Success!';
var noticeSuccessText = 'Data has been updated successfuly.';

var noticeErrorTitle = 'Error occured!';
var noticeErrorValidationTitle = "Validation error."
var noticeErrorText = 'Data has not been updated successfuly.';

var noticeSuccessDeleteText = 'Delete success!';
var noticeSuccessDeleteText = 'Asset has been deleted successfuly.';    