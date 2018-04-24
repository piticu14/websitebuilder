


$('#headerModal').on('shown.bs.modal', function (e) {
    e.preventDefault();

    var logo_container = $('#logo_container');
    var selected_logo = $('#logoImageModal').find('#selected');
    var selected_background = $('#headerBackgroundModal').find('#selected');




    $('#save_logo').off().on('click', function (e) {




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


        if(selected_background.length){
            $('#header-wrapper').css('background','url("' + $(selected_background).find('img').attr('src') +'")no-repeat center center fixed');
            $('#header-wrapper').css('background-size','cover');
        }else if($('#headerBackgroundModal').find('#selected_color').val() !== '') {
            var background_color = $('#headerBackgroundModal').find('#selected_color').val();
            console.log(background_color);
            $('#header-wrapper').css('background','');
            $('#header-wrapper').css('background-color',background_color);
        }

    });
});

// Do I use this?.....
/*
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
*/

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

    $('#user_images').hide().fadeIn('slow');
    $('.bt-glyphicons').hide();
    logoImageModal();
    $(this).find('input[name="images"]').off().on('change',function(){
        uploadImages($('#logoImageModal').find('#upload_bar'), $(this),'images');
    });



});

function headerBackgroundModal(){
    $(document).on('click','#header_background_color',function(e){
        e.preventDefault();
        $('#background_images').hide();
        $('#background_color').fadeIn('slow');
    });
    $(document).on('click','#header_background_images',function(e){
        e.preventDefault();

        $('#background_color').hide();
        $('#background_images').fadeIn('slow');
    });

    headerBackgroundSelection();

}
$('#headerBackgroundModal').on('shown.bs.modal', function (e) {
    //console.log(e.relatedTarget.id);



    $('#background_images').hide().fadeIn('slow');
    $('#background_color').hide();
    headerBackgroundModal();
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
    if($('#headerBackgroundModal').length){
        headerBackgroundSelection();
    }
});

function logoSelection() {

    var selected_image;
    var selected_icon;
    var logo_image_modal = $('#logoImageModal');
    logo_image_modal.find('.overlay').not('#selected .overlay').hide();
    logo_image_modal.find('.image_box').on('click',function() {
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

    logo_image_modal.find('.bt-glyphicons-list').find('li').on('click',function(){
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

function headerBackgroundSelection() {

    var selected_image;
    var header_background_modal = $('#headerBackgroundModal');
    header_background_modal.find('.overlay').not('#selected .overlay').hide();
    header_background_modal.find('.image_box').on('click',function() {
        if (selected_image) {
            selected_image.parent().removeAttr('id');
            selected_image.hide();

        }

        $(this).attr('id', 'selected');
        $(this).children('.overlay').fadeIn('slow');
        $('#selected_color').val("");

        selected_image = $(this).children('.overlay');
    });


    $('#selected_color').on('change.bfhcolorpicker',function(){
        if(selected_image){
            selected_image.hide();
            selected_image.parent().removeAttr('id');
            selected_image = null;
        }
    })

}
