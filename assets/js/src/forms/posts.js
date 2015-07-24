/** @File: posts.js */
var post_autocomplete_callback = function(item) {
    var val = $(item).val();
    var data = {
        'action': 'post_autocomplete',
        'post_name': val
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    $.post(ajaxurl, data, function(response) {
       return response;
    });
};