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
            alert(errorThrown);
            closeLoader();
        }
    });
    
    $('.more-btn-container a.btn').click(function() {
        showLoader();
        var page = parseInt($('.grid-list').attr('data-view')) + 1;

        $.ajax({
            type: 'POST',
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

});

function renderList(data, container) {
    var list = '';
    var dir = $('#dirPath').attr('href');
    var editPath = $('#editPath').attr('href');
    $.each(data.images, function(i, obj){
        list = list + '<li>' + renderMimeTypeThumbnail(obj, dir) +
            '<div class="image-grid-btns">' + 
            '<a data-toggle="tooltip" title="Copy Source" href="' + dir + obj.path + '" class="copy-source"><i class="icon-screenshot"></i></a>' +
            '<a data-placement="right" data-toggle="tooltip" title="Edit" href="' + editPath + '/' + obj.id + '/' + obj.imageGroupId +'"><i class="icon-edit"></i><a/>' +
            '</div></li>';
    });
    container.append(list);
    
    if (data.hasMore) {
        $('.more-btn-container').show();
    } else {
        $('.more-btn-container').hide();
    }
}

var notice = '<div class="alert">' +
    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
    '<strong>Empty databse!</strong> No records defined.' +
    '</div>';