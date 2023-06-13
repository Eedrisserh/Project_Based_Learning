(function ( $ ) {
    $(document).ready(function ( $ ) {
        $('.wplp-shortcode-copy').on('click', function () {
            var shortcode = $(this).data('value');
            wplpCopyText(shortcode);
        });

        $('.phpCodeInsert').on('click', function () {
            var shortcode = $(this).val();
            wplpCopyText(shortcode);
        });

        $('.wplp-max-elts').on('change', function () {
            var max = $(this).val();
            $('.wplp-max-elts').val(max);
            $('#max_elts').val(max).change();
        });

        function wplpCopyText(text) {
            let id = "mycustom-clipboard-textarea-hidden-id";
            let existsTextarea = document.getElementById(id);

            if (!existsTextarea) {
                let textarea = document.createElement("textarea");
                textarea.id = id;
                // Place in top-left corner of screen regardless of scroll position.
                textarea.style.position = 'fixed';
                textarea.style.top = 0;
                textarea.style.left = 0;

                // Ensure it has a small width and height. Setting to 1px / 1em
                // doesn't work as this gives a negative w/h on some browsers.
                textarea.style.width = '1px';
                textarea.style.height = '1px';

                // We don't need padding, reducing the size if it does flash render.
                textarea.style.padding = 0;

                // Clean up any borders.
                textarea.style.border = 'none';
                textarea.style.outline = 'none';
                textarea.style.boxShadow = 'none';

                // Avoid flash of white box if rendered for any reason.
                textarea.style.background = 'transparent';
                document.querySelector("body").appendChild(textarea);
                existsTextarea = document.getElementById(id);
            }

            existsTextarea.value = text;
            existsTextarea.select();

            var status = document.execCommand('copy');
            if (status) {
                wplpSnackbarModule.show({
                    content: wplp_trans.l18n.copy_success,
                    auto_close_delay: 1000
                });
            } else {
                wplpSnackbarModule.show({
                    content: wplp_trans.l18n.copy_error,
                    auto_close_delay: 1000
                });
            }
        }

        // Function for searching menus
        $('.widget-search-input').on('focus', function () {
            $(this).parent('.widget-search-wrapper').addClass('focused');
        }).on('blur', function () {
            $(this).parent('.widget-search-wrapper').removeClass('focused');
        });

        // Sort blocks
        function sortBlocks(sortBy, asc) {
            if (typeof asc === 'undefined') asc = false;

            var tbody = $('#blocks-list').find('tbody');
            tbody.find('tr').sort(function(a, b) {
                if (asc) {
                    return $('td.block-' + sortBy, a).text().localeCompare($('td.block-' + sortBy, b).text());
                } else {
                    return $('td.block-' + sortBy, b).text().localeCompare($('td.block-' + sortBy, a).text());
                }
            }).appendTo(tbody);
        }

        // Clicking header to sort
        $('#blocks-list thead .sorting-header').unbind('click').click(function () {
            var sortBy = $(this).data('sort');
            var asc = true;

            if ($(this).hasClass('asc')) {
                asc = false;
                $('#blocks-list').find('.sorting-header').removeClass('desc').removeClass('asc');
                $('#blocks-list').find('.block-header-'+ sortBy).addClass('desc');
            } else {
                $('#blocks-list').find('.sorting-header').removeClass('desc').removeClass('asc');
                $('#blocks-list').find('.block-header-'+ sortBy).addClass('asc');
            }

            sortBlocks(sortBy, asc);
            return false;
        });

        // Search blocks
        $('.block-search-input').on('input', function () {
            var searchKey = $(this).val().trim().toLowerCase();

            $('#blocks-list .wplp-block').each(function () {
                var blockTitle = $(this).find('.block-title').text().trim().toLowerCase(),
                    blockAuthor = $(this).find('.block-author').text().trim().toLowerCase();

                if (blockTitle.indexOf(searchKey) > -1 || blockAuthor.indexOf(searchKey) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            })
        });

        // Check all checkboxes
        $('.select-all-block').click(function () {
            $('.select-all-block').prop('checked', this.checked);
            $(this).closest('#blocks-list').find('tbody .block-checkbox input[type=checkbox]').prop('checked', this.checked);
        });

        $('.block-checkbox input[type=checkbox]').click(function () {
            if (!this.checked) {
                $('.select-all-block').prop('checked', this.checked);
            }
        });

        $('.wplp-duplicate-block').unbind('click').click(function () {
            var blocksNonce = $('#wplp_blocks_nonce').val();
            var blockID = $(this).data('block-id');
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'wplp_duplicate_block',
                    ajaxNonce: blocksNonce,
                    id: blockID
                },
                beforeSend: function () {
                    wplpSnackbarModule.show({
                        id: 'wplp-duplicate-block',
                        content: wplp_trans.l18n.copying_block,
                        auto_close: false,
                        is_progress: true
                    });
                },
                success: function (res) {
                    if (res.status) {
                        let $snack = wplpSnackbarModule.getFromId('wplp-duplicate-block');
                        wplpSnackbarModule.close($snack);
                        wplpSnackbarModule.show({
                            content: wplp_trans.l18n.copy_block_success,
                            auto_close_delay: 3000
                        });
                        location.reload();
                    }
                }
            })
        });

        // DELETE block
        // Click delete single block
        $('.block-delete').unbind('click').click(function () {
            var willDelete = confirm('Are you sure to delete this block? This action cannot be undone.');
            var blockID = $(this).data('block-id');

            if (willDelete) deleteBlocks([blockID]);
        });

        // Click delete multi-blocks
        $('#delete-blocks').unbind('click').click(function () {
            var blockIDs = [];
            var blocksChecked = $('#blocks-list').find('.block-checkbox input:checkbox:checked');

            if (blocksChecked.length < 1) {
                alert( 'No blocks selected!' );
                return false;
            }

            blocksChecked.each(function () {
                blockIDs.push($(this).val());
            });

            var willDelete = confirm('Are you sure to delete these blocks? This action cannot be undone.');
            if (willDelete) deleteBlocks(blockIDs);
        });

        // Delete blocks by id
        function deleteBlocks(ids) {
            var blocksNonce = $('#wplp_blocks_nonce').val();

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'wplp_delete_blocks',
                    ajaxNonce: blocksNonce,
                    ids: ids
                },
                success: function (res) {
                    res.deleted.forEach(function (id, index) {
                        setTimeout(function () {
                            $('.wplp-block[data-block-id='+ id +']').fadeOut(300, function () {
                                $(this).remove();
                            });
                        }, index * 500);
                    })
                },
                error: function ( xhr, error ) {
                    alert(error + ' - ' + xhr.responseText);
                }
            })
        }
    })
})(jQuery);