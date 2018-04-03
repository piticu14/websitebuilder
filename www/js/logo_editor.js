
/** TODO: After selecting image title,subtitle gets old text */
$('#logoModal').on('shown.bs.modal', function (e) {
    e.preventDefault();

    var logo_edit = $('#logo_edit_modal');
    var logo_container = $('#logo_container');
    var selected_logo = $('#selected');

    $(logo_edit).find('input[name="title"]').val(logo_container.find('#title').text());
    $(logo_edit).find('input[name="subtitle"]').val(logo_container.find('#subtitle').text());


    $('#save_logo').on('click', function () {
        var title = $(logo_edit).find('input[name="title"]').val();
        var subtitle = $(logo_edit).find('input[name="subtitle"]').val();

        if(selected_logo.length){
            var logo_image_container =  $('#logo_image_container');
            logo_image_container.empty();
            if(selected_logo.is('div')) {
                var logo_image = $('<img>');
                logo_image.attr('width','100px');
                logo_image.attr('height','100px');
                logo_image.attr('alt','User Image');
                logo_image.attr('src',$(selected_logo).find('img').attr('src'));
                logo_image_container.append(logo_image);
            } else {
                var logo_icon = $(selected_logo).children('span').clone();
                logo_icon.css('font-size','5.6em'); // Base width: 18px, 100px = 5.6em
                logo_image_container.append(logo_icon);
            }
        }
        logo_container.find('#title').text(title);
        logo_container.find('#subtitle').text(subtitle);



        var text_color = logo_edit.find('.bfh-colorpicker').val();
        if(text_color) {
            logo_container.find('#title').css('color',text_color);
            logo_container.find('#subtitle').css('color',text_color);

        }

    });
});

function logoImageModal(){
    $(document).on('click','#logo_icons',function(e){
        e.preventDefault();
        $('#user_images').hide();
        $('.bt-glyphicons').fadeIn('slow');
    });
    $(document).on('click','#logo_images',function(e){
        e.preventDefault();
        $('.bt-glyphicons').hide();
        $('#user_images').fadeIn('slow');
    });

    logoSelection();

}
$('#logoImageModal').on('shown.bs.modal', function () {
    $('#user_images').hide().fadeIn('slow');
    $('.bt-glyphicons').hide();
    logoImageModal();
});

$('#addImage').find('input[name="images"]').on('change',function(){
    $('#upload_bar').show();
    files = $(this)[0].files;
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });

    // If you want to add an extra field for the FormData
    //data.append("CustomField", "This is some extra data, testing");

    $.nette.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: $(this).data('url'),
        data: data,
        processData: false,
        contentType: false,
        cache: false,

        // Can use it for progess bar
        xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.onprogress = function(e) {
               var percent = (Math.floor(e.loaded / e.total *100) + '%');
                $('#upload_bar div.progress-bar').css('width',percent).text(percent);
            };
            return xhr;
        },

        success: function (data) {
            $('#upload_bar div.progress-bar').css('width',0).text("");
            $('#upload_bar').hide();
        },

        error: function(jqXHR,status,error) {
            console.log(jqXHR);
            console.log(status);
            console.log(error);
        }
    });
});

$(document).ajaxStop(function() {
    if($('#logoImageModal').length){
        logoSelection();
    }
});

function logoSelection() {

    var selected_image;
    var selected_icon;
    $('.overlay').not('div#selected .overlay').hide();
    $('.image_box').on('click',function() {
        if (selected_image) {
            selected_image.parent().removeAttr('id');
            selected_image.hide();
        }
        if (selected_icon) {
            selected_icon.css('background-color', '#f1f1f1');
            selected_icon.removeAttr('id');
            selected_icon = null;
        }
        $(this).attr('id', 'selected');
        $(this).children('.overlay').fadeIn('slow');

        selected_image = $(this).children('.overlay');
    });

    $('.bt-glyphicons-list').find('li').on('click',function(){
        if(selected_icon) {
            selected_icon.css('background-color', '#f1f1f1');
            selected_icon.removeAttr('id');
        }
        if(selected_image){
            selected_image.hide();
            selected_image.parent().removeAttr('id');
            selected_image = null;
        }
        $(this).attr('id','selected');
        $(this).css('background-color','#505050');
        selected_icon = $(this);
        //console.log(getIconName($(this).children('span').attr('class')));
    });
}



/*
var glyphicons = '';
$('.glyphicon-class').each(function(){

    glyphicons += (', "' + text + '"');
});
console.log(glyphicons);
*/

