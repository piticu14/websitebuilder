$(document).ready(function () {

    $(".submenu > a").click(function (e) {
        e.preventDefault();
        var $li = $(this).parent("li");
        var $ul = $(this).next("ul");

        if ($li.hasClass("open")) {
            $ul.slideUp(350);
            $li.removeClass("open");
        } else {
            $(".nav > li > ul").slideUp(350);
            $(".nav > li").removeClass("open");
            $ul.slideDown(350);
            $li.addClass("open");
        }
    });
    //Popover - Select template
    $('[data-toggle="popover"]').popover({trigger: "manual", html: true, animation: false, container: 'body'})
        .on("mouseenter", function () {
            var _this = this;
            $(this).popover("show");
            $(".popover").on("mouseleave", function () {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 500);
    });

    //RadioButton - Disable send button until select something
    $("input:radio").change(function () {
        $("#send").prop("disabled", false);
    });

    //If exists Select Template Form block everything except the form
    /*
    if (document.getElementById('frm-templateForm')) {
        $("a").not(".dropdown a").removeAttr("href").css("cursor", "not-allowed");
        $('.btn-primary').not("#send").prop('disabled', true);
        $('li').removeClass("submenu");
    }
    */

    // Sortable List - used to sort Lists
    $('#sortable').sortable({
        items: 'li',
        cursor: 'pointer',
        opacity: 0.6
    });

    $("#sortable").disableSelection();

    //display and edit class - used to edit menu items
    $(".display").click(function () {
        $(this).hide().siblings(".edit").show().val($(this).text()).focus();
    });

    $(".edit").focusout(function () {
        $(this).hide().siblings(".display").show().text($(this).val());
    });

});
