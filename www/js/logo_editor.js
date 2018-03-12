$('#logoModal').on('shown.bs.modal', function (e) {
    var logo_edit = $('#logo_edit_modal');
    var logo_container = $('#logo_container');
    $('#save_logo').on('click', function () {
        var title = $(logo_edit).find('input[name="title"]').val();
        var subtitle = $(logo_edit).find('input[name="subtitle"]').val();

        logo_container.find('#title').text(title);
        logo_container.find('#subtitle').text(subtitle);

        readURL($(logo_edit).find('input[name="image"]')[0]);

    });
});

$('#logoImageModal').on('shown.bs.modal', function () {

    $('#logo_form').hide().fadeIn('slow');
    $('#logo_form').hide();
    $('#logo_icons').on('click',function(){
        $('#logo_form').hide();
        $('.bt-glyphicons').hide().fadeIn('slow');
    });
    $('#logo_images').on('click',function(){
        $('.bt-glyphicons').hide()
        $('#logo_form').hide().fadeIn('slow');
    });

    var selected;
    $('.bt-glyphicons-list').find('li').on('click',function(e){
        if(selected) {
                selected.css('background-color', '#f1f1f1');
        }
        $(this).css('background-color','#505050');

        selected = $(this);
        console.log(getIconName($(this).children('span').attr('class')));
    });
});
function readURL(input) {
    console.log(input.value);
    var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }else{
        $('#img').attr('src', '/assets/no_preview.png');
    }
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

