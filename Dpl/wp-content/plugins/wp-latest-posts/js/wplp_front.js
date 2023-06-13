function render_default(widget_params) {

    (function ($) {
        $(document).ready(function () {
            var options = {
                selector: ".defaultflexslide > .parent",
                controlNav: false,
                directionNav: true,
                slideshow: true,
                animation: "slide",
                animationLoop: true,
                pauseOnHover: false,
                pauseOnAction: true,
                direction: "horizontal",
                slideshowSpeed: 7000,
                animationSpeed: 600,
                touch: true,
                start: function (slider) { // fires when the slider loads the first slide
                    $(slider).find('.flex-active-slide img.wplp-lazy-hidden')
                        .each(function () {
                            var src = $(this).attr('data-wplp-src');
                            $(this).removeClass('wplp-lazy-hidden').addClass('wplp-lazy-loaded');
                            $(this).attr('src', src).removeAttr('data-wplp-src');

                        });
                },
                before: function (slider) {
                    var slides = slider.slides;
                    index = slider.animatingTo;
                    $slide = $(slides[index]);

                    // Fires when next slide
                    $slide.find('img.wplp-lazy-hidden').each(function () {
                        if (!$(this).hasClass('wplp-lazy-loaded')) {
                            var src = $(this).attr('data-wplp-src');
                            $(this).attr('src', src).removeAttr('data-wplp-src');
                            $(this).removeClass('wplp-lazy-hidden').addClass('wplp-lazy-loaded');
                        }
                    });

                }
            };
            /*
             *
             *
             * Option
             *
             */
            if (typeof widget_params.pagination != 'undefined') {
                switch (widget_params.pagination) {
                    case '0':
                        options.controlNav = false;
                        options.directionNav = false;
                        break;
                    case '1':
                        options.controlNav = false;
                        options.directionNav = true;
                        break;
                    case '2':
                        options.controlNav = true;
                        options.directionNav = true;
                        break;
                    case '3':
                        options.controlNav = true;
                        options.directionNav = false;
                        break;
                }

            }

            // 0 = None
            // 1 = Arrows
            // 2 = PageNumber + bullet
            // 3 = bullet
            if (typeof widget_params.autoanimate != 'undefined') {
                switch (widget_params.autoanimate) {
                    case '0':
                        options.slideshow = false;
                        break;
                    case '1':
                        options.slideshow = true;
                        break;
                }
            }

            // 0 = off
            // 1 = on
            if (typeof widget_params.autoanimatetrans != 'undefined') {
                switch (widget_params.autoanimatetrans) {
                    case '0':
                        options.animation = "fade";
                        break;
                    case '1':
                        options.animation = "slide";
                        break;
                }
            }

            // 0 = true
            // 1 = false
            if (typeof widget_params.animationloop != 'undefined') {
                switch (widget_params.animationloop) {
                    case '0':
                        options.animationLoop = false;
                        break;
                    case '1':
                        options.animationLoop = true;
                        break;
                }
            }

            if (typeof widget_params.pausehover != 'undefined') {
                switch (widget_params.pausehover) {
                    case '0':
                        options.pauseOnHover = false;
                        break;
                    case '1':
                        options.pauseOnHover = true;
                        break;
                }
            }

            if (typeof widget_params.pauseaction != 'undefined') {
                switch (widget_params.pauseaction) {
                    case '0':
                        options.pauseOnAction = false;
                        break;
                    case '1':
                        options.pauseOnAction = true;
                        break;
                }
            }

            if (typeof widget_params.slidedirection != 'undefined') {
                switch (widget_params.slidedirection) {
                    case '0':
                        options.direction = "horizontal";
                        break;
                    case '1':
                        options.direction = "vertical";
                        break;
                }
            }
            if (typeof widget_params.touch != 'undefined') {
                switch (widget_params.touch) {
                    case '0':
                        options.touch = true;
                        break;
                    case '1':
                        options.touch = false;
                        break;
                }
            }

            widget_params.slideshowspeed = parseInt(widget_params.slideshowspeed);
            if (typeof widget_params.slideshowspeed != 'undefined' && !isNaN(widget_params.slideshowspeed)) {
                options.slideshowSpeed = widget_params.slideshowspeed;
            }

            widget_params.slidespeed = parseInt(widget_params.slidespeed);
            if (typeof widget_params.slidespeed != 'undefined' && !isNaN(widget_params.slidespeed)) {
                options.animationSpeed = widget_params.slidespeed;
            }

            setTimeout(function () {
                $("#wplp_widget_" + widget_params.id).flexslider(options);
            }, 200);
            $('.et_pb_tabs_controls li').on('click', function () {
                setTimeout(function () {
                    $(".et-pb-active-slide #wplp_widget_" + widget_params.id).flexslider(options);
                }, 500);
            });

            $('.elementor-tab-title').on('click', function () {
                setTimeout(function () {
                    $(".elementor-tab-content.elementor-active #wplp_widget_" + widget_params.id).flexslider(options);
                }, 500);
            });

            $('.vc_tta-tabs-list li').on('click', function () {
                setTimeout(function () {
                    $(".vc_active #wplp_widget_" + widget_params.id).flexslider(options);
                }, 500);
            });

            $('.listing-tab-toggle').on('click', function () {
                setTimeout(function () {
                    $(".profile-body.tab-active #wplp_widget_" + widget_params.id).flexslider(options);
                }, 500);
            });
        });

    })(jQuery);
}

jQuery(document).ready(function ($) {
    $('[id^="wplp_widget_"].default').each(function () {
        widget_id = $(this).data('post');
        widget_params = window['WPLP_' + widget_id];
        if (widget_params !== undefined) {
            render_default(widget_params);
        }
    });
});