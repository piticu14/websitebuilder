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

$(document).on('click', '#sendPasswordLink', function(e){
    e.preventDefault();
    $.nette.ajax({
        url: $('#frm-securityQuestionForm').data('url'),
    type: "POST",
        data: { user_question_answer: $('#questionAnswer').val(),
        emailAddress: $('#frm-securityQuestionForm').data('email'),
        user_security_question: $('#securityQuestion').val()},
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
    $('#frm-resetPasswordForm').bootstrapValidator({
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
                    notEmpty: {
                        message: 'Zopakujte nové heslo'
                    },
                    identical: {
                        field: 'password',
                        message: 'Hesla se neshodují'
                    }
                }
            }
        }
    })
$('#frm-addEmailForm').bootstrapValidator({
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
$('#frm-signinForm').bootstrapValidator({
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
        username: {
            validators: {
                notEmpty: {
                    message: 'Zadejte uživatelské jméno'
                },
                stringLength: {
                    min: 6,
                    max: 50,
                    message: 'Uživatelské jméno musi mít nejméně 6 znaků a nejvíce 50 znaků'
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

    }
})
function emailVerificationFormValidator() {
    $('#frm-emailVerificationForm').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        submitHandler: function (validator, form, submitButton) {
            $('#frm-emailVerificationForm').data('bootstrapValidator').resetForm();
            $.nette.ajax({
                url: form.data('url'),
                type: "POST",
                async: false,
                cache: false,
                data: {emailAddress: $(form).find('input[name="email"]').val()},
                success: function (payload) {
                    $('#collapse' + payload.boxId).addClass('in');
                },
                error: function (jqXHR, status, error) {
                    console.log(jqXHR);
                    console.log(status);
                    console.log(error);
                }
            });
            emailVerificationFormValidator();
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
}

function signupFormValidator() {
    $('#frm-signupForm').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'Zadejte uživatelské jméno'
                    },
                    stringLength: {
                        min: 6,
                        max: 50,
                        message: 'Uživatelské jméno musi mít nejméně 6 znaků a nejvíce 50 znaků'
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
                    notEmpty: {
                        message: 'Zopakujte nové heslo'
                    },
                    identical: {
                        field: 'password',
                        message: 'Hesla se neshodují'
                    }
                }
            },
            email: {
                validators: {
                    emailAddress: {
                        message: 'Emailová adresa není ve spravném tvaru'
                    },
                    notEmpty: {
                        message: 'Zadejte emailovou adresu'
                    }
                }
            },
            security_question_answer: {
                validators: {
                    notEmpty: {
                        message: 'Zadejte odpověď na bezpečnostní otázku'
                    }
                }
            }

        }
    })
}
$( document ).ready(function() {
    emailVerificationFormValidator();
    signupFormValidator();
});
$(document).on('submit', '#checkEmail', function(e) {
    emailVerificationFormValidator();
});

$(document).on('click', '#signup', function(e) {
    signupFormValidator();
});
