/** WPLP back-end jQuery script v.0.1 **/

(function ($) {
    $(document).ready(function () {
        $('.wplp_datepicker').pickadate();
        $('#default_img').dropify();
        $('select#theme').change(function (e) {
            var theme_img = themes[$(this).val()]['theme_url'] + '/screenshot.svg';
            var bgcolor = '#ff0000';
            if (theme_img.indexOf('portfolio') > -1) {
                bgcolor = 'transparent';
            } else if (theme_img.indexOf('masonry-category') > -1 || theme_img.indexOf('timeline') > -1) {
                bgcolor = '#444444';
            } else if (theme_img.indexOf('masonry') > -1 || theme_img.indexOf('material-vertical') > -1) {
                bgcolor = '#32323c';
            } else if (theme_img.indexOf('smooth-effect') > -1) {
                bgcolor = '#eeeeee';
            }

            if (theme_img.indexOf('masonry') > -1) {
                $('#overlayBackground').val('0.7').change();
            } else {
                $('#overlayBackground').val('0.2').change();
            }

            $('input#colorpicker').attr('value', bgcolor).change();
            $('input#colorpicker').closest('li').find('.wp-color-result').css('background-color', bgcolor);
        });

        $("#colorPicker .colorInner").unbind("mousedown");

        // /** Theme preview drop-down **/
        $('select#theme').change(function (e) {
            ThemeChange($(this));
        });

        ThemeChange($('select#theme'), true);

        function ThemeChange(object, first) {
            var theme_img = themes[object.val()]['theme_url'] + '/screenshot.svg';
            /*
             *
             * If theme is Premium > disable Option
             * else theme is defautl > enable Option
             *
             */
            if (!first) {
                $('.wplp-theme-preview img').fadeOut(200, function () {
                    $(this).attr('src', theme_img).bind('onreadystatechange load', function () {
                        if (this.complete) $(this).fadeIn(400);
                    });
                });
            }

            if (object.val().indexOf("masonry") > -1 || object.val().indexOf("material-vertical") > -1) {
                $("#animation").children(".settings-wrapper").hide();

                $("#wplp-arrow-color, #wplp-readmore-config, #wplp-overlay-config").hide();

                $(".theme-image-position, .theme-new-item, .pagination, .number-rows, .default-color-theme, .lazyloading-btn, .image-position, .crop-text").hide();

                // Show
                $("#animation").children(".white-content").show();

                $("#wplp-default-color").show();

                $(".more-btn-property, .load-more-btn, .force-icon-hover, .crop-text, .number-columns").show();

            } else if (object.val().indexOf("smooth") > -1) {
                $("#wplp-arrow-color, #wplp-arrow-color, #wplp-readmore-config, #wplp-overlay-config").hide();

                $(".theme-image-position, .theme-new-item, .number-rows, .translation-checkbox, .default-color-theme, .force-icon-hover, .more-btn-property, .image-position, .select-hover-icon").hide();

                $("#animation").children(".white-content").hide();
                // Show

                $('#animation').children('.settings-wrapper').show();

                $("#wplp-default-color").show();

                $(".crop-text , .pagination, .number-columns").show();

            } else if (object.val().indexOf("timeline") > -1) {
                $("#animation").children(".settings-wrapper").hide();

                $("#wplp-arrow-color, #wplp-readmore-config, #wplp-overlay-config").hide();

                $(".theme-new-item, .force-icon-hover, .more-btn-property, .number-columns , .number-rows, .pagination, .theme-image-position, .image-position , .default-color-theme").hide();

                // Show
                $("#animation").children(".white-content").show();

                $("#wplp-default-color").show();

                $(".crop-text").show();

            } else if (object.val().indexOf("portfolio") > -1) {
                $('#animation').children('.settings-wrapper').hide();

                $("#wplp-arrow-color, #wplp-readmore-config, #wplp-overlay-config ").hide();

                $(".crop-text, .theme-new-item, .pagination, .number-rows, .more-btn-property, .theme-image-position, .image-position, .force-icon-hover").hide();

                // Show
                $("#animation").children(".white-content").show();

                $("#wplp-default-color").show();

                $(".number-columns, .default-color-theme").show();

            } else {
                $("#wplp-default-color").hide();

                $(".load-more-btn, .force-icon-hover").hide();

                $("#animation").children(".white-content").hide();

                // Show
                $('#animation').children('.settings-wrapper').show();

                $(" #wplp-arrow-color, #wplp-readmore-config , #wplp-overlay-config").show();
                $(".theme-new-item , .crop-text , .pagination, .number-columns , .number-rows, .autoanim-transition, .animation-transition,.more-btn-property, .lazyloading-btn, .theme-image-position, .image-position, .translation-checkbox").show();
            }

            if (object.val().indexOf("masonry-category") > -1) {
                $("#wplp-arrow-color, #wplp-readmore-config, #wplp-overlay-config").hide();

                $(".crop-text , .lazyloading-btn").hide();
            }

            showIconConfig();
        }

        // // poup icon for overlay
        $('#overlayIcon').click(function () {
            $('#overlayIconList').toggle('show');
        });
        $('.wplp-overlay-close').click(function () {
            $('#overlayIconList').toggle('hide');
        });

        $('#overlayIconList .dashicons').click(function () {
            var alt = $(this).attr("alt");
            var iconClass = $(this).attr("class");
            $('#overlayIconSelected').attr('value', alt);
            $('#overlayIconList').toggle('hide');
            $('.overlay-icon #verlay-icon-box').removeClass().addClass(iconClass);
        });

        //force
        $('#wplp_force_icon').change(function () {
            showIconConfig();
        });
        showIconConfig();
        // //popup list icon
        $('#wplp_choose_icon_btn').click(function () {
            $('#iconlist').toggle('show');
        });
        $('.wplp-close').click(function () {
            $('#iconlist').toggle('hide');
            $('#dashicons_selector').attr('value', '');
        });

        $('#iconlist .dashicons, #iconlist .material-icons').click(function () {
            var content;
            var iconClass;
            if ($(this).hasClass('dashicons')) {
                content = $(this).attr("alt");
                iconClass = $(this).attr("class");
                $('.select-hover-icon #masonry-category-icon-box').removeClass().addClass(iconClass);
                $('#dashicons_selector').attr('value', content);
            } else {
                content = $(this).data("content");
                iconClass = $(this).data("class");
                $('.select-hover-icon #material-icon-box').html(iconClass).attr('data-before', '');
                $('#material_icon_selector').val(content);
            }

            $('#iconlist').toggle('hide');
        });

        // remove icon
        $('.wplp_remove_icon_btn').click(function () {
            $('.select-hover-icon #masonry-category-icon-box').removeClass();
            $('.select-hover-icon #material-icon-box').html('').attr('data-before', '');
            $('#material_icon_selector, #dashicons_selector').val('');
        });

        // slider overlay
        $('.overlay-slider').each(function () {
            var field = $(this).data('option');
            var value = $("#" + field).val();
            $(this).slider({
                min: 0,
                max: 1,
                step: 0.1,
                value: value,
                slide: function (event, ui) {
                    $("#" + field).val(ui.value);
                }
            });
        });

        $('.wplp-slider-input').change(function () {
            var id = $(this).attr('id');
            $('.' + id).slider('value', $(this).val());
        });

        $("select.mutilsite_select").change(function () {
            var content_language = $('#selected_content_language').val();
            var val_blog = $(this).val();
            var type = $(this).attr('id');
            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                method: 'POST',
                data: {
                    action: 'change_cat_multisite',
                    val_blog: val_blog,
                    type: type,
                    content_language: content_language,
                    ajaxnonce: wplp_objects.ajaxnonce
                },
                success: function (res) {
                    var type = res.type;

                    if (type.indexOf('post') > -1) {
                        parent = $('.postcat');
                        parent.find('ul').remove();
                        parent.append(res.output);
                    } else if (type.indexOf('page') > -1) {
                        parent = $('.pagecat');
                        parent.find('ul').remove();
                        parent.append(res.output);
                    } else if (type.indexOf('tag') > -1) {
                        parent = $('.tagcat');
                        parent.find('ul').remove();
                        parent.append(res.output);
                    } else if (type.indexOf('cat_list') > -1) {
                        parent = $('.catlistcat');
                        parent.find('ul').remove();
                        parent.append(res.output);
                    }

                }
            });
        });

        $('.wplp_close_notification').click(function () {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'wplp_set_close_notification',
                    task: 'wplp_cookie',
                    ajaxnonce: wplp_objects.ajaxnonce
                },
                success: function (res) {
                    if (res) {
                        $('.wplp_notification.wplp_wrap_notification').remove();
                    }
                }
            });
        });


        if ($('select[name="wplp_source_date_min_switch"]').val() == 'between') {
            $('.source_date_max').show();
        } else {
            $('.source_date_max').hide();
        }
        // change option Show articles created
        $('select[name="wplp_source_date_min_switch"]').change(function () {
            var val = $(this).val();
            if (val === 'between') {
                $('.source_date_max').show();
            } else {
                $('.source_date_max').hide();
            }
        });

        $('#overlayIconList').scroll(function () {
            pos = $(this).scrollTop();
            $('.wplp-overlay-close').css('margin-top', pos + 'px');
        });

        $('.color-box').click(function () {
            var color = $(this).data('id');
            $('.colorPicker-text').attr('value', color).change();
            $('.color-list-box ul li').removeClass('color-selected');
            $(this).addClass('color-selected');
        });

        // Transition theme
        $('input[name=wplp_autoanimation_trans]').click(function () {
            var id = $(this).data('id');

            $('.list-animation-transition li').removeClass();
            $(this).closest('li').addClass(id);

            $('.list-animation-transition li label').removeClass('transition-label-checked');
            $('.list-animation-transition li.' + id + ' label').addClass('transition-label-checked')
        });
        // Slide Transition
        $('input[name=wplp_autoanimation_slidedir]').click(function () {
            var id = $(this).data('id');

            $('.list-slide-transition li').removeClass();
            $(this).closest('li').addClass(id);

            $('.list-slide-transition li label').removeClass('transition-label-checked');
            $('.list-slide-transition li.' + id + ' label').addClass('transition-label-checked')
        });

        // Select visibility
        $('#post-visibility-select').change(function () {
            var val = $(this).val();
            if (val === 'password') {
                $('#password-visibility').show();
            } else {
                $('#password-visibility').hide();
            }
        });

        if ($('#post-visibility-select').val() === 'password') {
            $('#password-visibility').show();
        } else {
            $('#password-visibility').hide();
        }

        const monthNames = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];
        // console.log($("#post-date").datepicker( 'getDate' ));
        $('#post-date').change(function () {
            var val = $(this).val();
            var date = new Date(val);

            $('input[name=jj]').val(date.getDate());
            $('input[name=mm]').val(monthNames[date.getMonth()]);
            $('input[name=aa]').val(date.getFullYear());
        });

        // SEARCH CONTENT SOURCE
        $('.content-search-input').on('input', function () {
            var search = $(this).val().trim().toLowerCase();
            var active_tab = $("input[name=wplp_source_type]").val();
            var type;
            switch (active_tab) {
                case 'src_category' :
                    type = '.postcat';
                    break;
                case 'src_category_list' :
                    type = '.catlistcat';
                    break;
                case 'src_page' :
                    type = '.pagecat';
                    break;
                case 'src_tags' :
                    type = '.tagcat';
                    break;
            }
            // Check per content source with source type
            $(type + ' ul li').each(function () {
                var text = $(this).children('label').text().trim().toLowerCase();

                if (text.indexOf(search) < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });

        $('.content-search-input').on('focus', function () {
            $(this).parent('.content-search-field').addClass('focused');
        }).on('blur', function () {
            $(this).parent('.content-search-field').removeClass('focused');
        });

        function showIconConfig() {
            var current = $('#theme :selected').val();
            if (current === 'masonry-category') {
                $('#masonry-category-icon-box, .wplp-dashicons-list').show();
                $('#hover_img_icon, .select-hover-icon .dropify-wrapper, .wplp-material-icons-list').hide();
            }

            if (current === "masonry") {
                $('#hover_img_icon, .select-hover-icon .dropify-wrapper, .wplp-material-icons-list').show();
                $('#masonry-category-icon-box, .wplp-dashicons-list').hide();
            }

            if (current === "material-vertical") {
                $('#hover_img_icon, .dropify-wrapper, .wplp-material-icons-list').show();
                $('.wplp-dashicons-list, #masonry-category-icon-box').hide();
            }

            if (current !== "material-vertical" && current !== "masonry") {
                $('#material-icon-box, .overlay-background').hide();
            } else {
                $('#material-icon-box, .overlay-background').show();
            }

            if (current === "portfolio" || current === "smooth-effect" || current === "timeline") {
                $('#wplp_force_icon').closest('.fit-block').hide();
            } else {
                $('#wplp_force_icon').closest('.fit-block').show();
            }

            if (current === "default") {
                $('#layzyload_default').closest('.fit-block').show();
            } else {
                $('#layzyload_default').closest('.fit-block').hide();
            }

            if (current === "masonry" || current === "material-vertical" || current === "masonry-category") {
                $('.number-per-page label').text(wplp_objects.per_page_label);
                $('#wplp_load_more').closest('.fit-block').show();
                $('#max_elts').closest('div').show();
                $('.wplp-max-elts').closest('div').show();
                if ($('#wplp_force_icon').is(':checked')) {
                    $('.select-hover-icon').show();
                } else {
                    $('.select-hover-icon').hide();
                }

                if (current === 'masonry-category') {
                    $('.wplp-hover-custom-image-label, .wplp-icon-background').hide();
                } else {
                    $('.wplp-hover-custom-image-label, .wplp-icon-background').show();
                }

                $('#hover_img_icon').dropify();
                $('#hover_img_icon').on('change', function () {
                    $('.wplp-hover-img-input').val(1);
                });
                $('#hover_img_icon').on('dropify.afterClear', function () {
                    $('.wplp-hover-img-input').val(0);
                });
            } else {
                $('.number-per-page label').text(wplp_objects.max_number_label);
                $('#wplp_load_more').closest('.fit-block').hide();
                $('#max_elts').closest('div').hide();
                $('.wplp-max-elts').closest('div').hide();
                $('.select-hover-icon').hide();
            }
        }

        //Tool options

    });

})(jQuery);