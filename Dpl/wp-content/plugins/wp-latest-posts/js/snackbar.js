'use strict';

/**
 * Snackbar main module
 */
var wplpSnackbarModule = void 0;
(function ($) {
    wplpSnackbarModule = {
        snackbar_ids: [],
        $snackbar_wrapper: null, // Snackbar jQuery wrapper
        snackbar_defaults: {
            onClose: function onClose() {}, // Callback function when snackbar is closed
            is_undoable: false, // Show or not the undo button
            onUndo: function onUndo() {}, // Callback function when snackbar is undoed
            is_closable: true, // Can this snackbar be closed by user
            auto_close: true, // Do the snackbar close automatically
            auto_close_delay: 6000, // Time to wait before closing automatically
            is_progress: false, // Do we show the progress bar
            percentage: null // Percentage of the progress bar
        },

        /**
         * Initialize snackbar module
         */
        initModule: function initModule() {
            wplpSnackbarModule.$snackbar_wrapper = $('<div class="wplp-snackbar-wrapper"></div>').appendTo('body');
        },

        /**
         * Display a new snackbar
         * @param options
         * @return HTMLElement the snackbar generated
         */
        show: function show(options) {
            if (options === undefined) {
                options = {};
            }

            // Set default values
            options = $.extend({}, wplpSnackbarModule.snackbar_defaults, options);

            // Generate undo html if needed
            var undo = '';
            if (options.is_undoable) {
                undo = '<a href="#" class="wplp-snackbar-undo">' + wplp.l18n.wplp_undo + '</a>';
            }
            var close = '';
            if (options.is_closable) {
                close = '<a class="wplp-snackbar-close" href="#"><i class="material-icons">close</i></a>';
            }
            var progress = '';
            if (options.is_progress) {
                progress = '<div class="linear-activity wplpliner_progress"> <div class="indeterminate"></div> </div>';
            }
            var id = '';
            if (options.id) {
                id = 'data-id="' + options.id + '"';
            }

            // Generate final snackbar html content
            var snack = '<div ' + id + ' class="wplp-snackbar">\n                        <div class="wplp-snackbar-content">' + options.content + '</div>\n                        ' + undo + '                        \n                        ' + close + '\n                        ' + progress + '\n                    </div>';

            // Add element to the DOM
            var $snack = $(snack).prependTo(wplpSnackbarModule.$snackbar_wrapper);

            // Save options into snackbar jQuery element
            $snack.options = options;

            // Initialize undo function
            $snack.find('.wplp-snackbar-undo').click(function (e) {
                e.preventDefault();

                $snack.options.onUndo();

                // Reset the close function as we've done an undo
                $snack.options.onClose = function () {};

                // Finally close the snackbar
                wplpSnackbarModule.close($snack);
            });

            // Initialize close button
            $snack.find('.wplp-snackbar-close').click(function (e) {
                e.preventDefault();

                wplpSnackbarModule.close($snack);
            });

            // Initialize autoclose feature
            if (options.auto_close) {
                setTimeout(function () {
                    wplpSnackbarModule.close($snack);
                }, options.auto_close_delay);
            }

            // If an id is set save it
            if (options.id !== undefined) {
                wplpSnackbarModule.snackbar_ids[options.id] = $snack;
            }

            return $snack;
        },

        /**
         * Remove a snackbar and call onClose callback if needed
         * @param $snack snackbar element
         */
        close: function close($snack) {
            // Do not run anything if it has already been thrown
            if ($snack === null || $snack.is_closed === true) {
                return;
            }
            $snack.is_closed = true;

            // Hide the snackbar
            $snack.addClass('wplp-snackbar-hidden');

            // Call onClose callback
            $snack.options.onClose();

            // Remove the id if exists
            if ($snack.options.id !== undefined) {
                delete wplpSnackbarModule.snackbar_ids[$snack.options.id];
            }

            // Finally remove the element after waiting that css transition has finished
            setTimeout(function () {
                $snack.remove();
            }, 200);
        },

        /**
         * Retrieve an existing snackbar from its id
         * @param id
         * @return {null|object}
         */
        getFromId: function getFromId(id) {
            if (wplpSnackbarModule.snackbar_ids[id] === undefined) {
                return null;
            }

            return wplpSnackbarModule.snackbar_ids[id];
        },

        /**
         * Set the snackbar progress bar width
         * @param $snack jQuery element representing a snackbar
         * @param percentage int
         */
        setProgress: function setProgress($snack, percentage) {
            if ($snack === null) {
                return;
            }

            var $progress = $snack.find('.wplpliner_progress > div');
            if (percentage !== undefined) {
                $progress.addClass('determinate').removeClass('indeterminate');
                $progress.css('width', percentage + '%');
            } else {
                $progress.addClass('indeterminate').removeClass('determinate');
            }
        }
    };

    // Let's initialize wplp features
    $(document).ready(function () {
        wplpSnackbarModule.initModule();
    });
})(jQuery);
