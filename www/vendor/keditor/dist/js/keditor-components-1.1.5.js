/**!
 * KEditor - Kademi content editor
 * @copyright: Kademi (http://kademi.co)
 * @author: Kademi (http://kademi.co)
 * @version: 1.1.5
 * @dependencies: $, $.fn.draggable, $.fn.droppable, $.fn.sortable, Bootstrap (optional), FontAwesome (optional)
 */
(function ($) {
    var KEditor = $.keditor;
    var flog = KEditor.log;

    KEditor.components['audio'] = {
        getContent: function (component, keditor) {
            flog('getContent "audio" component, component');

            var componentContent = component.children('.keditor-component-content');
            var audio = componentContent.find('audio');
            audio.unwrap();

            return componentContent.html();
        },

        settingEnabled: true,

        settingTitle: 'Nastavení zvuku',

        initSettingForm: function (form, keditor) {
            flog('init "audio" settings', form);

            form.append(
                '<div id="upload_bar" class="progress">' +
                '<div class="progress-bar" role="progressbar" style="width: 0;" aria-valuenow="0"' +
                'aria-valuemin="0" aria-valuemax="100"></div>' +
                '</div>'+
                '<form class="form-horizontal">' +
                '<div class="form-group">' +
                '<label for="audioFileInput" class="col-sm-12">Zvukový soubor</label>' +
                '<div class="col-sm-12">' +
                '<div class="audio-toolbar">' +
                '<a href="#" class="btn-audioFileInput btn btn-sm btn-primary"><i class="fa fa-upload"></i></a>' +
                '<input id="audioFileInput" type="file" accept="audio/*" style="display: none">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="audio-autoplay" class="col-sm-12">Automatické přehrávání</label>' +
                '<div class="col-sm-12">' +
                '<input type="checkbox" id="audio-autoplay" />' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="audio-showcontrols" class="col-sm-12">Zobrazit ovládací prvky</label>' +
                '<div class="col-sm-12">' +
                '<input type="checkbox" id="audio-showcontrols" checked />' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="audio-width" class="col-sm-12">Šířka (%)</label>' +
                '<div class="col-sm-12">' +
                '<input type="number" id="audio-width" min="20" max="100" class="form-control" value="100" />' +
                '</div>' +
                '</div>' +
                '</form>'
            );
        },

        showSettingForm: function (form, component, keditor) {
            flog('showSettingForm "audio" component', form, component);

            var options = keditor.options;

            var audio = component.find('audio');
            var fileInput = form.find('#audioFileInput');
            var btnAudioFileInput = form.find('.btn-audioFileInput');
            var uploadbar = form.find('#upload_bar');
            btnAudioFileInput.off('click').on('click', function (e) {
                e.preventDefault();

                fileInput.trigger('click');
            });
            fileInput.off('change').on('change', function () {
                uploadbar.show();
                var file = this.files[0];
                if (/audio/.test(file.type)) {
                    var data = new FormData();
                    data.append('audio', file);
                    data.append('type', 'audio');
                    $.nette.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: $('#addImage').find('input[name="images"]').data('url'),
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        xhr: function() {
                            var xhr = $.ajaxSettings.xhr();
                            xhr.upload.onprogress = function(e) {
                                var percent = (Math.floor(e.loaded / e.total *100) + '%');
                                uploadbar.find('div.progress-bar').css('width',percent).text(percent);
                            };
                            return xhr;
                        },
                        success: function (payload) {


                            audio.attr('src', payload.src);

                            audio.on('load', (function () {
                                keditor.showSettingPanel(component, options);
                            }));
                            uploadbar.find('div.progress-bar').css('width',0).text("");
                            uploadbar.hide();
                        },

                        error: function (jqXHR, status, error) {
                            console.log(jqXHR);
                            console.log(status);
                            console.log(error);
                        }
                    });

                } else {
                    alert('Váš vybraný soubor není zvukový soubor!');
                }
            });

            var autoplayToggle = form.find('#audio-autoplay');
            autoplayToggle.off('click').on('click', function (e) {
                if (this.checked) {
                    audio.attr('autoplay', 'autoplay');
                } else {
                    audio.removeAttr('autoplay');
                }
            });

            var showcontrolsToggle = form.find('#audio-showcontrols');
            showcontrolsToggle.off('click').on('click', function (e) {
                if (this.checked) {
                    audio.attr('controls', 'controls');
                } else {
                    audio.removeAttr('controls');
                }
            });

            var audioWidth = form.find('#audio-width');
            audioWidth.off('change').on('change', function () {
                audio.css('width', this.value + '%');
            });
        }
    };
})(jQuery);

(function ($) {
    var KEditor = $.keditor;
    var flog = KEditor.log;


    KEditor.components['googlemap'] = {
        getContent: function (component, keditor) {
            flog('getContent "googlemap" component', component);

            var componentContent = component.children('.keditor-component-content');
            componentContent.find('.googlemap-cover').remove();

            return componentContent.html();
        },

        settingEnabled: true,

        settingTitle: 'Nastavení mapy Google',

        initSettingForm: function (form, keditor) {
            flog('initSettingForm "googlemap" component');

            form.append(
                '<form class="form-horizontal">' +
                '   <div class="form-group">' +
                '       <div class="col-sm-12">' +
                '           <button type="button" class="btn btn-block btn-primary btn-googlemap-edit">Aktualizovat mapu</button>' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label class="col-sm-12">Poměr stran</label>' +
                '       <div class="col-sm-12">' +
                '           <button type="button" class="btn btn-sm btn-default btn-googlemap-169">16:9</button>' +
                '           <button type="button" class="btn btn-sm btn-default btn-googlemap-43">4:3</button>' +
                '       </div>' +
                '   </div>' +
                '</form>'
            );

            var btnEdit = form.find('.btn-googlemap-edit');
            btnEdit.on('click', function (e) {
                e.preventDefault();

                var inputData = prompt('Zadejte zde kód pro vložení mapy Google:');
                var iframe = $(inputData);
                var src = iframe.attr('src');
                if (iframe.length > 0 && src && src.length > 0) {
                    keditor.getSettingComponent().find('.embed-responsive-item').attr('src', src);
                } else {
                    alert('Kód pro vkládání mapy Google je neplatný!');
                }
            });

            var btn169 = form.find('.btn-googlemap-169');
            btn169.on('click', function (e) {
                e.preventDefault();

                keditor.getSettingComponent().find('.embed-responsive').removeClass('embed-responsive-4by3').addClass('embed-responsive-16by9');
            });

            var btn43 = form.find('.btn-googlemap-43');
            btn43.on('click', function (e) {
                e.preventDefault();

                keditor.getSettingComponent().find('.embed-responsive').removeClass('embed-responsive-16by9').addClass('embed-responsive-4by3');
            });
        }
    };

})(jQuery);


(function ($) {
    var KEditor = $.keditor;
    var flog = KEditor.log;

    KEditor.components['photogallery'] = {
        init: function (contentArea, container, component, keditor) {
            var images = [
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/car.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/city.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/deer.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/flowers.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/food.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/guy.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/landscape.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/lips.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/night.jpg',
                'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/table.jpg'
            ];
            //flog('init "photo" component', component);
            var gallery = component.find('.gallery');
            var galleryItems = component.find('.gallery-item');
            var numOfItems = gallery.children().length;
            var itemWidth = 23; // percent: as set in css

            var featured = component.find('.featured-item');

            var leftBtn = component.find('.move-btn.left');
            var rightBtn = component.find('.move-btn.right');

            var leftInterval;
            var rightInterval;

            var scrollRate = 0.2;
            var left;


            function galleryWrapLeft() {
                var first = gallery.children().first();
                gallery.find(first).remove();
                gallery.css('left', (-itemWidth + '%'));
                gallery.append(first);
                gallery.css('left', '0%');
                addEvents();
            }

            function galleryWrapRight() {
                var last = gallery.children().eq(gallery.children().length - 1);
                last.bind('click');
                gallery.prepend(last);
                gallery.css('left', '23%');
                addEvents();
            }

            function moveLeft() {
                left = left || 0;

                leftInterval = setInterval(function () {
                    gallery.css('left', (left + '%'));

                    if (left > -itemWidth) {
                        left -= scrollRate;
                    } else {
                        left = 0;
                        galleryWrapLeft();
                    }
                }, 1);
            }

            function moveRight() {
                //Make sure there is element to the leftd
                if (left > -itemWidth && left < 0) {
                    left = left - itemWidth;

                    var last = gallery.children().eq(gallery.children().length - 1);
                    gallery.find(last).remove();
                    gallery.css('left', (left + '%'));
                    gallery.prepend(last);
                }

                left = left || 0;

                leftInterval = setInterval(function () {
                    gallery.css('left', (left + '%'));

                    if (left < 0) {
                        left += scrollRate;
                    } else {
                        left = -itemWidth;
                        galleryWrapRight();
                    }
                }, 1);
            }


            function stopMovement() {
                clearInterval(leftInterval);
                clearInterval(rightInterval);
            }

            function selectItem(e) {
                if (e.hasClass('active')) return;

                featured.css('background-image', e.css('background-image'));
                gallery.find('.gallery-item').each(function () {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    }
                });

                e.addClass('active');
            }

            function addEvents() {
                gallery.find('.gallery-item').each(function (index) {
                    $(this).on('click', function (e) {
                        selectItem($(this));
                    });
                });
            }


            leftBtn.on('mouseenter', function () {
                moveLeft();
            });
            leftBtn.on('mouseleave', function () {
                stopMovement();
            });
            rightBtn.on('mouseenter', function () {
                moveRight();
            });
            rightBtn.on('mouseleave', function () {
                stopMovement();
            });


            //Set random data-id if Gallery doesn't have one
            if (!component.find('.photogallery-container').attr('data-id')) {
                component.find('.photogallery-container').attr('data-id', guid());
            }

            //Set Images for Gallery and Add Event Listeners


            //Set random data-id if Gallery doesn't have one
            if (!component.find('.photogallery-container').attr('data-id')) {
                component.find('.photogallery-container').attr('data-id', guid());
            } else {
                if (getPhotogalleryImages(component.find('.photogallery-container').attr('data-id')).length > 0) {
                    images = getPhotogalleryImages(component.find('.photogallery-container').attr('data-id'));
                }
            }

            //Set Initial Featured Image
            featured.css('background-image', 'url(' + images[0] + ')');
            gallery.find('.gallery-item').each(function (index) {
                $(this).removeClass('active');
                $(this).css('background-image', 'url(' + images[index] + ')');
            });

            galleryItems.first().addClass('active');


            addEvents();
        },

        settingEnabled: true,

        settingTitle: 'Nastavení Fotogalerii',

        initSettingForm: function (form, keditor) {

            form.append(
                '<form class="form-horizontal">' +
                '   <div class="form-group">' +
                '       <div class="col-sm-12">' +
                '           <button type="button" aria-hidden="true" data-toggle="modal" data-target="#photogalleryModal" class="btn btn-block btn-primary" id="photo-edit">Upravit</button>' +
                '       </div>' +
                '   </div>' +
                '</form>'
            );

        },

        showSettingForm: function (form, component, keditor) {
            var pg_container_id = component.find('.photogallery-container').attr('data-id');
            var galleryItems = [];
            var images = [];
            var featured = component.find('.featured-item');

            var gallery = component.find('.gallery');

            var leftBtn = component.find('.move-btn.left');
            var rightBtn = component.find('.move-btn.right');

            var left;

            leftBtn.bind('mouseenter');
            leftBtn.bind('mouseleave');
            rightBtn.bind('mouseenter');
            rightBtn.bind('mouseleave');

            function addEvents() {
                galleryItems.each(function (index) {
                    $(this).css('background-image', 'url(' + images[index] + ')');
                    $(this).on('click', function (e) {
                        selectItem($(this));
                    });
                });
            }

            function selectItem(e) {
                if (e.hasClass('active')) return;

                featured.css('background-image', e.css('background-image'));
                galleryItems.each(function () {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    }
                });

                e.addClass('active');
            }

            function photoGalleryEvents() {

                var photogalleryModal = $('#photogalleryModal');
                photogalleryModal.find('.overlay').hide();
                photogalleryModal.find('.delete_photo').on('click', function (e) {
                    e.preventDefault();
                    var image_src = $(this).closest('.image_box').find('img').attr('src');
                    $.nette.ajax({
                        type: "POST",
                        url: $(this).data('url'),
                        data: {
                            path: image_src,
                            type: 'photogallery/' + pg_container_id
                        },
                    });
                });
                photogalleryModal.find('.image_box').on('mouseover', function () {
                    $(this).children('.overlay').finish().fadeIn();
                });

                photogalleryModal.find('.image_box').on('mouseout', function () {
                    $(this).children('.overlay').finish().fadeOut();
                });

            }

            $('#photogalleryModal').on('shown.bs.modal', function () {

                // Get images from photogallery
                var url = $('#photogalleryModal').data('url');
                $.nette.ajax({
                    type: "POST",
                    url: url,
                    data: {pgPath: 'photogallery/' + pg_container_id},

                    success: function (payload) {
                    },

                    error: function (jqXHR, status, error) {
                        console.log(jqXHR);
                        console.log(status);
                        console.log(error);
                    }
                });


                $('#photogallery_images').sortable({items: ".image_box"});
                $(this).find('input[name="images"]').off().on('change', function () {
                    uploadImages($('#photogalleryModal').find('#upload_bar'), $(this), 'photogallery/' + pg_container_id);
                });

                $(document).ajaxStop(function () {
                    photoGalleryEvents();
                });


                $(this).find('#save_photogallery').off().on('click', function () {
                    var gallery = component.find('.gallery');
                    gallery.html('');
                    $('#photogallery_images').find('.image_box').each(function () {
                        var image =
                            $('<div class="item-wrapper">' +
                                '<figure class="gallery-item image-holder r-3-2 transition"></figure>' +
                                '</div>');
                        //image.find('.gallery-item').css('background-image','url(' +  $(this).find('img').attr('src') + ')');
                        images.push($(this).find('img').attr('src'));
                        gallery.append(image);
                        gallery.find('.gallery-item').each(function (index) {
                            $(this).css('background-image', 'url(' + images[index] + ')');
                        });


                        galleryItems = component.find('.gallery-item');


                        galleryItems.each(function (index) {
                            $(this).css('background-image', 'url(' + images[index] + ')');
                        });


                        galleryItems.first().addClass('active');

                        addEvents();

                        component.find('.featured-item').css('background-image', 'url(' + images[0] + ')');
                    });

                });

            });


        }
    };

})(jQuery);


(function ($) {
    var KEditor = $.keditor;
    var flog = KEditor.log;

    KEditor.components['photo'] = {
        init: function (contentArea, container, component, keditor) {
            flog('init "photo" component', component);

            var componentContent = component.children('.keditor-component-content');
            var img = componentContent.find('img');

            img.css('display', 'inline-block');
        },

        settingEnabled: true,

        settingTitle: 'Nastavení Obrázku',

        initSettingForm: function (form, keditor) {
            flog('initSettingForm "photo" component');

            var self = this;
            var options = keditor.options;

            form.append(
                '<form class="form-horizontal">' +
                '   <div class="form-group">' +
                '       <div class="col-sm-12">' +
                '           <button type="button" class="btn btn-block btn-primary" id="photo-edit">Změnit obrázek</button>' +
                '           <input data-url=' + $('#addImage').find('input[name="images"]').data('url') + ' type="file" style="display: none" accept="image/*" />' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label for="photo-align" class="col-sm-12">Zarovnání</label>' +
                '       <div class="col-sm-12">' +
                '           <select id="photo-align" class="form-control">' +
                '               <option value="left">Levá</option>' +
                '               <option value="center">Urpostřed</option>' +
                '               <option value="right">Pravá</option>' +
                '           </select>' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label for="photo-style" class="col-sm-12">Styl</label>' +
                '       <div class="col-sm-12">' +
                '           <select id="photo-style" class="form-control">' +
                '               <option value="">Žádný</option>' +
                '               <option value="img-rounded">Zaoblený</option>' +
                '               <option value="img-circle">Kruh</option>' +
                '               <option value="img-thumbnail">Náhled</option>' +
                '           </select>' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label for="photo-responsive" class="col-sm-12">Responzivní</label>' +
                '       <div class="col-sm-12">' +
                '           <input type="checkbox" id="photo-responsive" />' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label for="photo-width" class="col-sm-12">Šířka</label>' +
                '       <div class="col-sm-12">' +
                '           <input type="number" id="photo-width" class="form-control" />' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label for="photo-height" class="col-sm-12">Výška</label>' +
                '       <div class="col-sm-12">' +
                '           <input type="number" id="photo-height" class="form-control" />' +
                '       </div>' +
                '   </div>' +
                '</form>'
            );

            var photoEdit = form.find('#photo-edit');
            var fileInput = photoEdit.next();
            photoEdit.on('click', function (e) {
                e.preventDefault();

                fileInput.trigger('click');
            });
            fileInput.on('change', function () {
                var file = this.files[0];
                if (/image/.test(file.type)) {

                    var data = new FormData();
                    data.append('image', file);
                    data.append('type', 'images');

                    $.nette.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: $('#addImage').find('input[name="images"]').data('url'),
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,


                        success: function (payload) {
                            var img = keditor.getSettingComponent().find('img');
                            img.attr('src', payload.src);
                            img.css({
                                width: '',
                                height: ''
                            });
                            img.on('load', function () {
                                keditor.showSettingPanel(keditor.getSettingComponent(), options);
                            });
                        },

                        error: function (jqXHR, status, error) {
                            console.log(jqXHR);
                            console.log(status);
                            console.log(error);
                        }
                    });

                } else {
                    alert('Váš vybraný soubor není obrázek!');
                }
            });

            var inputAlign = form.find('#photo-align');
            inputAlign.on('change', function () {
                var panel = keditor.getSettingComponent().find('.photo-panel');
                panel.css('text-align', this.value);
            });

            var inputResponsive = form.find('#photo-responsive');
            inputResponsive.on('click', function () {
                keditor.getSettingComponent().find('img')[this.checked ? 'addClass' : 'removeClass']('img-responsive');
            });

            var cbbStyle = form.find('#photo-style');
            cbbStyle.on('change', function () {
                var img = keditor.getSettingComponent().find('img');
                var val = this.value;

                img.removeClass('img-rounded img-circle img-thumbnail');
                if (val) {
                    img.addClass(val);
                }
            });

            var inputWidth = form.find('#photo-width');
            var inputHeight = form.find('#photo-height');
            inputWidth.on('change', function () {
                var img = keditor.getSettingComponent().find('img');
                var newWidth = +this.value;
                var newHeight = Math.round(newWidth / self.ratio);

                if (newWidth <= 0) {
                    newWidth = self.width;
                    newHeight = self.height;
                    this.value = newWidth;
                }

                img.css({
                    'width': newWidth,
                    'height': newHeight
                });
                inputHeight.val(newHeight);
            });
            inputHeight.on('change', function () {
                var img = keditor.getSettingComponent().find('img');
                var newHeight = +this.value;
                var newWidth = Math.round(newHeight * self.ratio);

                if (newHeight <= 0) {
                    newWidth = self.width;
                    newHeight = self.height;
                    this.value = newHeight;
                }

                img.css({
                    'height': newHeight,
                    'width': newWidth
                });
                inputWidth.val(newWidth);
            });
        },

        showSettingForm: function (form, component, keditor) {
            flog('showSettingForm "photo" component', component);

            var self = this;
            var inputAlign = form.find('#photo-align');
            var inputResponsive = form.find('#photo-responsive');
            var inputWidth = form.find('#photo-width');
            var inputHeight = form.find('#photo-height');
            var cbbStyle = form.find('#photo-style');

            var panel = component.find('.photo-panel');
            var img = panel.find('img');

            var algin = panel.css('text-align');
            if (algin !== 'right' || algin !== 'center') {
                algin = 'left';
            }

            if (img.hasClass('img-rounded')) {
                cbbStyle.val('img-rounded');
            } else if (img.hasClass('img-circle')) {
                cbbStyle.val('img-circle');
            } else if (img.hasClass('img-thumbnail')) {
                cbbStyle.val('img-thumbnail');
            } else {
                cbbStyle.val('');
            }

            inputAlign.val(algin);
            inputResponsive.prop('checked', img.hasClass('img-responsive'));
            inputWidth.val(img.width());
            inputHeight.val(img.height());

            $('<img />').attr('src', img.attr('src')).on('load', (function () {
                self.ratio = this.width / this.height;
                self.width = this.width;
                self.height = this.height;
            }));
        }
    };

})(jQuery);

(function ($) {
    var KEditor = $.keditor;
    var flog = KEditor.log;

    //CKEDITOR.disableAutoInline = true;

    // Text component
    // ---------------------------------------------------------------------
    KEditor.components['text'] = {
        options: {
            toolbarGroups: [
                {name: 'document', groups: ['mode', 'document', 'doctools']},
                {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
                {name: 'forms', groups: ['forms']},
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                {name: 'links', groups: ['links']},
                {name: 'insert', groups: ['Imagebrowser', 'Glyphicons']},
                '/',
                {name: 'clipboard', groups: ['clipboard', 'undo']},
                {name: 'styles', groups: ['styles']},
                {name: 'colors', groups: ['colors']},
                {name: 'tools', groups: ['tools']},
                {name: 'others', groups: ['others']},
            ],
            title: false,
            allowedContent: true, // DISABLES Advanced Content Filter. This is so templates with classes: allowed through
            extraAllowedContent: '*[*]{*}(*)',
            bodyId: 'editor',
            templates_replaceContent: false,
            enterMode: 'P',
            forceEnterMode: true,
            format_tags: 'p;h1;h2;h3;h4;h5;h6',
            removePlugins: 'table,magicline,tabletools',
            extraPlugins: 'glyphicons,widget,lineutils,colordialog,uploadimage',
            removeButtons: 'Save,NewPage,Preview,Print,Templates,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Form,HiddenField,ImageButton,Button,Select,Textarea,TextField,Radio,Checkbox,Outdent,Indent,Blockquote,CreateDiv,Language,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,BGColor,Maximize,About,ShowBlocks,BidiLtr,BidiRtl,Flash,Subscript,Superscript,Anchor',
            minimumChangeMilliseconds: 100,
            //imageBrowser_listUrl: "/websitebuilder/www/project/edit/16?do=getImageJsonList",
            filebrowserImageUploadUrl: '/websitebuilder/www/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserImageBrowseUrl: '',

        },

        init: function (contentArea, container, component, keditor) {
            flog('init "text" component', component);

            var self = this;
            var options = keditor.options;

            var componentContent = component.children('.keditor-component-content');
            componentContent.prop('contenteditable', true);

            componentContent.on('input', function (e) {
                if (typeof options.onComponentChanged === 'function') {
                    options.onComponentChanged.call(contentArea, e, component);
                }

                if (typeof options.onContainerChanged === 'function') {
                    options.onContainerChanged.call(contentArea, e, container);
                }

                if (typeof options.onContentChanged === 'function') {
                    options.onContentChanged.call(contentArea, e);
                }
            });

            var editor = componentContent.ckeditor(self.options).editor;
            editor.on('instanceReady', function () {
                flog('CKEditor is ready', component);

                if (typeof options.onComponentReady === 'function') {
                    options.onComponentReady.call(contentArea, component, editor);
                }
            });
        },

        getContent: function (component, keditor) {
            flog('getContent "text" component', component);

            var componentContent = component.find('.keditor-component-content');
            var id = componentContent.attr('id');
            var editor = CKEDITOR.instances[id];
            if (editor) {
                return editor.getData();
            } else {
                return componentContent.html();
            }
        },

        destroy: function (component, keditor) {
            flog('destroy "text" component', component);

            var id = component.find('.keditor-component-content').attr('id');
            var editor = CKEDITOR.instances[id];
            if (editor) {
                editor.destroy();
            }
        }
    };

})(jQuery);

(function ($) {
    var KEditor = $.keditor;
    var flog = KEditor.log;

    KEditor.components['video'] = {
        getContent: function (component, keditor) {
            flog('getContent "video" component', component);

            var componentContent = component.children('.keditor-component-content');
            var video = componentContent.find('video');
            video.unwrap();

            return componentContent.html();
        },

        settingEnabled: true,

        settingTitle: 'Nastavení Videa',

        initSettingForm: function (form, keditor) {
            flog('init "video" settings', form);

            form.append(
                '<div id="upload_bar" class="progress">' +
                '<div class="progress-bar" role="progressbar" style="width: 0;" aria-valuenow="0"' +
                'aria-valuemin="0" aria-valuemax="100"></div>' +
                '</div>' +
                '<form class="form-horizontal">' +
                '<div class="form-group">' +
                '<label for="videoFileInput" class="col-sm-12">Video soubor</label>' +
                '<div class="col-sm-12">' +
                '<div class="video-toolbar">' +
                '<a href="#" class="btn-videoFileInput btn btn-sm btn-primary"><i class="fa fa-upload"></i></a>' +
                '<input id="videoFileInput" type="file" style="display: none" accept=".mp4">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="video-autoplay" class="col-sm-12">Automatické přehrávání</label>' +
                '<div class="col-sm-12">' +
                '<input type="checkbox" id="video-autoplay" />' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="video-loop" class="col-sm-12">Smyčka</label>' +
                '<div class="col-sm-12">' +
                '<input type="checkbox" id="video-loop" />' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="video-showcontrols" class="col-sm-12">Zobrazit ovládací prvky</label>' +
                '<div class="col-sm-12">' +
                '<input type="checkbox" id="video-showcontrols" checked />' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="" class="col-sm-12">Poměr</label>' +
                '<div class="col-sm-12">' +
                '<input type="radio" name="video-radio" class="video-ratio" value="4/3" checked /> 4:3' +
                '</div>' +
                '<div class="col-sm-12">' +
                '<input type="radio" name="video-radio" class="video-ratio" value="16/9" /> 16:9' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="video-width" class="col-sm-12">Šířka (px)</label>' +
                '<div class="col-sm-12">' +
                '<input type="number" id="video-width" min="320" max="1920" class="form-control" value="320" />' +
                '</div>' +
                '</div>' +
                '</form>'
            );
        },

        showSettingForm: function (form, component, keditor) {
            flog('showSettingForm "video" settings', form, component);

            var options = keditor.options;
            var video = component.find('video');
            var fileInput = form.find('#videoFileInput');
            var btnVideoFileInput = form.find('.btn-videoFileInput');
            var uploadbar = form.find('#upload_bar');
            btnVideoFileInput.on('click', function (e) {
                e.preventDefault();

                fileInput.trigger('click');
            });
            fileInput.off('change').on('change', function () {
                uploadbar.show();
                var file = this.files[0];
                if (/video/.test(file.type)) {
                    var data = new FormData();
                    data.append('video', file);
                    data.append('type', 'video');

                    $.nette.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: $('#addImage').find('input[name="images"]').data('url'),
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,

                        xhr: function() {
                            var xhr = $.ajaxSettings.xhr();
                            xhr.upload.onprogress = function(e) {
                                var percent = (Math.floor(e.loaded / e.total *100) + '%');
                                uploadbar.find('div.progress-bar').css('width',percent).text(percent);
                            };
                            return xhr;
                        },

                        success: function (payload) {
                            video.attr('src', payload.src);

                            video.on('load', (function () {
                                keditor.showSettingPanel(component, options);
                            }));
                            uploadbar.find('div.progress-bar').css('width',0).text("");
                            uploadbar.hide();
                        },

                        error: function (jqXHR, status, error) {
                            console.log(jqXHR);
                            console.log(status);
                            console.log(error);
                        }
                    });

                } else {
                    alert('Váš vybraný soubor není video soubor!');
                }
            });

            var autoplayToggle = form.find('#video-autoplay');
            autoplayToggle.off('click').on('click', function (e) {
                if (this.checked) {
                    video.prop('autoplay', true);
                } else {
                    video.removeProp('autoplay');
                }
            });

            var loopToggle = form.find('#video-loop');
            loopToggle.off('click').on('click', function (e) {
                if (this.checked) {
                    video.prop('loop', true);
                } else {
                    video.removeProp('loop');
                }
            });

            var ratio = form.find('.video-ratio');
            ratio.off('click').on('click', function (e) {
                if (this.checked) {
                    var currentWidth = video.css('width') || video.prop('width');
                    currentWidth = currentWidth.replace('px', '');

                    var currentRatio = this.value === '16/9' ? 16 / 9 : 4 / 3;
                    var height = currentWidth / currentRatio;
                    video.css('width', currentWidth + 'px');
                    video.css('height', height + 'px');
                    video.removeProp('width');
                    video.removeProp('height');
                }
            });

            var showcontrolsToggle = form.find('#video-showcontrols');
            showcontrolsToggle.off('click').on('click', function (e) {
                if (this.checked) {
                    video.attr('controls', 'controls');
                } else {
                    video.removeAttr('controls');
                }
            });

            var videoWidth = form.find('#video-width');
            videoWidth.off('change').on('change', function () {
                video.css('width', this.value + 'px');
                var currentRatio = form.find('.video-ratio:checked').val() === '16/9' ? 16 / 9 : 4 / 3;
                var height = this.value / currentRatio;
                video.css('height', height + 'px');
                video.removeProp('width');
                video.removeProp('height');
            });
        }
    };
})(jQuery);

(function ($) {
    var KEditor = $.keditor;
    var flog = KEditor.log;

    KEditor.components['vimeo'] = {
        getContent: function (component, keditor) {
            flog('getContent "vimeo" component', component);

            var componentContent = component.children('.keditor-component-content');
            componentContent.find('.vimeo-cover').remove();

            return componentContent.html();
        },

        settingEnabled: true,

        settingTitle: 'Vimeo Settings',

        initSettingForm: function (form, keditor) {
            flog('initSettingForm "vimeo" component');

            form.append(
                '<form class="form-horizontal">' +
                '   <div class="form-group">' +
                '       <div class="col-sm-12">' +
                '           <button type="button" class="btn btn-block btn-primary btn-vimeo-edit">Změnit Video</button>' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label class="col-sm-12">Automatické přehrávání</label>' +
                '       <div class="col-sm-12">' +
                '           <input type="checkbox" id="vimeo-autoplay" />' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label class="col-sm-12">Poměr stran</label>' +
                '       <div class="col-sm-12">' +
                '           <button type="button" class="btn btn-sm btn-default btn-vimeo-169">16:9</button>' +
                '           <button type="button" class="btn btn-sm btn-default btn-vimeo-43">4:3</button>' +
                '       </div>' +
                '   </div>' +
                '</form>'
            );

            var btnEdit = form.find('.btn-vimeo-edit');
            btnEdit.on('click', function (e) {
                e.preventDefault();

                var inputData = prompt('Zadejte Vimeo URL adresu Vimeo:');
                var vimeoRegex = /https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/;
                var match = inputData.match(vimeoRegex);
                if (match && match[1]) {
                    keditor.getSettingComponent().find('.embed-responsive-item').attr('src', 'https://player.vimeo.com/video/' + match[1] + '?byline=0&portrait=0&badge=0');
                } else {
                    alert('Vimeo URL adresa je neplatná!');
                    alert('Vimeo URL adresa je neplatná!');
                }
            });

            var btn169 = form.find('.btn-vimeo-169');
            btn169.on('click', function (e) {
                e.preventDefault();

                keditor.getSettingComponent().find('.embed-responsive').removeClass('embed-responsive-4by3').addClass('embed-responsive-16by9');
            });

            var btn43 = form.find('.btn-vimeo-43');
            btn43.on('click', function (e) {
                e.preventDefault();

                keditor.getSettingComponent().find('.embed-responsive').removeClass('embed-responsive-16by9').addClass('embed-responsive-4by3');
            });

            var chkAutoplay = form.find('#vimeo-autoplay');
            chkAutoplay.on('click', function () {
                var embedItem = keditor.getSettingComponent().find('.embed-responsive-item');
                var currentUrl = embedItem.attr('src');
                var newUrl = (currentUrl.replace(/(\?.+)+/, '')) + '?byline=0&portrait=0&badge=0&autoplay=' + (chkAutoplay.is(':checked') ? 1 : 0);

                flog('Current url: ' + currentUrl, 'New url: ' + newUrl);
                embedItem.attr('src', newUrl);
            });
        },

        showSettingForm: function (form, component, keditor) {
            flog('showSettingForm "vimeo" component', component);

            var embedItem = component.find('.embed-responsive-item');
            var chkAutoplay = form.find('#vimeo-autoplay');
            var src = embedItem.attr('src');

            chkAutoplay.prop('checked', src.indexOf('autoplay=1') !== -1);
        }
    };

})(jQuery);

(function ($) {
    var KEditor = $.keditor;
    var flog = KEditor.log;

    KEditor.components['youtube'] = {
        getContent: function (component, keditor) {
            flog('getContent "youtube" component', component);

            var componentContent = component.children('.keditor-component-content');
            componentContent.find('.youtube-cover').remove();

            return componentContent.html();
        },

        settingEnabled: true,

        settingTitle: 'Nastavení Youtube',

        initSettingForm: function (form, keditor) {
            flog('initSettingForm "youtube" component');

            form.append(
                '<form class="form-horizontal">' +
                '   <div class="form-group">' +
                '       <div class="col-sm-12">' +
                '           <button type="button" class="btn btn-block btn-primary btn-youtube-edit">Změnit video</button>' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label class="col-sm-12">Automatické přehrávání</label>' +
                '       <div class="col-sm-12">' +
                '           <input type="checkbox" id="youtube-autoplay" />' +
                '       </div>' +
                '   </div>' +
                '   <div class="form-group">' +
                '       <label class="col-sm-12">Poměr stran</label>' +
                '       <div class="col-sm-12">' +
                '           <button type="button" class="btn btn-sm btn-default btn-youtube-169">16:9</button>' +
                '           <button type="button" class="btn btn-sm btn-default btn-youtube-43">4:3</button>' +
                '       </div>' +
                '   </div>' +
                '</form>'
            );

            var btnEdit = form.find('.btn-youtube-edit');
            btnEdit.on('click', function (e) {
                e.preventDefault();

                var inputData = prompt('Zadejte Youtube URL adresu URL zde:');
                var youtubeRegex = /^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/;
                var match = inputData.match(youtubeRegex);
                if (match && match[1]) {
                    keditor.getSettingComponent().find('.embed-responsive-item').attr('src', 'https://www.youtube.com/embed/' + match[1]);
                } else {
                    alert('YouTube URL adresa je neplatná!');
                }
            });

            var btn169 = form.find('.btn-youtube-169');
            btn169.on('click', function (e) {
                e.preventDefault();

                keditor.getSettingComponent().find('.embed-responsive').removeClass('embed-responsive-4by3').addClass('embed-responsive-16by9');
            });

            var btn43 = form.find('.btn-youtube-43');
            btn43.on('click', function (e) {
                e.preventDefault();

                keditor.getSettingComponent().find('.embed-responsive').removeClass('embed-responsive-16by9').addClass('embed-responsive-4by3');
            });

            var chkAutoplay = form.find('#youtube-autoplay');
            chkAutoplay.on('click', function () {
                var embedItem = keditor.getSettingComponent().find('.embed-responsive-item');
                var currentUrl = embedItem.attr('src');
                var newUrl = (currentUrl.replace(/(\?.+)+/, '')) + '?autoplay=' + (chkAutoplay.is(':checked') ? 1 : 0);

                flog('Current url: ' + currentUrl, 'New url: ' + newUrl);
                embedItem.attr('src', newUrl);
            });
        },

        showSettingForm: function (form, component, keditor) {
            flog('showSettingForm "youtube" component', component);

            var embedItem = component.find('.embed-responsive-item');
            var chkAutoplay = form.find('#youtube-autoplay');
            var src = embedItem.attr('src');

            chkAutoplay.prop('checked', src.indexOf('autoplay=1') !== -1);
        }
    };

})(jQuery);
