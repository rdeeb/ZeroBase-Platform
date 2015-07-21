/** @File: widgets.js */
(function ($) {
    $(document).ready(function(){
        // Attaching the calendars
        $('.datepicker').datepicker();
        // Attaching the color selectors
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
    });
})(jQuery);
