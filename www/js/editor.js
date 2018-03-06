$('.modal-body').on('change', '.col-md-4', function () {
    change_menuitem_text();
});

$('#menuModal').on('hide.bs.modal', function () {
    $('#page_edit').hide();
});
$('#menuModal').on('shown.bs.modal', function (e) {
    e.preventDefault();

    init();
    var modal_body = $('.modal-body');
    modal_body.find('a.modal-menu-links').each(function (index) {
        $(this).on('click', function (e) {
            e.preventDefault();
            temp_save();
            $('#page_text').html(
                    '<a data-id="' + index + '" class="text-left" href="#"><span id="edit_menu_icon" class="glyphicon glyphicon-edit"></span>' + $(this).text() + ' </a>');

            $('#page_edit').hide().fadeIn('slow');
            change_menuitem_text();
        });
    });


});

function init() {
    var modal_body = $('.modal-body');
    modal_body.find('.col-md-4').trigger('change');

    $('#menuModalTitle').text('Editace stránek');
    modal_body.find('ul').empty();


    $('#menu a:not(:last)').each(function (index) {
        $('.modal-body').find('ul').append('<li><i class="glyphicon glyphicon-file"></i><a data-id="' + index + '" class="modal-menu-links padding-left" href="#">' + $(this).text() + '</a></li>').sortable();
    })

    $('#page_text').html(
        '<a data-id="0" class="text-left" href="#"><span id="edit_menu_icon" class="glyphicon glyphicon-edit"></span>' + modal_body.find('a.modal-menu-links').first().text() +'</a>'
    );
    $('#page_edit').hide().fadeIn('slow');
}

function temp_save(){
    var title = $('[name="title"]').val();
    var keywords = $('[name="keywords"]').val();
    var description = $('[name="description"]').val();
    var id = $('#page_text a').data("id");

    console.log(title);
    console.log(keywords);
    console.log(description);
    console.log(id);

    var page = $('a.modal-menu-links[data-id="' + id + '"]');
    page.data('id',3)
    page.data('title',title);
    page.data('keywords',keywords);
    page.data('description',description);
    console.log(page.data('id'));


}


function change_menuitem_text() {
    var modal_body = $('.modal-body');
    var page_text = modal_body.find('#page_text a');
    $(page_text).on('click', function (e) {
        e.preventDefault();
        page_text.replaceWith($('<input id="menu_item_input" type="text" value="' + page_text.text() + '"/>'));

        var menu_item_input = $('#menu_item_input');

        if (menu_item_input.length) {
            menu_item_input.focus();
            menu_item_input.on('input', function (e) {
                $("[data-id='" + page_text.data('id') + "']").each(function () {
                    $(this).text(menu_item_input.val());
                    if (menu_item_input.val()) {
                        menu_item_input.css('border', '2px solid black');
                    } else {
                        menu_item_input.css('border', '2px solid red');
                    }
                })
            });

            menu_item_input.blur(function (e) {
                if (!$(this).val()) {
                    e.preventDefault();
                } else {
                    $(this).replaceWith('<a data-id="' + page_text.data('id') + '" class="text-left" href="#"><span id="edit_menu_icon" class="glyphicon glyphicon-edit"></span>' + $(this).val() + ' </a>');
                    modal_body.find('.col-md-4').trigger('change');

                }
            });

        }

    });
}


// Button toggle
    $('.btn-toggle').click(function() {
        $(this).find('.btn').toggleClass('active');

        if ($(this).find('.btn-primary').length>0) {
            $(this).find('.btn').toggleClass('btn-primary');
        }
        if ($(this).find('.btn-danger').length>0) {
            $(this).find('.btn').toggleClass('btn-danger');
        }
        if ($(this).find('.btn-success').length>0) {
            $(this).find('.btn').toggleClass('btn-success');
        }
        if ($(this).find('.btn-info').length>0) {
            $(this).find('.btn').toggleClass('btn-info');
            var radioValue = $("input[name='options']:checked").val();

            console.log("You selected - " + radioValue);
        }

        $(this).find('.btn').toggleClass('btn-default');

    });

    /*
    $('form').submit(function(){
        var radioValue = $("input[name='options']:checked").val();
        if(radioValue){
            alert("You selected - " + radioValue);
        };
        return false;
    });
*/


/*
$(document).on('click','#save_pages',function(e){
    $.nette.ajax({
        url: $(this).data('url'),
        type: "POST",
        data: {project_pages: project_pages()},
        success: function(payload) {
            alert('success');
        },
        error: function(jqXHR,status,error) {
            console.log(jqXHR);
            console.log(status);
            console.log(error);
        }
    })
});

function project_pages(){
    $name =
}
*/