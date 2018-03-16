$(document).ready(function(){
    if($('#logoImageModal').length){
        logoImageModal();
    }
});

$('#logoModal').on('shown.bs.modal', function (e) {
    e.preventDefault();
    var logo_edit = $('#logo_edit_modal');
    var logo_container = $('#logo_container');
    $('#save_logo').on('click', function () {
        var title = $(logo_edit).find('input[name="title"]').val();
        var subtitle = $(logo_edit).find('input[name="subtitle"]').val();

        logo_container.find('#title').text(title);
        logo_container.find('#subtitle').text(subtitle);


    });
});

function logoImageModal(){
    var selected_icon;
    var selected_image;

    $('#user_images').hide().fadeIn('slow');
    $('.bt-glyphicons').hide();
    $('#logo_icons').on('click',function(){
        $('#user_images').hide();
        $('.bt-glyphicons').hide().fadeIn('slow');
    });
    $('#logo_images').on('click',function(){
        $('.bt-glyphicons').hide();
        $('#user_images').hide().fadeIn('slow');
    });


    $('.bt-glyphicons-list').find('li').on('click',function(){
        if(selected_icon) {
            selected_icon.css('background-color', '#f1f1f1');
            selected_icon.removeAttr('id');
        }
        if(selected_image){
            selected_image.hide();
            selected_image = null;
        }
        $(this).attr('id','selected');
        $(this).css('background-color','#505050');
        selected_icon = $(this);
        //console.log(getIconName($(this).children('span').attr('class')));
    });

    $('.overlay').not('div#selected .overlay').hide();
    $('.image_box').on('click',function(){
        if(selected_image) {
            selected_image.hide();
        }
        if(selected_icon){
            selected_icon.css('background-color', '#f1f1f1');
            selected_icon = null;
        }
        $(this).attr('id','selected');
        $(this).children('.overlay').fadeIn('slow');

        selected_image = $(this).children('.overlay');

    })
}
$('#logoImageModal').on('shown.bs.modal', function () {
    logoImageModal();
});

$('#addImage').find('input[name="images"]').on('change',function(){
    files = $(this)[0].files;
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });

    // If you want to add an extra field for the FormData
    data.append("CustomField", "This is some extra data, testing");

    $.nette.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: $(this).data('url'),
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        /*
        // Can use it for progess bar
        xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.onprogress = function(e) {
                console.log(Math.floor(e.loaded / e.total *100) + '%');
            };
            return xhr;
        },
        */
        success: function (data) {
            $('.ajax_loader').hide();
        },

        error: function(jqXHR,status,error) {
            console.log(jqXHR);
            console.log(status);
            console.log(error);
        }
    });
});

$(document).ajaxStop(function(){
    if($('#logoImageModal').length){
        logoImageModal();
    }
});

function readURL(input) {
    var names = [];
    for (var i = 0; i < input.files.length; ++i) {
        console.log(input.files[i].name);

        //names.push(input.files[i].name);
    }
    $("input[name=file]").val(names);

}


function getIconName(iconClass) {
    return  iconClass.split(" ")[1].replace('glyphicon-','');
}

/*
var glyphicons = '';
$('.glyphicon-class').each(function(){

    glyphicons += (', "' + text + '"');
});
console.log(glyphicons);
*/

