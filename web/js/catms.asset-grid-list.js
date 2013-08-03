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

    /*
    $('.modal-trigger').live('click', function() {
        $('.modal-body').html($(this).parent().find($('.edit-form-prototype')).html());
        $('#modalQuickEdit').modal();
        return false;
    });
    */
   
    /*
    $('.save-trigger').live('click', function() {
        saveChanges($(this));
    });
    */
});

function renderList(data, container) {
    var list = '';
    var dir = $('#dirPath').attr('href');
    var editPath = $('#editPath').attr('href');
    $.each(data.images, function(i, obj){
        list = list + '<li>' + renderMimeTypeThumbnail(obj, dir) +
            '<div class="image-grid-btns">' + 
                '<div class="edit-form-prototype hide">' + obj.editFormPrototype +'</div>' +
                //'<input type="checkbox" data-toggle="tooltip" title="Remove" />' +
                '<a data-toggle="tooltip" title="Copy Source" href="' + dir + obj.path + '" class="copy-source"><i class="icon-screenshot"></i></a>' +
                '<a data-placement="right" data-toggle="tooltip" title="Edit" href="' + editPath + '/' + obj.id + '"><i class="icon-edit"></i></a>' +
                //'<a data-placement="right" class="modal-trigger" data-toggle="tooltip" title="Quick edit" href="' + editPath + '/' + obj.id + '"><i class="icon-pencil"></i></a>' +
            '</div></li>';
    });
    container.append(list);
    
    if (data.hasMore) {
        $('.more-btn-container').show();
    } else {
        $('.more-btn-container').hide();
    }
}

/*
function saveChanges(el) {
    var form = el.parents('#modalQuickEdit').find($('form.inline-edit-form'));
    console.log(form.html());
    var data = {
        'id'            : form.find($('#asset_form_id')).val(),
        'title'         : form.find($('#asset_form_title')).val(),
        'priority'      : form.find($('#asset_form_priority')).val(),
        'redirect'      : form.find($('#asset_form_redirect')).val(),
        'slug'          : form.find($('#asset_form_slug')).val(),
        'imageGroup'    : form.find($('#asset_form_imageGroup')).val()
    };
    console.log(data);
    return false;
}
*/

var notice = '<div class="alert">' +
    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
    '<strong>Empty databse!</strong> No records defined.' +
    '</div>';
    