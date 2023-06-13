/** WPLP back-end jQuery script v.0.1 **/

(function($){
    $( document ).ready(function() {
        // Storage source type
        // Using to get content language
        selected_type = $('input[name=wplp_source_type]').val();
        $('#selected_source_type').val(selected_type);

        $('.content-source-tab li.tab').click(function () {
            dataID = $(this).data('id');
            $('#selected_source_type').val(dataID);
        });

        // Storage blog for category list type content
        // Using to get content language
        $("select[name=wplp_mutilsite_cat_list]").change(function(){
            var selected_multisite_cat_list_post_type = $(this).val();
            $('#selected_multisite_cat_list_post_type').val(selected_multisite_cat_list_post_type);
        });
        // Storage blog for post type content
        // Using to get content language
        $("select[name=wplp_mutilsite_cat]").change(function(){
            var selected_multisite_post_type = $(this).val();
            $('#selected_multisite_post_type').val(selected_multisite_post_type);
        });
        // Storage blog for page type content
        // Using to get content language
        $("select[name=wplp_mutilsite_page]").change(function(){
            var selected_multisite_page_type = $(this).val();
            $('#selected_multisite_page_type').val(selected_multisite_page_type);
        });
        // Storage blog for tags type content
        // Using to get content language
        $("select[name=wplp_mutilsite_tag]").change(function(){
            var selected_multisite_tags_type = $(this).val();
            $('#selected_multisite_tags_type').val(selected_multisite_tags_type);
        });
        // Storage blog for custom post type content
        // Using to get content language

        $("select[name=wplp_content_language]").change(function(){
            var custom_posttype_language = $(this).val();
            $('#custom_posttype_language').val(custom_posttype_language);
            $('#selected_content_language').val(custom_posttype_language);
        });






        $("#content_language").on('change',function(){
            var current_page =  $('#selected_source_type').val();
            var blog_catlist = $('#selected_multisite_cat_list_post_type').val();
            var blog_post = $('#selected_multisite_post_type').val();
            var blog_page = $('#selected_multisite_page_type').val();
            var blog_tags = $('#selected_multisite_tags_type').val();
            var language = $(this).val();
            loading = '<div style="content-language-loading"><img src="' + content_language_param.plugin_dir + '/css/images/loading.gif"</div>';
            if(current_page === 'src_category'){
                $('.postcat ul.craft').html(loading);
            }else if(current_page === 'src_page'){
                $('.pagecat ul.craft').html(loading);
            }else if(current_page === 'src_tags'){
                $('.tagcat ul.craft').html(loading);
            }else if(current_page === 'src_category_list') {
                $('.catlistcat ul.craft').html(loading);
            }
            $.ajax({
                url : ajaxurl,
                dataType : 'json',
                method : 'POST',
                data : {
                    action : 'change_source_type_by_language',
                    language : language,
                    page : current_page,
                    blog_post : blog_post,
                    blog_page : blog_page,
                    blog_tags : blog_tags,
                    blog_catlist : blog_catlist,
                    security : _token_name.wplp_nonce
                },success : function(res){
                    if(res.type === 'src_category'){
                        $('.postcat ul.craft').html(res.output);
                    }else if(res.type === 'src_page'){
                        $('.pagecat ul.craft').html(res.output);
                    }else if(res.type === 'src_tags'){
                        $('.tagcat ul.craft').html(res.output);
                    }else if(res.type === 'src_category_list'){
                        $('.catlistcat ul.craft').html(res.output);
                    }

                    $('.wplp_change_content').on('change', function() {
                        var wplp_id = $('[name="wplp_id"]').val();
                        $.ajax({
                            url : ajaxurl,
                            dataType : 'json',
                            method : 'POST',
                            data : {
                                action : 'wplp_get_count_posts',
                                wplp_id : wplp_id,
                                settings: $('form').serialize(),
                                wplp_nonce: _token_name.wplp_nonce
                            },
                            beforeSend: function() {
                                $('.wplp-source-content-selector span.content-selector').text(content_language_param.loading);
                            },
                            success: function(res){
                                $('.wplp-source-content-selector span.content-selector').text(res.count + res.text);
                            }
                        });
                    });
                }
            });
        });

    });
})( jQuery );
