$('#socialMediaModal').on('shown.bs.modal', function (e) {
    e.preventDefault();

    var modal_body = $('#social_media_body');
    var social_media_edit = $('#social_media_edit');

    $('#social_media_items').empty();
    $('#social_media_items').hide().fadeIn('slow');


    $('#qw_social_color').val($('#social_media').data('color'));
    $('#qw_social_color_hover').val($('#social_media').data('color_hover'));

    $('#social_media a').not('#social_media_icon').each(function (index) {

        var modal_link = $('<a></a>');
        modal_link.addClass('modal-social-media-links padding-left');
        modal_link.attr('href', $(this).attr('href'));
        modal_link.text($(this).data('media'));
        modal_link.data('id',index);
        modal_link.data('active', $(this).data('active'));
        var parent = $('<li></li>');
        var i = $('<i></i>');
        if($(this).has('i').length){
            i.addClass($(this).find('i').attr('class'));
        }else {
            i.addClass(this.className);
        }
        parent.append(i);
        parent.append(modal_link);
        $('#social_media_items').append(parent);

        social_media_init();

        modal_body.find('a.modal-social-media-links').each(function (index) {
            $(this).unbind().on('click', function (e) {
                e.preventDefault();

                social_temp_save();

                social_media_edit.find('#social_media_title').html('<p class="text-left" data-id="' + $(this).data('id') + '">' + $('#social_media a').eq(index).data('media') + '</p>');
                social_media_edit.find('input[name="href"]').val(remove_protocol_url($(this).attr('href')));
                if (!$(this).data('active')) {
                    $('#social_media_options').find('#active').bootstrapToggle('off');
                } else {
                    $('#social_media_options').find('#active').bootstrapToggle('on');
                }
                $('#social_media_edit').hide().fadeIn('slow');
            });
        })
    });
});

function remove_protocol_url(href) {
    var protocols_url = ['https://','http://'];
    protocols_url.forEach(function(url){
        if(href.indexOf(url) !=-1) {
            href = href.replace(url,'');
        }
    });

    return href;
}
$(document).on('click', '#save_social_media', function () {
    social_temp_save();
    var social_media = $('#social_media a').not('#social_media_icon');
    var modal_body = $('#social_media_body');
    var modal_social_media_links = modal_body.find('a.modal-social-media-links');

    var social_color = $('#qw_social_color').val();
    var social_color_hover = $('#qw_social_color_hover').val();

    $('#social_media').data('color',social_color);
    $('#social_media').data('color_hover',social_color_hover);

    social_media.each(function (index) {
        $(this).attr('href', 'https://' + $(modal_social_media_links[index]).attr('href'));
        $(this).data('active', $(modal_social_media_links[index]).data('active'));

        $(this).css('color',social_color);
        $(this).on('mouseover',(function(){
            $(this).css('color',social_color_hover);
        }));
        $(this).on('mouseout',(function(){
            $(this).css('color',social_color);
        }));
        if ($(modal_social_media_links[index]).data('active')) {
            $(this).parent().removeClass('hide')
        } else {
            $(this).parent().addClass('hide');
        }
    });
});
function social_media_init() {
    //var modal_body = $('#social_media_body');
    var modal_social_media_links = $('a.modal-social-media-links');
    var href = modal_social_media_links.attr('href');
    var active = modal_social_media_links.data('active');
    var social_media_edit = $('#social_media_edit');
    $('#social_media_title').html(
        '<p class="text-left" data-id="' + modal_social_media_links.first().data('id') + '">' + $('#social_media a').first().data('media') + '</p>'
    );
    //modal_body.find('.col-md-4').trigger('change');

    social_media_edit.find('input[name="href"]').val(remove_protocol_url(href));
    if (!active) {
        $('#social_media_options').find('#active').bootstrapToggle('off');
    } else {
        $('#social_media_options').find('#active').bootstrapToggle('on');
    }


    social_media_edit.hide().fadeIn('slow');


}
function social_temp_save() {
    var index = $('#social_media_title p').data('id');
    var social_media_edit = $('#social_media_edit');
    var href = remove_protocol_url(social_media_edit.find('input[name="href"]').val());

    console.log(href);

    var social_media_link = $("#social_media_body a.modal-social-media-links").eq(index);
    social_media_link.attr('href',href);
}

$('#socialMediaModal').on('hidden.bs.modal', function () {
    $('#social_media_items').hide();
    $('#social_media_edit').hide();
});

$('#social_media_options').find('#active').change(function() {

    var index = $('#social_media_title p').data('id');
    var link = $("#social_media_body a.modal-social-media-links").eq(index);
            if ($('#social_media_options').find('#active').prop('checked')) {
                $(link).data('active', 1);
            } else {
                $(link).data('active', 0);
            }
});

