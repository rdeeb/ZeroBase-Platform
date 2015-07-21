/** @File: upload.js */
(function ($) {
    /* global wp */
    /* global forms_trans */
    var addAttachmentHtml = function(attachment, item, gallery) {
        if (gallery) {
            var $gallery_preview = $(item).siblings('.gallery-preview');
            $gallery_preview.append('' +
            '<li class="image" data-attachment_id="' + attachment.id + '">' +
            '<img src="' + attachment.url + '" /> <a href="#" class="delete">Delete</a>' +
            '</li>');
        } else {
            singleFileHtml();
        }
    };

    var singleFileHtml = function(attachment, item) {
        $(item).parent().find('.preview').remove();
        if ($(item).hasClass('image_selector')) {
            $(item).parent().append($('<img src="' + attachment.url + '" width="60" class="img preview" />'));
        } else {
            $(item).parent().append($('<div class="doc preview" />'));
        }
        if ($(item).parent().find('.delete').length === 0) {
            $(item).parent().append($(' <button class="button delete submitdelete">Remove File</button>'));
        }
    };

    var handleUpload = function(custom_uploader, item, gallery) {
        var attachments = custom_uploader.state().get('selection');
        if (gallery) {
            var $gallery_preview = $(item).siblings('.gallery-preview');
            $gallery_preview.html('');
        }
        attachments.map(function (attachment) {
            //Handle the gallery attachments
            attachment = attachment.toJSON();

            if (attachment.id) {
                addAttachmentHtml(attachment, item, gallery);
            }

            $(item).siblings('input[type="hidden"]').val(updateGalleryIds(item));

        });
    };

    var updateGalleryIds = function(item) {
        var attachment_ids = '';

        $(item).siblings('.gallery-preview').find('.image').each(function () {
            var attachment_id = $(this).attr('data-attachment_id');
            attachment_ids = attachment_ids + attachment_id + ',';
        });
        return attachment_ids;
    };

    var createUploaderConfig = function(item, gallery) {
        var uploader_config = {
            title:    forms_trans.file_title,
            button:   {
                text: forms_trans.file_submit
            },
            multiple: gallery
        };

        if ($(item).hasClass('image_selector')) {
            uploader_config.title = forms_trans.image_title;
            uploader_config.button = {
                text: forms_trans.image_submit
            };
            uploader_config.library = { type: 'image' };
        }

        if (gallery) {
            uploader_config.title = forms_trans.gallery_title;
            uploader_config.button = {
                text: forms_trans.gallery_submit
            };
            uploader_config.library = { type: 'image' };
        }
        return uploader_config;
    };

    var createUploader = function(item, gallery) {
        var custom_uploader;

        if (gallery === undefined) {
            gallery = false;
        }

        custom_uploader = wp.media.frames.file_frame = wp.media(createUploaderConfig(item, gallery));

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function () {
            handleUpload(custom_uploader, item, gallery);
        });

        custom_uploader.on('open', function () {
            var selection = custom_uploader.state().get('selection');

            //Get ids array from
            var ids = $(item).siblings('input[type="hidden"]').val().split(',');
            ids.forEach(function (id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add(attachment ? [ attachment ] : []);
            });
        });
        return custom_uploader;
    };

    $(document).ready(function(){
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
                var custom_uploader = null;
                e.preventDefault();

                //If the uploader object has already been created, reopen the dialog
                if (custom_uploader === null) {
                    custom_uploader = createUploader();
                }

                //Open the uploader dialog
                custom_uploader.open();
            });
        });

        $('.gallery').each(function (i, item) {
            //Handle the delete of the attachment
            var $gallery_preview = $(item).siblings('.gallery-preview');
            $gallery_preview.css('cursor', 'default');
            $(item).closest('.form-field, .form_row').delegate('.delete', 'click', function (e) {
                e.preventDefault();
                $(this).closest('li.image').remove();

                $(item).siblings('input[type="hidden"]').val(updateGalleryIds(item));

                return false;
            });
            //Handle the gallery selector
            $(item).click(function (e) {
                var custom_uploader = null;
                e.preventDefault();

                //If the uploader object has already been created, reopen the dialog
                if (custom_uploader === null) {
                    custom_uploader = createUploader();
                }

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
                update:               function () {
                    $(item).siblings('input[type="hidden"]').val(updateGalleryIds(item));
                }
            });

        });
    });

})(jQuery);
