/*-------------------------------------START OF FUNCTIONS------------------------------------------------- */

/* Initialize modal box by adding nav items and show first item for editation */

function init() {

    var modal_body = $('#nav_body');
    var modal_menu_links = $('.modal-menu-links');
    var title = $(modal_menu_links).first().data('title');
    var keywords = $(modal_menu_links).first().data('keywords');
    var description = $(modal_menu_links).first().data('description');
    var page_url = $(modal_menu_links).first().data('page_url');
    var active = $(modal_menu_links).first().data('active');
    var page_edit = $('#page_edit');


    modal_body.find('.col-md-4').trigger('change');

    //$('#menuModalTitle').text('Editace stránek');
    $('#nav_body').find('ul').sortable({ items: "> li:not(:first,:last)" });

    $('#nav_title').html(
        '<a data-id="' + $(modal_menu_links).first().data('id') + '" class="text-left" href="#"><span id="edit_menu_icon" class="glyphicon glyphicon-edit"></span>' + modal_body.find('a.modal-menu-links').first().text() +'</a>'
    );
    change_menuitem_text();

    $(page_edit).find('input[name="title"]').val(title);
    $(page_edit).find('input[name="keywords"]').val(keywords);
    $(page_edit).find('input[name="description"]').val(description);
    $(page_edit).find('input[name="page_url"]').val(page_url);

    if(!active) {
        $('#active').bootstrapToggle('off');
    } else {
        $('#active').bootstrapToggle('on');
    }


    $(page_edit).hide().fadeIn('slow');
}

/* Temporary save by editing the modal-menu-links text and data-attributes (menu on the left side)*/

function nav_temp_save(){
    var page_edit = $('#page_edit');
    var title = $(page_edit).find('input[name="title"]').val();
    var keywords = $(page_edit).find('input[name="keywords"]').val();
    var description = $(page_edit).find('input[name="description"]').val();
    var page_url = $(page_edit).find('input[name="page_url"]').val();
    var id = $('#nav_title').find('a').data("id");


    $("#nav_body a.modal-menu-links").each(function () {
        if ($(this).data('id') == id) {
            $(this).data('title',title);
            $(this).data('keywords',keywords);
            $(this).data('description',description);
            $(this).data('page_url',page_url);
        }
    });
}

/* Changes the h2 tag which contains menu item text with an input used for chaning the text.
   When blur, the input is changed back into h2 tag with the new text
   */
function change_menuitem_text() {
    var modal_body = $('#nav_body');
    var nav_title = modal_body.find('#nav_title a');
    $(nav_title).on('click', function (e) {
        e.preventDefault();
        nav_title.replaceWith($('<input id="menu_item_input" type="text" />'));

        var menu_item_input = $('#menu_item_input');

        if (menu_item_input.length) {
            menu_item_input.focus();
            menu_item_input.val(nav_title.text() );
            menu_item_input.on('keyup', function () {
                $("#nav_body a.modal-menu-links").each(function () {
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

function hideDropDownMenu() {
    var hide = true;
    $('#page_drop_down li').each(function() {
        if(!$(this).hasClass('hide')){
            hide  = false;
            return;
        }
    });

    if(hide){
        $('#page_drop_down_container').addClass('hide');
    } else {
        $('#page_drop_down_container').removeClass('hide');
    }
}

$('#nav_body').on('change', '.col-md-4', function () {
    change_menuitem_text();
});

function savePage() {
    var pageForm = $('#pageForm');
    var modal_link = $('<a></a>');
    modal_link.attr('href','#');
    modal_link.text(pageForm.find('input[name="name"]').val());
    modal_link.data('id',guid());
    modal_link.data('title',pageForm.find('input[name="title"]').val());
    modal_link.data('keywords',pageForm.find('input[name="keywords"]').val());
    modal_link.data('description', pageForm.find('input[name="description"]').val());
    modal_link.data('page_url', pageForm.find('input[name="page_url"]').val());
    modal_link.data('active',1);
    var parent = $('<li></li>');
    parent.append(modal_link);
    if($('#menu a').not(':last,#dropdownMenu').length < 4){
        parent.addClass('hide');
        parent.insertBefore('#page_drop_down_container')
    }else {
        $('#page_drop_down').append(parent);
    }

}

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}

function getHeaderData()
{
    var $logo_container = $('#logo_container');
    var header = {};
    header.title = $('#title').html();
    header.subtitle = $('#subtitle').html();


    var image = {};
    if($logo_container.find('img').length){
        image.type = 'img'
        image.src = $('#logo_image_container img').attr('src');
    }else {
        image.type = 'i';
        image.src = getIconName($logo_container.find('i').attr('class'))
    }

    header.logo = image;
    header.background = getHeaderBackgroundData();

    return header;
}

function getHeaderBackgroundData()
{
    var $header = $('#header-wrapper');

    if (typeof $header.attr('style') !== typeof undefined && $header.attr('style') !== false) {
        return $header.attr('style');
    }else{
        return 'default';
    }


}



function getIconName(iconClass) {
    return  iconClass.split(" ")[1].replace('glyphicon-','');
}

function getNavData() {
    var menu = $('#menu a').not(':last,#dropdownMenu');
    var pages = [];

    menu.each(function(){
        var menu_item = {};
        menu_item.text = $(this).text();
        menu_item.id = $(this).data('id');
        menu_item.page_id = $(this).data('page_id');
        menu_item.title = $(this).data('title');
        menu_item.keywords = $(this).data('keywords');
        menu_item.description = $(this).data('description');
        menu_item.url = $(this).data('page_url');
        menu_item.active = $(this).data('active');
        pages.push(menu_item);
    });

    return pages;
}

function getSocialMedia() {
    var social_media = $('#social_media a').not('#social_media_icon');
    var items = [];
    social_media.each(function(){
        var item = {};
        item.media = $(this).data('media');
        item.active = $(this).data('active');
        item.href = $(this).attr('href');
        items.push(item);
    });

    return items;
}

function getBodyContent() {
    var items = [];
    var body_content =  $('#body').keditor('getContent');

    //console.log($('#body section').attr('data-id'));
    $(body_content + '#page_item').each(function(index){

        var photogallery_ids = [];
        var item = {};
        if($(this).data('id')){
            item.id = $(this).data('id');
        }
        var photoGalleryItem = $(this).find('.photogallery-container');

        if(photoGalleryItem.length) {
            photoGalleryItem.each(function(){
                photogallery_ids.push($(this).attr('data-id'));
            });
        }

        $(this).removeAttr('data-id');
        item.photogallery_ids = photogallery_ids;
        item.content = $(this).prop('outerHTML');

        items.push(item);
    });

    //console.log(items);
    return items;
}

//  If user goes to another page save data to temporary db table

function sendContent($link) {

    var nav = getNavData();
    var header = getHeaderData();
    var body = getBodyContent();
    var footer = {};

    footer.content = $('#footer_content').html();
    footer.social_media = getSocialMedia();
    $.nette.ajax({
        url: $('#temp_save').attr('href') + '?do=saveTemporary',
        type: "POST",
        data: { nav: JSON.stringify(nav),
            header: JSON.stringify(header),
            body: JSON.stringify(body),
            footer: JSON.stringify(footer)},
        error: function(jqXHR,status,error) {
            console.log(jqXHR);
            console.log(status);
            console.log(error);
        },
        success: function(payload) {
            window.location.href = $link.attr('href');
        }
    });
}

$('#menu a').not(':last,#dropdownMenu').each(function(){
    $(this).off().on('click',function(e) {
        sendContent($(this));
    });
});
/*-------------------------------------END OF FUNCTIONS------------------------------------------------- */

/*-------------------------------------START OF EVENTS------------------------------------------------- */

$('#menuModal').off('shown.bs.modal').on('shown.bs.modal', function (e) {
    e.preventDefault();

    var modal_body = $('#nav_body');
    var page_edit = $('#page_edit');

    $('#page_items').empty();
    $('#page_items').hide().fadeIn('slow');

    $('#menu a').not(':last,#dropdownMenu').each(function(index){

         var modal_link = $('<a></a>');
        modal_link.addClass('modal-menu-links padding-left');
        modal_link.attr('href','#');
        modal_link.text($(this).text());
        modal_link.data('id',$(this).data('id'));
        modal_link.data('title',$(this).data('title'));
        modal_link.data('keywords',$(this).data('keywords'));
        modal_link.data('description',$(this).data('description'));
        modal_link.data('page_url',$(this).data('page_url'));
        modal_link.data('active',$(this).data('active'));
        var parent = $('<li></li>');
        var i = $('<i></i>');
        i.addClass('glyphicon glyphicon-file');
        parent.append(i);
        parent.append(modal_link);
        if(index != 0){
            parent.append('<a class="deletePage" href="javascript:void(0)" data-id="'+ $(this).data('page_id') + '" ><i class="glyphicon glyphicon-trash"></i></a>');
        }
        $('#page_items').append(parent);

    });
    $('#page_items').append('<li><a data-dismiss="modal" data-toggle="modal" data-target="#newPageModal" class="btn btn-primary"  href="javascript:void(0)" >Přidat stránku</a></li>');
    init();

    modal_body.find('a.modal-menu-links').each(function () {
        $(this).on('click', function (e) {
            e.preventDefault();
            nav_temp_save();
            $('#nav_title').html(
                    '<a data-id="' + $(this).data('id') + '" class="text-left" href="#"><span id="edit_menu_icon" class="glyphicon glyphicon-edit"></span>' + $(this).text() + '</a>');

            $(page_edit).find('input[name="title"]').val($(this).data('title'));
            $(page_edit).find('input[name="keywords"]').val($(this).data('keywords'));
            $(page_edit).find('input[name="description"]').val($(this).data('description'));
            $(page_edit).find('input[name="page_url"]').val($(this).data('page_url'));
            if(!$(this).data('active')) {
                $('#active').bootstrapToggle('off');
            }else {
                $('#active').bootstrapToggle('on');
            }
            $('#page_edit').hide().fadeIn('slow');
            change_menuitem_text();
        });
    });

    $('.deletePage').each(function(){
        $(this).off().on('click',function(){

            var button = $(this);

            $.nette.ajax({
                url: $('#page_items').data('url'),
                type: "POST",
                data: { pid: $(this).data('id')},
                error: function(jqXHR,status,error) {
                    console.log(jqXHR);
                    console.log(status);
                    console.log(error);
                },
                success: function(payload) {
                    button.parent().remove();
                    $('#menu a[data-page_id = ' + button.data('id') +']').parent().remove();
                }
            });

        });

    });
});
/* Hide modal content when modal is closed */

$('#menuModal').on('hidden.bs.modal', function () {
    $('#page_items').hide();
    $('#page_edit').hide();
});


/* If user press Save then change the Main Nav values */
$(document).on('click','#save_pages',function(){

    var temp_href = $('#temp_save').attr('href');
    var publish_href = $('#publish').attr('href');
    var current_page_url = temp_href.substring(temp_href.lastIndexOf("/") + 1);
    nav_temp_save();
    var menu = $('#menu a').not(':last,#dropdownMenu');
    var modal_body = $('#nav_body');
    var modal_menu_links = $(modal_body).find('a.modal-menu-links');
    menu.each(function(index){
        var valid_page_url = $(modal_menu_links[index]).data('page_url').toLowerCase().replace(/ /g,'-');
        var temp_url = temp_href.slice(0, temp_href.lastIndexOf('/'));
        var publish_url = publish_href.slice(0, publish_href.lastIndexOf('/'));
        if($(this).data('page_url') === current_page_url){
            $('#temp_save').attr('href',temp_url + '/' + valid_page_url);
            $('#publish').attr('href',publish_url + '/' + valid_page_url);

        }
        //console.log($(modal_menu_links[index]).text());
        $(this).text($(modal_menu_links[index]).text());
        $(this).data('title',$(modal_menu_links[index]).data('title'));
        $(this).data('page_url',valid_page_url);
        $(this).data('keywords',$(modal_menu_links[index]).data('keywords'));
        $(this).data('description',$(modal_menu_links[index]).data('description'));
        $(this).data('active',$(modal_menu_links[index]).data('active'));
        $(this).attr('href',url + '/' + valid_page_url);

        if($(modal_menu_links[index]).data('active')){
            $(this).parent().removeClass('hide')
        }else {
            $(this).parent().addClass('hide');
        }
    });

    hideDropDownMenu();
});



/* Toggle Active button change data-active by Yes/No */

$('#active').change(function() {
    var id = $('#nav_title a').data('id');

    $("#nav_body a.modal-menu-links").each(function () {
        if($(this).data('id') == id) {
            if ($('#active').prop('checked')) {
                $(this).data('active', 1);
            } else {
                $(this).data('active', 0);
            }
        }
    });
});

$('#newPageModal').on('shown.bs.modal', function (e) {
    $('#savePage').unbind().on('click',function() {
        savePage();
    });
});

$("#newPageModal").on("hidden.bs.modal", function(){
    var pageForm = $('#pageForm');
    pageForm.find('input[name="name"]').val('');
    pageForm.find('input[name="title"]').val('');
    pageForm.find('input[name="keywords"]').val('');
    pageForm.find('input[name="page_url"]').val('');
    pageForm.find('input[name="description"]').val('');
});

$('#cover').hide();
/*-------------------------------------END OF EVENTS------------------------------------------------- */
$('#cover').bind('ajaxStart', function(){
    $(this).show();
}).bind('ajaxStop', function(){
    $(this).hide();
});


if($('#page_drop_down').children().length) {
    console.log('Yes');
    $('#page_drop_down_container').removeClass('hide');
}