
$('#logo_edit').on('click',function(){
    var logo_edit = $('#logo_edit_modal');
    var logo_container = $('#logo_container');

    var title_input = $(logo_edit).find('input[name="title"]');
    var subtitle_input = $(logo_edit).find('input[name="subtitle"]');

    title_input.val($('#title').text());
    subtitle_input.val($('#subtitle').text());

    $('#title').removeData('temp');
    $('#subtitle').removeData('temp');
});

$('#logoModal').on('shown.bs.modal', function (e) {
    e.preventDefault();

    var logo_edit = $('#logo_edit_modal');
    var logo_container = $('#logo_container');
    var selected_logo = $('#selected');


    var title_container = $('#title');
    var subtitle_container = $('#subtitle');
    console.log(subtitle_container);


    if(title_container.data('temp') && subtitle_container.data('temp')) {

        $(logo_edit).find('input[name="title"]').val(title_container.data('temp'));
        $(logo_edit).find('input[name="subtitle"]').val(subtitle_container.data('temp'));

    }



    $('#save_logo').on('click', function () {
        var title = $(logo_edit).find('input[name="title"]').val();
        var subtitle = $(logo_edit).find('input[name="subtitle"]').val();


        if(selected_logo.length){
            var logo_image_container =  $('#logo_image_container');
            logo_image_container.children().first().remove();
            if(selected_logo.is('div')) {
                var logo_image = $('<img>');
                logo_image.attr('width','100px');
                logo_image.attr('height','100px');
                logo_image.attr('alt','User Image');
                logo_image.attr('src',$(selected_logo).find('img').attr('src'));
                logo_image_container.prepend(logo_image);
                $('#menu').attr('style','padding-top:35px !important');
            } else {
                var logo_icon = $(selected_logo).children('span').clone();
                var icon = "<i class='" + logo_icon.attr('class') + "'></i>";
                logo_image_container.prepend(icon);
                $('#menu').attr('style','padding-top:0 !important');
            }
        }
        $('#title').text(title);
        $('#subtitle').text(subtitle);



        var text_color = logo_edit.find('.bfh-colorpicker').val();
        if(text_color) {
            $('#title').css('color',text_color);
            $('#subtitle').css('color',text_color);

        }

    });
});

$.extend({
    replaceTag: function (currentElem, newTagObj, keepProps) {
        var $currentElem = $(currentElem);
        var i, $newTag = $(newTagObj).clone();
        if (keepProps) {//{{{
            newTag = $newTag[0];
            newTag.className = currentElem.className;
            $.extend(newTag.classList, currentElem.classList);
            $.extend(newTag.attributes, currentElem.attributes);
        }//}}}
        $currentElem.wrapAll($newTag);
        $currentElem.contents().unwrap();
        // return node; (Error spotted by Frank van Luijn)
        return this; // Suggested by ColeLawrence
    }
});

$.fn.extend({
    replaceTag: function (newTagObj, keepProps) {
        // "return" suggested by ColeLawrence
        return this.each(function() {
            jQuery.replaceTag(this, newTagObj, keepProps);
        });
    }
});

$('#logo_container').mouseover(function(){
   $('#logo_edit').css("visibility","visible");
});
$('#logo_container').mouseout(function(){
    $('#logo_edit').css("visibility","hidden");
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

    var logo_edit = $('#logo_edit_modal');
    var logo_container = $('#logo_container');

    var title_input = $(logo_edit).find('input[name="title"]');
    var subtitle_input = $(logo_edit).find('input[name="subtitle"]');

    var title_container = $('#title');
    var subtitle_container = $('#subtitle');


    title_container.data('temp',title_input.val());
    subtitle_container.data('temp',subtitle_input.val());



    $('#user_images').hide().fadeIn('slow');
    $('.bt-glyphicons').hide();
    logoImageModal();
    $(this).find('input[name="images"]').off().on('change',function(){
        uploadImages($('#logoImageModal').find('#upload_bar'), $(this),'images');
    });



});


function uploadImages(uploadbar,input,type){

    uploadbar.show();
    var files = input[0].files

    if(files.length){
        var data = new FormData();
        $.each(files, function(key, value)
        {
            data.append(key, value);
        });
        data.append('type',type);
        // If you want to add an extra field for the FormData
        //data.append("CustomField", "This is some extra data, testing");

        $.nette.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: input.data('url'),
            data: data,
            processData: false,
            contentType: false,
            cache: false,

            // Can use it for progess bar
            xhr: function() {
                var xhr = $.ajaxSettings.xhr();
                xhr.upload.onprogress = function(e) {
                    var percent = (Math.floor(e.loaded / e.total *100) + '%');
                    uploadbar.find('div.progress-bar').css('width',percent).text(percent);
                };
                return xhr;
            },

            success: function (data) {
                uploadbar.find('div.progress-bar').css('width',0).text("");
                uploadbar.hide();
            },

            error: function(jqXHR,status,error) {
                console.log(jqXHR);
                console.log(status);
                console.log(error);
            }
        });
    }
}

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

