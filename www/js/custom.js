$(document).ready(function () {

    if ($('#collapse3').length) {
        $('#collapse3').addClass('in');
    } else {
        $('#collapse1').addClass('in');
    }
    if ($('#frm-emailVerificationForm').length) {
        console.log('call email validator from docReady');
        emailVerificationFormValidator();
    }
    if ($('#frm-securityQuestionForm').length) {
        console.log('call sec validator from docReady');
        securityQuestionFormValidator();
    }
});

$(document).ajaxStop(function () {
    if ($('#frm-emailVerificationForm').length) {
        console.log('call email validator from ajaxStop');
        emailVerificationFormValidator();
    }
    if ($('#frm-securityQuestionForm').length) {
        console.log('call sec validator from ajaxStop');
        securityQuestionFormValidator();
    }
});
$(document).on('click', '#addEmail', function (e) {
    e.preventDefault();
    $.nette.ajax({
        url: $(this).data('url'),
        type: "POST",
        async: false,
        cache: false,
        data: {email: $(this).data('email')},
        success: function (payload) {
            $('#collapse' + payload.boxId).addClass('in');
        },
        error: function (jqXHR, status, error) {
            console.log(jqXHR);
            console.log(status);
            console.log(error);
        }
    });
});

$(document).on('click', '#setPrimary', function (e) {
    e.preventDefault();
    $.nette.ajax({
        url: $(this).data('url'),
        type: "POST",
        async: false,
        cache: false,
        data: {email: $(this).data('email')},
        success: function (payload) {
            $('#collapse' + payload.boxId).addClass('in');
        },
        error: function (jqXHR, status, error) {
            console.log(jqXHR);
            console.log(status);
            console.log(error);
        }
    });
});

/*

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
 */

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
                    message: 'Heslo musi mít alespoň 6 znaků'
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
                    message: 'Heslo musi mít alespoň 6 znaků'
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
                    message: 'Uživatelské jméno nesmi být kratší 6 znaků a delší 50 znaků'
                }
            }
        },
        password: {
            validators: {
                stringLength: {
                    min: 6,
                    message: 'Heslo musi mít alespoň 6 znaků'
                },
                notEmpty: {
                    message: 'Zadejte nové heslo'
                }
            }
        },

    }
})

$('#frm-siteForm').bootstrapValidator({
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
        title: {
            validators: {
                notEmpty: {
                    message: 'Zadejte název webu'
                },
                stringLength: {
                    max: 45,
                    message: 'Název webu může nejvíce 45 znaků'
                }
            }
        },
        subtitle: {
            validators: {
                stringLength: {
                    max: 45,
                    message: 'Podnázev webu může mít nejvíce 45 znaků'
                }
            }
        },
        subdomain: {
            validators: {
                regexp: {
                    regexp: /^[\W = [A-Za-z0-9]+$/,
                    message: 'Subdoména může obsahovat pouze čislice a písmenka'
                },
                notEmpty: {
                    message: 'Zadejte subdoménu'
                },
                stringLength: {
                    max: 20,
                    message: 'Subdoména může mít nejvíce 20 znaků'
                }
            }
        }

    }
})


function securityQuestionFormValidator() {
    $('#frm-securityQuestionForm').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        submitHandler: function (validator, form, submitButton) {
            $('#frm-securityQuestionForm').data('bootstrapValidator').resetForm();
            $.nette.ajax({
                url: form.data('url'),
                type: "POST",
                data: {
                    user_question_answer: $('#questionAnswer').val(),
                    emailAddress: form.data('email'),
                    user_security_question: $('#securityQuestion').val()
                },
                error: function (jqXHR, status, error) {
                    console.log(jqXHR);
                    console.log(status);
                    console.log(error);
                }
            });
        },
        fields: {
            security_question_answer: {
                validators: {
                    notEmpty: {
                        message: 'Zadejte kontrolní odpověď'
                    },
                    stringLength: {
                        max: 100,
                        message: 'Kontrolní odpověď nesmi být kratší 6 znaků a delší 100 znaků'
                    }
                }
            }
        }
    })
}


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
            securityQuestionFormValidator();

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
                    message: 'Uživatelské jméno nesmí být kratší 6 znaků a delší 50 znaků'
                }
            }
        },
        password: {
            validators: {
                stringLength: {
                    min: 6,
                    message: 'Heslo musi mít alespoň 6 znaků'
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
$(document).on('click', '.add-project-form', function () {
    $('#addProjectModal input[id="template_id"]').val($(this).attr('id'))
});
$(document).on('click', '.showProject', function () {
    var project = $(this).data('project');
    console.log(project);
    //var projectLink = project.subdomain + '.fastweb.cz';
    var projectLink = '/websitebuilder/www/project/show/' + project.id + '/' + project.page_publish_id;
    $('#editProjectModal .modal-title').html(project.title);
    $('#project-subdomain').html(projectLink).attr('href', projectLink);

    if (parseInt(project.active)) {
        $('#active').html('Stav: Aktivní')
    } else {
        $('#active').html('Stav: Neaktivní')
    }
    $('#settings').attr('href', '/websitebuilder/www/settings/default/' + project.id);
    $('#edit').attr('href', '/websitebuilder/www/project/edit/' + project.id + '/' + project.page_temp_id);
    $('#delete').attr('href', '/websitebuilder/www/project/delete/' + project.id);


});

/*
 * -----Publish project------
 * Send the project data to PHP using Ajax
 */
/*ToDo: Change "menu" to  "nav" or "pages" */


$(document).on('click', '#temp_save', function (e) {
    e.preventDefault();

    sendContent($(this));
});

$(document).on('click', '#publish', function (e) {
    e.preventDefault();
    sendContent($('#temp_save'));
    window.location.href = $(this).attr('href');
});

function initPhotogallery(comp, imgs) {

}
