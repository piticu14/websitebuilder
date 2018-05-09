$(document).ready(function () {

    if ($('#collapse3').length) {
        $('#collapse3').addClass('in');
    } else {
        $('#collapse1').addClass('in');
    }
    if ($('#frm-emailVerificationForm').length) {
        emailVerificationFormValidator();
    }
    if ($('#frm-securityQuestionForm').length) {
        securityQuestionFormValidator();
    }
});

$(document).ajaxStop(function () {
    if ($('#frm-emailVerificationForm').length) {
        emailVerificationFormValidator();
    }
    if ($('#frm-securityQuestionForm').length) {
        securityQuestionFormValidator();
    }
});
$(document).on('click', '#addEmail', function (e) {
    e.preventDefault();
    $.nette.ajax({
        url: $(this).data('url'),
        type: "POST",
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
});

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
});

$('#frm-projectSettingsForm').bootstrapValidator({
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
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
});



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
                    user_question_answer: $('#frm-securityQuestionForm-security_question_answer').val(),
                    emailAddress: form.data('email'),
                    user_security_question: $('#frm-securityQuestionForm-security_question').val()
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

    //var projectLink = project.subdomain + '.QuickWeb.cz';
    var projectLink = '/websitebuilder/www/project/show/' + project.subdomain + '/' + project.public_url;
    $('#editProjectModal .modal-title').html(project.subdomain);
    $('#public-link').html(projectLink).attr('href', projectLink);

    if (parseInt(project.active)) {
        $('#active').html('Aktivní');
        $('#active').css('background','rgb(47,182,106)');
    } else {
        $('#active').html('Neaktivní');
        $('#active').css('background','#FF3232');
    }
    $('#settings').attr('href', '/websitebuilder/www/settings/default/' + project.subdomain);
    $('#edit').attr('href', '/websitebuilder/www/project/edit/' + project.subdomain + '/' + project.temp_url);
    $('#delete').attr('href', '/websitebuilder/www/project/delete/' + project.subdomain);


});

/*
 * -----Publish project------
 * Send the project data to PHP using Ajax
 */
/*ToDo: Change "menu" to  "nav" or "pages" */


$('#temp_save').off().on('click', function (e) {
    e.preventDefault();
    sendContent($(this));

});

$('#publish').off().on('click', function (e) {
    e.preventDefault();
    sendContent($(this));
});

function initPhotogallery(comp, imgs) {

}
