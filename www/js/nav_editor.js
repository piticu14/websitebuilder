// Functions

/* Initialize modal box by adding nav items and show first item for editation */

function init() {
    var modal_body = $('.modal-body');
    var modal_menu_links = $('.modal-menu-links');
    var title = $(modal_menu_links).first().data('title');
    var keywords = $(modal_menu_links).first().data('keywords');
    var description = $(modal_menu_links).first().data('description');
    var active = $(modal_menu_links).first().data('active');
    var page_edit = $('#page_edit');
    modal_body.find('.col-md-4').trigger('change');

    $('#menuModalTitle').text('Editace stránek');
    $('.modal-body').find('ul').sortable();

    $('#nav_title').html(
        '<a data-id="' + $(modal_menu_links).first().data('id') + '" class="text-left" href="#"><span id="edit_menu_icon" class="glyphicon glyphicon-edit"></span>' + modal_body.find('a.modal-menu-links').first().text() +'</a>'
    );
    change_menuitem_text();

    $(page_edit).find('input[name="title"]').val(title);
    $(page_edit).find('input[name="keywords"]').val(keywords);
    $(page_edit).find('input[name="description"]').val(description);

    if(!active) {
        $('#active').bootstrapToggle('off');
    } else {
        $('#active').bootstrapToggle('on');
    }


    $(page_edit).hide().fadeIn('slow');
}

/* Temporary save by editing the modal-menu-links text and data-attributes (menu on the left side)*/

function temp_save(){
    var page_edit = $('#page_edit');
    var title = $(page_edit).find('input[name="title"]').val();
    var keywords = $(page_edit).find('input[name="keywords"]').val();
    var description = $(page_edit).find('input[name="description"]').val();
    var id = $('#nav_title').find('a').data("id");


    $(".modal-body a.modal-menu-links").each(function () {
        if ($(this).data('id') == id) {
            $(this).data('title',title);
            $(this).data('keywords',keywords);
            $(this).data('description',description);
        }
    });
}

/* Changes the h2 tag which contains menu item text with an input used for chaning the text.
   When blur, the input is changed back into h2 tag with the new text
   */
function change_menuitem_text() {
    var modal_body = $('.modal-body');
    var nav_title = modal_body.find('#nav_title a');
    $(nav_title).on('click', function (e) {
        e.preventDefault();
        nav_title.replaceWith($('<input id="menu_item_input" type="text" />'));

        var menu_item_input = $('#menu_item_input');

        if (menu_item_input.length) {
            menu_item_input.focus();
            menu_item_input.val(nav_title.text() );
            menu_item_input.on('keyup', function () {
                $(".modal-body a.modal-menu-links").each(function () {
                    if($(this).data('id') == nav_title.data('id')) {
                        $(this).text(menu_item_input.val());
                        if (menu_item_input.val()) {
                            menu_item_input.css('border', '2px solid black');
                        } else {
                            menu_item_input.css('border', '2px solid red');
                        }
                    }
                })
            });

            menu_item_input.blur(function (e) {
                if (!$(this).val()) {
                    e.preventDefault();
                } else {
                    $(this).replaceWith('<a data-id="' + nav_title.data('id') + '" class="text-left" href="#"><span id="edit_menu_icon" class="glyphicon glyphicon-edit"></span>' + $(this).val() + ' </a>');
                    modal_body.find('.col-md-4').trigger('change');

                }
            });

        }

    });
}

//Events

$('#menuModal').on('shown.bs.modal', function (e) {
    e.preventDefault();

    var modal_body = $('.modal-body');
    var page_edit = $('#page_edit');

    $('.modal-body ul').empty();
    $('.modal-body ul').hide().fadeIn('slow');

    $('#menu a').not(':last').each(function(){
         var modal_link = $('<a></a>');
        modal_link.addClass('modal-menu-links padding-left');
        modal_link.attr('href','#');
        modal_link.text($(this).text());
        modal_link.data('id',$(this).data('id'));
        modal_link.data('title',$(this).data('title'));
        modal_link.data('keywords',$(this).data('keywords'));
        modal_link.data('description',$(this).data('description'));
        modal_link.data('active',$(this).data('active'));
        var parent = $('<li></li>');
        var i = $('<i></i>');
        i.addClass('glyphicon glyphicon-file');
        parent.append(i);
        parent.append(modal_link);
        $('.modal-body ul').append(parent);

    });

    init();

    modal_body.find('a.modal-menu-links').each(function () {
        $(this).on('click', function (e) {
            e.preventDefault();
            temp_save();
            $('#nav_title').html(
                    '<a data-id="' + $(this).data('id') + '" class="text-left" href="#"><span id="edit_menu_icon" class="glyphicon glyphicon-edit"></span>' + $(this).text() + '</a>');

            $(page_edit).find('input[name="title"]').val($(this).data('title'));
            $(page_edit).find('input[name="keywords"]').val($(this).data('keywords'));
            $(page_edit).find('input[name="description"]').val($(this).data('description'));
            if(!$(this).data('active')) {
                $('#active').bootstrapToggle('off');
            }else {
                $('#active').bootstrapToggle('on');
            }
            $('#page_edit').hide().fadeIn('slow');
            change_menuitem_text();
        });
    });
});

/* Hide modal content when modal is closed */

$('#menuModal').on('hidden.bs.modal', function () {
    $('.modal-body ul').hide();
    $('#page_edit').hide();
});


/* If user press Save then change the Main Nav values */
$(document).on('click','#save_pages',function(){
    var menu = $('#menu a').not(':last');
    var modal_body = $('.modal-body');
    var modal_menu_links = $(modal_body).find('a.modal-menu-links');

    menu.each(function(index){
        //console.log($(modal_menu_links[index]).text());
        $(this).text($(modal_menu_links[index]).text());
        $(this).data('title',$(modal_menu_links[index]).data('title'));
        $(this).data('keywords',$(modal_menu_links[index]).data('keywords'));
        $(this).data('description',$(modal_menu_links[index]).data('description'));
        $(this).data('active',$(modal_menu_links[index]).data('active'));

        if($(modal_menu_links[index]).data('active')){
            $(this).parent().removeClass('hide')
        }else {
            $(this).parent().addClass('hide');
        }
    });
});

$('.modal-body').on('change', '.col-md-4', function () {
    change_menuitem_text();
});

/* Toggle Active button change data-active by Yes/No */

$('#active').change(function() {
    var id = $('#nav_title a').data('id');

    $(".modal-body a.modal-menu-links").each(function () {
        if($(this).data('id') == id) {
            if ($('#active').prop('checked')) {
                $(this).data('active', 1);
            } else {
                $(this).data('active', 0);
            }
        }
    });
});