$(function() {
    showLoader();
    
    $('.more-btn-container').hide();
    
    var URL = $('#getImagesList').attr('href');
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
            alert(errorThrown);
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
                alert(errorThrown);
                closeLoader();
            }
        });
        return false;
    });


    $('.modal-trigger').live('click', function() {
        
        regenerateForm($(this).parents('li').find('.image-id').attr('rel'));
        
        $('#modalQuickEdit').modal();
        showModalLoader();
        return false;
    });

   
    $('.save-trigger').live('click', function() {
        saveChanges($(this));
    });

});

function renderList(data, container) {
    var list = '';
    var dir = $('#dirPath').attr('href');
    var editPath = $('#editPath').attr('href');
    $.each(data.images, function(i, obj){
        list = list + '<li>' + renderMimeTypeThumbnail(obj, dir) +
            '<div class="image-grid-btns">' + 
                '<a class="hide image-id" rel="' + obj.id + '"></a>' +
                '<div class="edit-form-prototype hide">' + /*obj.editFormPrototype*/ '</div>' +
                '<input type="checkbox" data-toggle="tooltip" title="Remove" />' +
                '<a data-toggle="tooltip" title="Copy Source" href="' + dir + obj.path + '" class="copy-source"><i class="icon-screenshot"></i></a>' +
                '<a data-placement="right" class="modal-trigger" data-toggle="tooltip" title="Quick edit" href="' + editPath + '/' + obj.id + '"><i class="icon-pencil"></i></a>' +
                '<a data-placement="right" data-toggle="tooltip" title="Edit" href="' + editPath + '/' + obj.id + '"><i class="icon-edit"></i></a>' +
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
            } else if(data.result === 'error') {
                pinesNotify(noticeErrorTitle, noticeErrorText, 'error');
            }
            
            $('#modalQuickEdit').modal('hide');
            closeLoader();
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
var noticeErrorText = 'Data has not been updated successfuly.';
