(function( $ ) {
    // Add Color Picker to all inputs that have 'color-field' class
    jQuery(document).ready(function($){
        $('.wplp_colorPicker').wpColorPicker({
            // you can declare a default color here,
            // or in the data-default-color attribute on the input
            defaultColor: false,
            // a callback to fire whenever the color changes to a valid color
            change: function(event, ui){
                var color = ui.color.toString();
                $(event.target).val(color).change();
            },
            // a callback to fire when the input is emptied or an invalid color
            clear: function(event) {
                $(event.target).val('transparent').change();
            },
            // hide the color picker controls on load
            hide: true,
            // show a group of common colors beneath the square
            // or, supply an array of colors to customize further
            palettes: true
        });

        $('.colorPicker-text').on('change', function () {
            var val = $(this).val();
            $('input[name="wplp_colorpicker_clone"]').val(val).change();
            $('#default-color').prop('checked', true);
        });

        $('.wplp_arrow_color').wpColorPicker();

        $('.wplp-pick-color button[type=button]').click(function(event){
            var id = $(this).closest('.wplp-pick-color').data('id');
            if ( $('#'+id+' .wp-color-result').hasClass('wp-picker-open') ) {
                $('#'+id+' .wp-color-result span.wp-color-result-text').hide();
            } else {
                $('#'+id+' .wp-color-result span.wp-color-result-text').show();
            }
            event.stopPropagation();
        });

        $(window).click(function() {
            $('.wplp-pick-color span.wp-color-result-text').show();
        });
    });

})( jQuery );