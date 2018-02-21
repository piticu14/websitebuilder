$(document).on('click', '#signup', function(){

    var valid = true;
    $.nette.ajax({
        url: $('#frm-signupForm').data('url'),
        type: "POST",
        async: false,
        cache:false,
        data: { username: $("input[name=username]").val(),
        email: $("input[name=email]").val()},
    success: function (payload) {
        valid = payload.valid
    },
    error: function(jqXHR,status,error) {
        console.log(jqXHR);
        console.log(status);
        console.log(error);
    }
});
     if(valid) {
         $("#frm-signupForm").submit();
     }
});

$(document).on('click', '#addEmail', function(e){
    e.preventDefault();
    $.nette.ajax({
        url: $(this).data('url'),
        type: "POST",
        async: false,
        cache:false,
        data: { email: $(this).data('email')},
        success: function (payload) {
            $('#collapse' +  payload.boxId).addClass('in');
        },
        error: function(jqXHR,status,error) {
            console.log(jqXHR);
            console.log(status);
            console.log(error);
        }
    });
});

$(document).on('click', '#setPrimary', function(e){
    e.preventDefault();
    $.nette.ajax({
        url: $(this).data('url'),
        type: "POST",
        async: false,
        cache:false,
        data: { email: $(this).data('email')},
        success: function (payload) {
            $('#collapse' +  payload.boxId).addClass('in');
        },
        error: function(jqXHR,status,error) {
            console.log(jqXHR);
            console.log(status);
            console.log(error);
        }
    });
});

/*
jQuery(function() {
---------------------
    jQuery('#showall').click(function() {
        jQuery('.targetDiv').show();
    });
---------------------------------
    jQuery('.target-box').hide();
    jQuery('.show-box').click(function(e) {
        e.preventDefault();

        var flag = $(this).data('flag');
        $('#box' + $(this).data('target')).stop().slideToggle(500);

        $(this).data('flag', !flag);
    });
});
*/
$(document).ready(function() {
    $('#resetPasswordForm').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            old_password: {
                validators: {
                    stringLength: {
                        min: 6,
                        message: 'Helo musi mít alespoň 6 znaků'
                    },
                    notEmpty: {
                        message: 'Zadejte staré heslo'
                    }
                }
            },
            password: {
                validators: {
                    stringLength: {
                        min: 6,
                        message: 'Helo musi mít alespoň 6 znaků'
                    },
                    notEmpty: {
                        message: 'Zadejte nové heslo'
                    }
                }
            },
            password2: {
                validators: {
                    stringLength: {
                        min: 6,
                        message: 'Helo musi mít alespoň 6 znaků'
                    },
                    notEmpty: {
                        message: 'Zopakujte nové heslo'
                    }
                }
            }
        }
    })

    $('#addEmailForm').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    emailAddress: {
                        message: 'Emailová adresa není ve spravném tvaru'
                    },
                    notEmpty: {
                        message: 'Zadejte emailovou adresu'
                    }
                }
            }
        }
    })
});