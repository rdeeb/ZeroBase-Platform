(function ($) {
    $('.datepicker').datepicker();
    $('.colorselector').each(function (i, item) {
        $(item).css('background', $(item).val());
        $(item).ColorPicker({
            color:       $(item).val(),
            livePreview: true,
            onSubmit:    function (hsb, hex, rgb, el) {
                $(el).val('#' + hex);
                $(el).ColorPickerHide();
            },
            onChange:    function (hsb, hex) {
                if (hsb.b < 40) {
                    $(item).css('color', '#fff');
                } else {
                    $(item).css('color', '#000');
                }
                $(item).css('background', '#' + hex);
                $(item).val('#' + hex.toUpperCase());
            }
        });
    });

    $('.uploader, .image_selector').each(function (i, item) {
        //Handle the delete
        $(item).parent('.form-field, .form_row').delegate('.delete', 'click', function (e) {
            e.preventDefault();
            $(item).siblings('input[type="hidden"]').val('');
            $(item).siblings('.preview').remove();
            $(this).remove();
        });
        //Handle the selector
        $(item).click(function (e) {
            var custom_uploader;
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            if ($(item).hasClass('image_selector')) {
                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title:    forms_trans.image_title,
                    button:   {
                        text: forms_trans.image_submit
                    },
                    library:  { type: 'image' },
                    multiple: false
                });
            } else {
                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title:    forms_trans.file_title,
                    button:   {
                        text: forms_trans.file_submit
                    },
                    multiple: false
                });
            }

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function () {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                $(item).siblings('input[type="hidden"]').val(attachment.id);
                if ($(item).parent().find('.preview').length == 0) {
                    if ($(item).hasClass('image_selector')) {
                        $(item).parent().append($('<img src="' + attachment.url + '" width="60" class="img preview" />'));
                    } else {
                        $(item).parent().append($('<div class="doc preview" />'));
                    }
                } else {
                    if ($(item).hasClass('image_selector')) {
                        $(item).parent('.form-field, .form_row').find('.preview').attr('src', attachment.url);
                    }
                }

                if ($(item).parent().find('.delete').length == 0) {
                    $(item).parent().append($(' <button class="button delete submitdelete">Remove File</button>'));
                }
            });

            custom_uploader.on('open', function () {
                var selection = custom_uploader.state().get('selection');

                //Get ids array from
                ids = $(item).siblings('input[type="hidden"]').val().split(',');
                ids.forEach(function (id) {
                    attachment = wp.media.attachment(id);
                    attachment.fetch();
                    selection.add(attachment ? [ attachment ] : []);
                });
            });

            //Open the uploader dialog
            custom_uploader.open();
        });
    });

    $('.gallery').each(function (i, item) {
        //Handle the delete of the attachment
        var $gallery_preview = $(item).siblings('.gallery-preview');
        $(item).closest('.form-field, .form_row').delegate('.delete', 'click', function (e) {
            e.preventDefault();
            $(this).closest('li.image').remove();

            var attachment_ids = '';

            $(item).siblings('.gallery-preview').find('.image').css('cursor', 'default').each(function () {
                var attachment_id = $(this).attr('data-attachment_id');
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            console.log(attachment_ids);

            $(item).siblings('input[type="hidden"]').val(attachment_ids);

            return false;
        });
        //Handle the gallery selector
        $(item).click(function (e) {
            var custom_uploader;
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title:    forms_trans.gallery_title,
                button:   {
                    text: forms_trans.gallery_submit
                },
                library:  { type: 'image' },
                multiple: true
            });

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function () {

                var attachments_ids = '';
                var attachments = custom_uploader.state().get('selection');
                $gallery_preview.html('');
                attachments.map(function (attachment) {
                    //Handle the gallery attachments
                    attachment = attachment.toJSON();

                    if (attachment.id) {
                        attachments_ids = attachments_ids ? attachments_ids + "," + attachment.id : attachment.id;
                        $gallery_preview.append('\
                            <li class="image" data-attachment_id="' + attachment.id + '">\
                                <img src="' + attachment.url + '" />\
                                <a href="#" class="delete">Delete</a>\
                            </li>');
                    }

                    $(item).siblings('input[type="hidden"]').val(attachments_ids);

                });
            });

            custom_uploader.on('open', function () {
                var selection = custom_uploader.state().get('selection');

                //Get ids array from
                ids = $(item).siblings('input[type="hidden"]').val().split(',');
                ids.forEach(function (id) {
                    attachment = wp.media.attachment(id);
                    attachment.fetch();
                    selection.add(attachment ? [ attachment ] : []);
                });
            });

            //Open the uploader dialog
            custom_uploader.open();
        });

        $(item).siblings('.gallery-preview').sortable({
            items:                'li.image',
            cursor:               'move',
            scrollSensitivity:    40,
            forcePlaceholderSize: true,
            forceHelperSize:      false,
            helper:               'clone',
            opacity:              0.65,
            placeholder:          'metabox-sortable-placeholder',
            start:                function (event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop:                 function (event, ui) {
                ui.item.removeAttr('style');
            },
            update:               function (event, ui) {
                var attachment_ids = '';

                $gallery_preview.find('.image').each(function () {
                    var attachment_id = $(this).attr('data-attachment_id');
                    attachment_ids = attachment_ids + attachment_id + ',';
                });
                console.log(attachment_ids);

                $(item).siblings('input[type="hidden"]').val(attachment_ids);
            }
        });

    });

    $('.map-selector').each(function(i, item){
        var mapOptions = {
            center: new google.maps.LatLng(9.040860, -79.483337),
            zoom: 13
        }
        if ($(item).find('.gmap-latlong').val() != "")
        {
            mapOptions.center = $(item).find('.gmap-latlong').val();
        }

        var canvas = $(item).find('.map-canvas');
        var map = new google.maps.Map(canvas[0], mapOptions);

        var marker = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            title: 'Click to zoom'
        });

        google.maps.event.addListener(map, 'center_changed', function() {
            // 3 seconds after the center of the map has changed, pan back to the
            // marker.
            window.setTimeout(function() {
                marker.setPosition(map.getCenter())
                $(item).find('.gmap-latlong').val(map.getCenter());
            }, 500);
        });
    });

})(jQuery);