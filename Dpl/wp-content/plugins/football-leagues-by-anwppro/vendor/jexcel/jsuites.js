
/**
 * (c) jSuites Javascript Web Components (v3)
 *
 * Author: Paul Hodel <paul.hodel@gmail.com>
 * Website: https://bossanova.uk/jsuites/
 * Description: Create amazing web based applications.
 *
 * MIT License
 *
 */
;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    global.jSuites = factory();
}(this, (function () {

    'use strict';

var jSuites = function(options) {
    var obj = {}

    obj.init = function() {
    }

    return obj;
}

jSuites.ajax = (function(options) {
    if (! options.data) {
        options.data = {};
    }

    if (options.type) {
        options.method = options.type;
    }

    if (options.data) {
        var data = [];
        var keys = Object.keys(options.data);

        if (keys.length) {
            for (var i = 0; i < keys.length; i++) {
                if (typeof(options.data[keys[i]]) == 'object') {
                    var o = options.data[keys[i]];
                    for (var j = 0; j < o.length; j++) {
                        if (typeof(o[j]) == 'string') {
                            data.push(keys[i] + '[' + j + ']=' + encodeURIComponent(o[j]));
                        } else {
                            var prop = Object.keys(o[j]);
                            for (var z = 0; z < prop.length; z++) {
                                data.push(keys[i] + '[' + j + '][' + prop[z] + ']=' + encodeURIComponent(o[j][prop[z]]));
                            }
                        }
                    }
                } else {
                    data.push(keys[i] + '=' + encodeURIComponent(options.data[keys[i]]));
                }
            }
        }

        if (options.method == 'GET' && data.length > 0) {
            if (options.url.indexOf('?') < 0) {
                options.url += '?';
            }
            options.url += data.join('&');
        }
    }

    var httpRequest = new XMLHttpRequest();
    httpRequest.open(options.method, options.url, true);
    httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    if (options.method == 'POST') {
        httpRequest.setRequestHeader('Accept', 'application/json');
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    } else {
        if (options.dataType == 'json') {
            httpRequest.setRequestHeader('Content-Type', 'text/json');
        }
    }

    // No cache
    if (options.cache != true) {
        httpRequest.setRequestHeader('pragma', 'no-cache');
        httpRequest.setRequestHeader('cache-control', 'no-cache');
    }

    // Authentication
    if (options.withCredentials == true) {
        httpRequest.withCredentials = true
    }

    // Before send
    if (typeof(options.beforeSend) == 'function') {
        options.beforeSend(httpRequest);
    }

    httpRequest.onload = function() {
        if (httpRequest.status === 200) {
            if (options.dataType == 'json') {
                try {
                    var result = JSON.parse(httpRequest.responseText);

                    if (options.success && typeof(options.success) == 'function') {
                        options.success(result);
                    }
                } catch(err) {
                    if (options.error && typeof(options.error) == 'function') {
                        options.error(err, result);
                    }
                }
            } else {
                var result = httpRequest.responseText;

                if (options.success && typeof(options.success) == 'function') {
                    options.success(result);
                }
            }
        } else {
            if (options.error && typeof(options.error) == 'function') {
                options.error(httpRequest.responseText);
            }
        }

        // Global queue
        if (jSuites.ajax.queue && jSuites.ajax.queue.length > 0) {
            jSuites.ajax.send(jSuites.ajax.queue.shift());
        }

        // Global complete method
        if (jSuites.ajax.requests && jSuites.ajax.requests.length) {
            // Get index of this request in the container
            var index = jSuites.ajax.requests.indexOf(httpRequest)
            // Remove from the ajax requests container
            jSuites.ajax.requests.splice(index, 1);
            // Last one?
            if (! jSuites.ajax.requests.length) {
                if (options.complete && typeof(options.complete) == 'function') {
                    options.complete(result);
                }
            }
        }
    }

    // Data
    httpRequest.data = data;

    // Queue
    if (options.queue == true && jSuites.ajax.requests.length > 0) {
        jSuites.ajax.queue.push(httpRequest);
    } else {
        jSuites.ajax.send(httpRequest)
    }

    return httpRequest;
});

jSuites.ajax.send = function(httpRequest) {
    if (httpRequest.data) {
        httpRequest.send(httpRequest.data.join('&'));
    } else {
        httpRequest.send();
    }

    jSuites.ajax.requests.push(httpRequest);
}

jSuites.ajax.exists = function(url, __callback) {
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    if (http.status) {
        __callback(http.status);
    }
}

jSuites.ajax.requests = [];

jSuites.ajax.queue = [];

jSuites.animation = {};

jSuites.animation.slideLeft = function(element, direction, done) {
    if (direction == true) {
        element.classList.add('slide-left-in');
        setTimeout(function() {
            element.classList.remove('slide-left-in');
            if (typeof(done) == 'function') {
                done();
            }
        }, 400);
    } else {
        element.classList.add('slide-left-out');
        setTimeout(function() {
            element.classList.remove('slide-left-out');
            if (typeof(done) == 'function') {
                done();
            }
        }, 400);
    }
}

jSuites.animation.slideRight = function(element, direction, done) {
    if (direction == true) {
        element.classList.add('slide-right-in');
        setTimeout(function() {
            element.classList.remove('slide-right-in');
            if (typeof(done) == 'function') {
                done();
            }
        }, 400);
    } else {
        element.classList.add('slide-right-out');
        setTimeout(function() {
            element.classList.remove('slide-right-out');
            if (typeof(done) == 'function') {
                done();
            }
        }, 400);
    }
}

jSuites.animation.slideTop = function(element, direction, done) {
    if (direction == true) {
        element.classList.add('slide-top-in');
        setTimeout(function() {
            element.classList.remove('slide-top-in');
            if (typeof(done) == 'function') {
                done();
            }
        }, 400);
    } else {
        element.classList.add('slide-top-out');
        setTimeout(function() {
            element.classList.remove('slide-top-out');
            if (typeof(done) == 'function') {
                done();
            }
        }, 400);
    }
}

jSuites.animation.slideBottom = function(element, direction, done) {
    if (direction == true) {
        element.classList.add('slide-bottom-in');
        setTimeout(function() {
            element.classList.remove('slide-bottom-in');
            if (typeof(done) == 'function') {
                done();
            }
        }, 400);
    } else {
        element.classList.add('slide-bottom-out');
        setTimeout(function() {
            element.classList.remove('slide-bottom-out');
            if (typeof(done) == 'function') {
                done();
            }
        }, 100);
    }
}

jSuites.animation.fadeIn = function(element, done) {
    element.classList.add('fade-in');
    setTimeout(function() {
        element.classList.remove('fade-in');
        if (typeof(done) == 'function') {
            done();
        }
    }, 2000);
}

jSuites.animation.fadeOut = function(element, done) {
    element.classList.add('fade-out');
    setTimeout(function() {
        element.classList.remove('fade-out');
        if (typeof(done) == 'function') {
            done();
        }
    }, 1000);
}

jSuites.app = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Path
    obj.el = el;
    obj.options.path = 'pages';

    // Global elements
    var panel = null;

    var actionsheet = document.createElement('div');
    actionsheet.className = 'jactionsheet';
    actionsheet.style.display = 'none';

    var actionContent = document.createElement('div');
    actionContent.className = 'jactionsheet-content';
    actionsheet.appendChild(actionContent);

    // Page container
    var pages = document.querySelector('.pages');
    if (! pages) {
        pages = document.createElement('div');
        pages.className = 'pages';
        el.appendChild(pages);
    }

    // App
    el.classList.add('japp');

    // Jsuites
    document.body.classList.add('jsuites');

    /*
     * Parse javascript from an element
     */
    var parseScript = function(element) {
        // Get javascript
        var script = element.getElementsByTagName('script');
        // Run possible inline scripts
        for (var i = 0; i < script.length; i++) {
            // Get type
            var type = script[i].getAttribute('type');
            if (! type || type == 'text/javascript') {
                eval(script[i].innerHTML);
            }
        }
    }

    /**
     * Pages
     */
    obj.pages = function(route, mixed) {
        if (! obj.pages.container[route]) {
            if (! route) {
                console.error('JSUITES: Error, no route provided');
            } else {
                if (typeof(mixed) == 'function') {
                    var options = {};
                    var callback = mixed;
                } else {
                    // Page options
                    var options = mixed ? mixed : {};
                }

                // Closed
                options.closed = mixed && mixed.closed ? 1 : 0;
                // Keep Route
                options.route = route;

                // New page url
                if (! options.url) {
                    var routePath = route.split('#');
                    options.url = obj.options.path + routePath[0] + '.html';
                }
                // Title
                if (! options.title) {
                    options.title = 'Untitled';
                }

                // Create new page
                obj.pages.container[route] = obj.pages.create(options, callback ? callback : null);
            }
        } else {
            // Update config
            if (mixed) {
                // History
                var ignoreHistory = 0;

                if (typeof(mixed) == 'function') {
                    var callback = mixed;
                } else {
                    if (typeof(mixed.onenter) == 'function') {
                        obj.pages.container[route].options.onenter = mixed.onenter;
                    }
                    if (typeof(mixed.onleave) == 'function') {
                        obj.pages.container[route].options.onleave = mixed.onleave;
                    }

                    // Ignore history
                    ignoreHistory = mixed.ignoreHistory ? 1 : 0; 
                }
            }

            obj.pages.show(obj.pages.container[route], ignoreHistory, callback ? callback : null);
        }
    }

    // Container
    obj.pages.container = {};

    /**
     * Show one page
     */
    obj.pages.show = function(page, ignoreHistory, callback) {
        if (obj.page) {
            if (obj.page != page) {
                // Keep scroll in the top
                window.scrollTo({ top: 0 });

                // Show page
                page.style.display = '';

                var a = Array.prototype.indexOf.call(pages.children, obj.page);
                var b = Array.prototype.indexOf.call(pages.children, page);

                // Leave
                if (typeof(obj.page.options.onleave) == 'function') {
                    obj.page.options.onleave(obj.page, page, ignoreHistory);
                }

                jSuites.animation.slideLeft(pages, (a < b ? 0 : 1), function() {
                    obj.page.style.display = 'none';
                    obj.page = page;
                });

                // Enter
                if (typeof(page.options.onenter) == 'function') {
                    page.options.onenter(page, obj.page, ignoreHistory);
                }
            }
        } else {
            // Show
            page.style.display = '';

            // Enter
            if (typeof(page.options.onenter) == 'function') {
                page.options.onenter(page);
            }

            // Keep current
            obj.page = page;
        }

        // Add history
        if (! ignoreHistory) {
            // Add history
            window.history.pushState({ route: page.options.route }, page.options.title, page.options.route);
        }

        // Callback
        if (typeof(callback) == 'function') {
            callback(page);
        }
    }

    /**
     * Create a new page
     */
    obj.pages.create = function(options, callback) {
        // Create page
        var page = document.createElement('div');
        page.classList.add('page');

        // Always hidden
        page.style.display = 'none';

        // Keep options
        page.options = options ? options : {};

        if (! obj.page) {
            pages.appendChild(page);
        } else {
            pages.insertBefore(page, obj.page.nextSibling);
        }

        jSuites.ajax({
            url: page.options.url,
            method: 'GET',
            success: function(result) {
                // Push to refresh controls
                jSuites.refresh(page, page.options.onpush);

                // Open page
                page.innerHTML = result;
                // Get javascript
                parseScript(page);
                // Set title
                page.setTitle = function(text) {
                    this.children[0].children[0].children[1].innerHTML = text;
                }
                // Show page
                if (! page.options.closed) {
                    obj.pages.show(page);
                }
                // Onload callback
                if (typeof(page.options.onload) == 'function') {
                    page.options.onload(page);
                }
                // Force callback
                if (typeof(callback) == 'function') {
                    callback(page);
                }
            }
        });

        return page;
    }

    // Get page
    obj.pages.get = function(route) {
        if (obj.pages.container[route]) {
            return obj.pages.container[route]; 
        }
    }

    obj.pages.destroy = function() {
        // Current is null
        obj.page = null;
        // Destroy containers
        obj.pages.container = {};
        // Reset container
        if (pages) {
            pages.innerHTML = '';
        }
    }


    /**
     * Panel methods
     */
    obj.panel = function(route, options) {
        if (! panel) {
            // Create element
            panel = document.createElement('div');
            panel.classList.add('panel');
            panel.classList.add('panel-left');
            panel.style.display = 'none';

            // Bind to the app
            el.appendChild(panel);

            // Remote content
            if (route) {
                var url = obj.options.path + route + '.html';

                jSuites.ajax({
                    url: url,
                    method: 'GET',
                    success: function(result) {
                        // Set content
                        panel.innerHTML = result;
                        // Parse scripts
                        parseScript(panel);
                        // Visible?
                        if (! options || ! options.closed) {
                            // Show panel
                            obj.panel.show();
                        }
                    }
                });
            } else {
                obj.panel.show();
            }
        } else {
            obj.panel.show();
        }
    }

    obj.panel.show = function() {
        // Show panel
        panel.style.display = '';

        // Add animation
        if (panel.classList.contains('panel-left')) {
            jSuites.animation.slideLeft(panel, 1);
        } else {
            jSuites.animation.slideRight(panel, 1);
        }
    }

    obj.panel.hide = function() {
        if (panel) {
            // Animation
            if (panel.classList.contains('panel-left')) {
                jSuites.animation.slideLeft(panel, 0, function() {
                    panel.style.display = 'none';
                });
            } else {
                jSuites.animation.slideRight(panel, 0, function() {
                    panel.animation.style.display = 'none';
                });
            }
        }
    }

    obj.panel.get = function() {
        return panel;
    }

    obj.panel.destroy = function() {
        el.removeChild(panel);
        panel = null;
    }

    obj.actionsheet = function(options) {
        if (options) {
            obj.actionsheet.options = options;
        }

        // Reset container
        actionContent.innerHTML = '';

        // Create new elements
        for (var i = 0; i < obj.actionsheet.options.length; i++) {
            var actionGroup = document.createElement('div');
            actionGroup.className = 'jactionsheet-group';

            for (var j = 0; j < obj.actionsheet.options[i].length; j++) {
                var v = obj.actionsheet.options[i][j];
                var actionItem = document.createElement('div');
                var actionInput = document.createElement('input');
                actionInput.type = 'button';
                actionInput.value = v.title;
                if (v.className) {
                    actionInput.className = v.className; 
                }
                if (v.onclick) {
                    actionInput.onclick = v.onclick; 
                }
                if (v.action == 'cancel') {
                    actionInput.style.color = 'red';
                }
                actionItem.appendChild(actionInput);
                actionGroup.appendChild(actionItem);
            }

            actionContent.appendChild(actionGroup);
        }

        // Show
        actionsheet.style.display = '';

        // Append
        el.appendChild(panel);

        // Animation
        jSuites.animation.slideBottom(actionContent, true);
    }

    obj.actionsheet.close = function() {
        if (actionsheet.style.display != 'none') {
            // Remove any existing actionsheet
            jSuites.animation.slideBottom(actionContent, false, function() {
                actionsheet.remove();
                actionsheet.style.display = 'none';
            });
        }
    }

    var controlSwipeLeft = function(e) {
        var element = jSuites.findElement(e.target, 'option');

        if (element && element.querySelector('.option-actions')) {
            element.scrollTo({
                left: 100,
                behavior: 'smooth'
            });
        } else {
            if (panel && panel.style.display != 'none') {
                obj.panel.hide();
            }
        }
    }

    var controlSwipeRight = function(e) {
        var element = jSuites.findElement(e.target, 'option');
        if (element && element.querySelector('.option-actions')) {
            element.scrollTo({
                left: 0,
                behavior: 'smooth'
            });
        } else {
            if (panel && panel.style.display == 'none') {
                obj.panel.show();
            }
        }
    }

    var actionDown = function(e) {
        // Close any actionsheet if is opened
        if (! actionsheet.style.display) {
            obj.actionsheet.close()
        }

        // Grouped options
        if (e.target.classList.contains('option-title')) {
            if (e.target.classList.contains('selected')) {
                e.target.classList.remove('selected');
            } else {
                e.target.classList.add('selected');
            }
        }

        // App links
        var element = jSuites.findElement(e.target, function(e) {
            return (e.tagName == 'A' || e.tagName == 'DIV') && e.getAttribute('data-href') ? e : false;
        });

        if (element) {
            var link = element.getAttribute('data-href');
            if (link == '#back') {
                window.history.back();
            } else if (link == '#panel') {
                obj.panel();
            } else {
                obj.pages(link);
            }
        }
    }

    el.addEventListener('swipeleft', controlSwipeLeft);
    el.addEventListener('swiperight', controlSwipeRight);

    if ('ontouchstart' in document.documentElement === true) {
        document.addEventListener('touchstart', actionDown);
    } else {
        document.addEventListener('mousedown', actionDown);
    }

    window.onpopstate = function(e) {
        if (e.state && e.state.route) {
            if (obj.pages.get(e.state.route)) {
                obj.pages(e.state.route, { ignoreHistory:true });
            }
        }
    }

    el.app = obj;

    return obj;
});


jSuites.calendar = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Global container
    if (! jSuites.calendar.current) {
        jSuites.calendar.current = null;
    }

    // Default configuration
    var defaults = {
        // Data
        data: null,
        // Inline or not
        type: null,
        // Restrictions
        validRange: null,
        // Starting weekday - 0 for sunday, 6 for saturday
        startingDay: null, 
        // Date format
        format: 'DD/MM/YYYY',
        // Allow keyboard date entry
        readonly: true,
        // Today is default
        today: false,
        // Show timepicker
        time: false,
        // Show the reset button
        resetButton: true,
        // Placeholder
        placeholder: '',
        // Translations can be done here
        months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        weekdays: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
        weekdays_short: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
        textDone: 'Done',
        textReset: 'Reset',
        textUpdate: 'Update',
        // Value
        value: null,
        // Fullscreen (this is automatic set for screensize < 800)
        fullscreen: false,
        // Create the calendar closed as default
        opened: false,
        // Events
        onopen: null,
        onclose: null,
        onchange: null,
        // Internal mode controller
        mode: null,
        position: null,
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Value
    if (! obj.options.value) {
        if (el.tagName == 'INPUT' && el.value) {
            obj.options.value = el.value;
        }
    }

    // Make sure use upper case in the format
    obj.options.format = obj.options.format.toUpperCase();

    if (obj.options.value) {
        var date = obj.options.value.split(' ');
        var time = date[1];
        var date = date[0].split('-');
        var y = parseInt(date[0]);
        var m = parseInt(date[1]);
        var d = parseInt(date[2]);

        if (time) {
            var time = time.split(':');
            var h = parseInt(time[0]);
            var i = parseInt(time[1]);
        } else {
            var h = 0;
            var i = 0;
        }
    } else {
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        var h = date.getHours();
        var i = date.getMinutes();
    }

    // Current value
    obj.date = [ y, m, d, h, i, 0 ];

    // Two digits
    var two = function(value) {
        value = '' + value;
        if (value.length == 1) {
            value = '0' + value;
        }
        return value;
    }

    // Calendar elements
    var calendarReset = document.createElement('div');
    calendarReset.className = 'jcalendar-reset';
    calendarReset.innerHTML = obj.options.textReset;

    var calendarConfirm = document.createElement('div');
    calendarConfirm.className = 'jcalendar-confirm';
    calendarConfirm.innerHTML = obj.options.textDone;

    var calendarControls = document.createElement('div');
    calendarControls.className = 'jcalendar-controls'
    if (obj.options.resetButton) {
        calendarControls.appendChild(calendarReset);
    }
    calendarControls.appendChild(calendarConfirm);

    var calendarContainer = document.createElement('div');
    calendarContainer.className = 'jcalendar-container';

    var calendarContent = document.createElement('div');
    calendarContent.className = 'jcalendar-content';
    calendarContent.appendChild(calendarControls);
    calendarContainer.appendChild(calendarContent);

    // Table container
    var calendarTableContainer = document.createElement('div');
    calendarTableContainer.className = 'jcalendar-table';
    calendarContent.appendChild(calendarTableContainer);

    // Main element
    if (el.tagName == 'INPUT') {
        var calendar = document.createElement('div');
    } else {
        var calendar = el;
    }
    calendar.className = 'jcalendar';
    calendar.appendChild(calendarContainer);

    // Previous button
    var calendarHeaderPrev = document.createElement('td');
    calendarHeaderPrev.setAttribute('colspan', '2');
    calendarHeaderPrev.className = 'jcalendar-prev';

    // Header with year and month
    var calendarLabelYear = document.createElement('span');
    calendarLabelYear.className = 'jcalendar-year';

    var calendarLabelMonth = document.createElement('span');
    calendarLabelMonth.className = 'jcalendar-month';

    var calendarHeaderTitle = document.createElement('td');
    calendarHeaderTitle.className = 'jcalendar-header';
    calendarHeaderTitle.setAttribute('colspan', '3');
    calendarHeaderTitle.appendChild(calendarLabelMonth);
    calendarHeaderTitle.appendChild(calendarLabelYear);

    var calendarHeaderNext = document.createElement('td');
    calendarHeaderNext.setAttribute('colspan', '2');
    calendarHeaderNext.className = 'jcalendar-next';

    var calendarHeaderRow = document.createElement('tr');
    calendarHeaderRow.appendChild(calendarHeaderPrev);
    calendarHeaderRow.appendChild(calendarHeaderTitle);
    calendarHeaderRow.appendChild(calendarHeaderNext);

    var calendarHeader = document.createElement('thead');
    calendarHeader.appendChild(calendarHeaderRow);

    var calendarBody = document.createElement('tbody');
    var calendarFooter = document.createElement('tfoot');

    // Calendar table
    var calendarTable = document.createElement('table');
    calendarTable.setAttribute('cellpadding', '0');
    calendarTable.setAttribute('cellspacing', '0');
    calendarTable.appendChild(calendarHeader);
    calendarTable.appendChild(calendarBody);
    calendarTable.appendChild(calendarFooter);
    calendarTableContainer.appendChild(calendarTable);

    var calendarSelectHour = document.createElement('select');
    calendarSelectHour.className = 'jcalendar-select';
    calendarSelectHour.onchange = function() {
        obj.date[3] = this.value; 
    }

    for (var i = 0; i < 24; i++) {
        var element = document.createElement('option');
        element.value = i;
        element.innerHTML = two(i);
        calendarSelectHour.appendChild(element);
    }

    var calendarSelectMin = document.createElement('select');
    calendarSelectMin.className = 'jcalendar-select';
    calendarSelectMin.onchange = function() {
        obj.date[4] = this.value; 
    }

    for (var i = 0; i < 60; i++) {
        var element = document.createElement('option');
        element.value = i;
        element.innerHTML = two(i);
        calendarSelectMin.appendChild(element);
    }

    // Footer controls
    var calendarControlsFooter = document.createElement('div');
    calendarControlsFooter.className = 'jcalendar-controls';

    var calendarControlsTime = document.createElement('div');
    calendarControlsTime.className = 'jcalendar-time';
    calendarControlsTime.style.maxWidth = '140px';
    calendarControlsTime.appendChild(calendarSelectHour);
    calendarControlsTime.appendChild(calendarSelectMin);

    var calendarControlsUpdateButton = document.createElement('input');
    calendarControlsUpdateButton.setAttribute('type', 'button');
    calendarControlsUpdateButton.className = 'jcalendar-update';
    calendarControlsUpdateButton.value = obj.options.textUpdate;

    var calendarControlsUpdate = document.createElement('div');
    calendarControlsUpdate.style.flexGrow = '10';
    calendarControlsUpdate.appendChild(calendarControlsUpdateButton);
    calendarControlsFooter.appendChild(calendarControlsTime);
    calendarControlsFooter.appendChild(calendarControlsUpdate);
    calendarContent.appendChild(calendarControlsFooter);

    var calendarBackdrop = document.createElement('div');
    calendarBackdrop.className = 'jcalendar-backdrop';
    calendar.appendChild(calendarBackdrop);

    // Update actions button
    var updateActions = function() {
        var currentDay = calendar.querySelector('.jcalendar-selected');

        if (currentDay && currentDay.classList.contains('jcalendar-disabled')) {
            calendarControlsUpdateButton.setAttribute('disabled', 'disabled');
            calendarSelectHour.setAttribute('disabled', 'disabled');
            calendarSelectMin.setAttribute('disabled', 'disabled');
        } else {
            calendarControlsUpdateButton.removeAttribute('disabled');
            calendarSelectHour.removeAttribute('disabled');
            calendarSelectMin.removeAttribute('disabled');
        }
    }

    // Methods
    obj.open = function (value) {
        if (! calendar.classList.contains('jcalendar-focus')) {
            if (jSuites.calendar.current) {
                jSuites.calendar.current.close();
            }
            // Current
            jSuites.calendar.current = obj;
            // Show calendar
            calendar.classList.add('jcalendar-focus');
            // Get days
            obj.getDays();
            // Hour
            if (obj.options.time) {
                calendarSelectHour.value = obj.date[3];
                calendarSelectMin.value = obj.date[4];
            }

            // Get the position of the corner helper
            if (jSuites.getWindowWidth() < 800 || obj.options.fullscreen) {
                // Full
                calendar.classList.add('jcalendar-fullsize');
                // Animation
                jSuites.animation.slideBottom(calendarContent, 1);
            } else {
                const rect = el.getBoundingClientRect();
                const rectContent = calendarContent.getBoundingClientRect();

                if (obj.options.position) {
                    calendarContainer.style.position = 'fixed';
                    if (window.innerHeight < rect.bottom + rectContent.height) {
                        calendarContainer.style.top = (rect.top - (rectContent.height + 2)) + 'px';
                    } else {
                        calendarContainer.style.top = (rect.top + rect.height + 2) + 'px';
                    }
                    calendarContainer.style.left = rect.left + 'px';
                } else {
                    if (window.innerHeight < rect.bottom + rectContent.height) {
                        calendarContainer.style.bottom = (1 * rect.height + rectContent.height + 2) + 'px';
                    } else {
                        calendarContainer.style.top = 2 + 'px'; 
                    }
                }
            }

            // Events
            if (typeof(obj.options.onopen) == 'function') {
                obj.options.onopen(el);
            }
        }
    }

    obj.close = function (ignoreEvents, update) {
        if (jSuites.calendar.current) {
            // Current
            jSuites.calendar.current = null;

            if (update !== false) {
                var element = calendar.querySelector('.jcalendar-selected');

                if (typeof(update) == 'string') {
                    var value = update;
                } else if (element && element.classList.contains('jcalendar-disabled')) {
                    var value = obj.options.value
                } else {
                    var value = obj.getValue();
                }

                obj.setValue(value);
            }

            // Events
            if (! ignoreEvents && typeof(obj.options.onclose) == 'function') {
                obj.options.onclose(el);
            }

            // Hide
            calendar.classList.remove('jcalendar-focus');
        }

        return obj.options.value;
    }

    obj.prev = function() {
        // Check if the visualization is the days picker or years picker
        if (obj.options.mode == 'years') {
            obj.date[0] = obj.date[0] - 12;

            // Update picker table of days
            obj.getYears();
        } else {
            // Go to the previous month
            if (obj.date[1] < 2) {
                obj.date[0] = obj.date[0] - 1;
                obj.date[1] = 12;
            } else {
                obj.date[1] = obj.date[1] - 1;
            }

            // Update picker table of days
            obj.getDays();
        }
    }

    obj.next = function() {
        // Check if the visualization is the days picker or years picker
        if (obj.options.mode == 'years') {
            obj.date[0] = parseInt(obj.date[0]) + 12;

            // Update picker table of days
            obj.getYears();
        } else {
            // Go to the previous month
            if (obj.date[1] > 11) {
                obj.date[0] = parseInt(obj.date[0]) + 1;
                obj.date[1] = 1;
            } else {
                obj.date[1] = parseInt(obj.date[1]) + 1;
            }

            // Update picker table of days
            obj.getDays();
        }
    }

    obj.setValue = function(val) {
        if (! val) {
            val = '' + val;
        }
        // Values
        var newValue = val;
        var oldValue = obj.options.value;
        // Set label
        var value = obj.setLabel(newValue, obj.options.format);
        var date = newValue.split(' ');
        if (! date[1]) {
            date[1] = '00:00:00';
        }
        var time = date[1].split(':')
        var date = date[0].split('-');
        var y = parseInt(date[0]);
        var m = parseInt(date[1]);
        var d = parseInt(date[2]);
        var h = parseInt(time[0]);
        var i = parseInt(time[1]);
        obj.date = [ y, m, d, h, i, 0 ];
        var val = obj.setLabel(newValue, obj.options.format);

        if (oldValue != newValue) {
            // Input value
            if (el.tagName == 'INPUT') {
                el.value = val;
            }
            // New value
            obj.options.value = newValue;
            // On change
            if (typeof(obj.options.onchange) ==  'function') {
                obj.options.onchange(el, newValue, oldValue);
            }
        }

        obj.getDays();
    }

    obj.getValue = function() {
        if (obj.date) {
            if (obj.options.time) {
                return two(obj.date[0]) + '-' + two(obj.date[1]) + '-' + two(obj.date[2]) + ' ' + two(obj.date[3]) + ':' + two(obj.date[4]) + ':' + two(0);
            } else {
                return two(obj.date[0]) + '-' + two(obj.date[1]) + '-' + two(obj.date[2]) + ' ' + two(0) + ':' + two(0) + ':' + two(0);
            }
        } else {
            return "";
        }
    }

    /**
     *  Calendar
     */
    obj.update = function(element) {
        if (element.classList.contains('jcalendar-disabled')) {
            // Do nothing
        } else {
            obj.date[2] = element.innerText;

            if (! obj.options.time) {
                obj.close();
            } else {
                obj.date[3] = calendarSelectHour.value;
                obj.date[4] = calendarSelectMin.value;
            }

            var elements = calendar.querySelector('.jcalendar-selected');
            if (elements) {
                elements.classList.remove('jcalendar-selected');
            }
            element.classList.add('jcalendar-selected');
        }

        // Update
        updateActions();
    }

    /**
     * Set to blank
     */
    obj.reset = function() {
        // Close calendar
        obj.setValue('');
        obj.close(false, false);
    }

    /**
     * Get calendar days
     */
    obj.getDays = function() {
        // Mode
        obj.options.mode = 'days';

        // Setting current values in case of NULLs
        var date = new Date();

        // Current selection
        var year = obj.date && obj.date[0] ? obj.date[0] : parseInt(date.getFullYear());
        var month = obj.date && obj.date[1] ? obj.date[1] : parseInt(date.getMonth()) + 1;
        var day = obj.date && obj.date[2] ? obj.date[2] : parseInt(date.getDate());
        var hour = obj.date && obj.date[3] ? obj.date[3] : parseInt(date.getHours());
        var min = obj.date && obj.date[4] ? obj.date[4] : parseInt(date.getMinutes());

        // Selection container
        obj.date = [year, month, day, hour, min, 0 ];

        // Update title
        calendarLabelYear.innerHTML = year;
        calendarLabelMonth.innerHTML = obj.options.months[month - 1];

        // Current month and Year
        var isCurrentMonthAndYear = (date.getMonth() == month - 1) && (date.getFullYear() == year) ? true : false;
        var currentDay = date.getDate();

        // Number of days in the month
        var date = new Date(year, month, 0, 0, 0);
        var numberOfDays = date.getDate();

        // First day
        var date = new Date(year, month-1, 0, 0, 0);
        var firstDay = date.getDay() + 1;

        // Index value
        var index = obj.options.startingDay || 0;

        // First of day relative to the starting calendar weekday
        firstDay = firstDay - index;

        // Reset table
        calendarBody.innerHTML = '';

        // Weekdays Row
        var row = document.createElement('tr');
        row.setAttribute('align', 'center');
        calendarBody.appendChild(row);

        // Create weekdays row
        for (var i = 0; i < 7; i++) {
            var cell = document.createElement('td');
            cell.classList.add('jcalendar-weekday')
            cell.innerHTML = obj.options.weekdays_short[index];
            row.appendChild(cell);
            // Next week day
            index++;
            // Restart index
            if (index > 6) {
                index = 0;
            }
        }

        // Index of days
        var index = 0;
        var d = 0;
 
        // Calendar table
        for (var j = 0; j < 6; j++) {
            // Reset cells container
            var row = document.createElement('tr');
            row.setAttribute('align', 'center');
            // Data control
            var emptyRow = true;
            // Create cells
            for (var i = 0; i < 7; i++) {
                // Create cell
                var cell = document.createElement('td');
                cell.classList.add('jcalendar-set-day');

                if (index >= firstDay && index < (firstDay + numberOfDays)) {
                    // Day cell
                    d++;
                    cell.innerHTML = d;

                    // Selected
                    if (d == day) {
                        cell.classList.add('jcalendar-selected');
                    }

                    // Current selection day is today
                    if (isCurrentMonthAndYear && currentDay == d) {
                        cell.style.fontWeight = 'bold';
                    }

                    // Current selection day
                    var current = jSuites.calendar.now(new Date(year, month-1, d), true);

                    // Available ranges
                    if (obj.options.validRange) {
                        if (! obj.options.validRange[0] || current >= obj.options.validRange[0]) {
                            var test1 = true;
                        } else {
                            var test1 = false;
                        }

                        if (! obj.options.validRange[1] || current <= obj.options.validRange[1]) {
                            var test2 = true;
                        } else {
                            var test2 = false;
                        }

                        if (! (test1 && test2)) {
                            cell.classList.add('jcalendar-disabled');
                        }
                    }

                    // Control
                    emptyRow = false;
                }
                // Day cell
                row.appendChild(cell);
                // Index
                index++;
            }

            // Add cell to the calendar body
            if (emptyRow == false) {
                calendarBody.appendChild(row);
            }
        }

        // Show time controls
        if (obj.options.time) {
            calendarControlsTime.style.display = '';
        } else {
            calendarControlsTime.style.display = 'none';
        }

        // Update
        updateActions();
    }

    obj.getMonths = function() {
        // Mode
        obj.options.mode = 'months';

        // Loading month labels
        var months = obj.options.months;

        // Update title
        calendarLabelYear.innerHTML = obj.date[0];
        calendarLabelMonth.innerHTML = '';

        // Create months table
        var html = '<td colspan="7"><table width="100%"><tr align="center">';

        for (i = 0; i < 12; i++) {
            if ((i > 0) && (!(i % 4))) {
                html += '</tr><tr align="center">';
            }

            var month = parseInt(i) + 1;
            html += '<td class="jcalendar-set-month" data-value="' + month + '">' + months[i] +'</td>';
        }

        html += '</tr></table></td>';

        calendarBody.innerHTML = html;
    }

    obj.getYears = function() { 
        // Mode
        obj.options.mode = 'years';

        // Array of years
        var y = [];
        for (i = 0; i < 25; i++) {
            y[i] = parseInt(obj.date[0]) + (i - 12);
        }

        // Assembling the year tables
        var html = '<td colspan="7"><table width="100%"><tr align="center">';

        for (i = 0; i < 25; i++) {
            if ((i > 0) && (!(i % 5))) {
                html += '</tr><tr align="center">';
            }
            html += '<td class="jcalendar-set-year">'+ y[i] +'</td>';
        }

        html += '</tr></table></td>';

        calendarBody.innerHTML = html;
    }

    obj.setLabel = function(value, format) {
        return jSuites.calendar.getDateString(value, format);
    }

    obj.fromFormatted = function (value, format) {
        return jSuites.calendar.extractDateFromString(value, format);
    }

    var mouseUpControls = function(e) {
        var action = e.target.className;

        // Object id
        if (action == 'jcalendar-prev') {
            obj.prev();
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-next') {
            obj.next();
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-month') {
            obj.getMonths();
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-year') {
            obj.getYears();
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-set-year') {
            obj.date[0] = e.target.innerText;
            obj.getDays();
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-set-month') {
            obj.date[1] = parseInt(e.target.getAttribute('data-value'));
            obj.getDays();
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-confirm' || action == 'jcalendar-update') {
            obj.close();
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-close') {
            obj.close();
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-backdrop') {
            obj.close(false, false);
            e.stopPropagation();
            e.preventDefault();
        } else if (action == 'jcalendar-reset') {
            obj.reset();
            e.stopPropagation();
            e.preventDefault();
        } else if (e.target.classList.contains('jcalendar-set-day')) {
            if (e.target.innerText) {
                obj.update(e.target);
                e.stopPropagation();
                e.preventDefault();
            }
        }
    }

    var keyUpControls = function(e) {
        if (e.target.value && e.target.value.length > 3) {
            var test = jSuites.calendar.extractDateFromString(e.target.value, obj.options.format);
            if (test) {
                if (e.target.getAttribute('data-completed') == 'true') {
                    obj.setValue(test);
                }
            }
        }
    }

    // Handle events
    el.addEventListener("keyup", keyUpControls);

    // Add global events
    calendar.addEventListener("swipeleft", function(e) {
        jSuites.animation.slideLeft(calendarTable, 0, function() {
            obj.next();
            jSuites.animation.slideRight(calendarTable, 1);
        });
        e.preventDefault();
        e.stopPropagation();
    });

    calendar.addEventListener("swiperight", function(e) {
        jSuites.animation.slideRight(calendarTable, 0, function() {
            obj.prev();
            jSuites.animation.slideLeft(calendarTable, 1);
        });
        e.preventDefault();
        e.stopPropagation();
    });

    if ('ontouchend' in document.documentElement === true) {
        calendar.addEventListener("touchend", mouseUpControls);

        el.addEventListener("touchend", function(e) {
            obj.open();
        });
    } else {
        calendar.addEventListener("mouseup", mouseUpControls);

        el.addEventListener("mouseup", function(e) {
            obj.open();
        });
    }

    if (! jSuites.calendar.hasEvents) {
        if ('ontouchstart' in document.documentElement === true) {
            document.addEventListener("touchstart", jSuites.calendar.isOpen);
        } else {
            document.addEventListener("mousedown", jSuites.calendar.isOpen);
        }

        document.addEventListener("keydown", function(e) {
            if (e.which == 13) {
                // ENTER
                if (jSuites.calendar.current) {
                    jSuites.calendar.current.close(false, true);
                }
            } else if (e.which == 27) {
                // ESC
                if (jSuites.calendar.current) {
                    jSuites.calendar.current.close(false, false);
                }
            }
        });

        jSuites.calendar.hasEvents = true;
    }

    // Append element to the DOM
    if (el.tagName == 'INPUT') {
        el.parentNode.insertBefore(calendar, el.nextSibling);
        // Add properties
        el.setAttribute('autocomplete', 'off');
        el.setAttribute('data-mask', obj.options.format.toLowerCase());

        if (obj.options.readonly) {
            el.setAttribute('readonly', 'readonly');
        }
        if (obj.options.placeholder) {
            el.setAttribute('placeholder', obj.options.placeholder);
        }
        // Element
        el.classList.add('jcalendar-input');
        // Value
        el.value = obj.setLabel(obj.getValue(), obj.options.format);
    }

    // Keep object available from the node
    el.calendar = obj;

    if (obj.options.opened == true) {
        obj.open();
    }

    return obj;
});

jSuites.calendar.prettify = function(d, texts) {
    if (! texts) {
        var texts = {
            justNow: 'Just now',
            xMinutesAgo: '{0}m ago',
            xHoursAgo: '{0}h ago',
            xDaysAgo: '{0}d ago',
            xWeeksAgo: '{0}w ago',
            xMonthsAgo: '{0} mon ago',
            xYearsAgo: '{0}y ago',
        }
    }

    var d1 = new Date();
    var d2 = new Date(d);
    var total = parseInt((d1 - d2) / 1000 / 60);

    String.prototype.format = function(o) {
        return this.replace('{0}', o);
    }

    if (total == 0) {
        var text = texts.justNow;
    } else if (total < 90) {
        var text = texts.xMinutesAgo.format(total);
    } else if (total < 1440) { // One day
        var text = texts.xHoursAgo.format(Math.round(total/60));
    } else if (total < 20160) { // 14 days
        var text = texts.xDaysAgo.format(Math.round(total / 1440));
    } else if (total < 43200) { // 30 days
        var text = texts.xWeeksAgo.format(Math.round(total / 10080));
    } else if (total < 1036800) { // 24 months
        var text = texts.xMonthsAgo.format(Math.round(total / 43200));
    } else { // 24 months+
        var text = texts.xYearsAgo.format(Math.round(total / 525600));
    }

    return text;
}

jSuites.calendar.prettifyAll = function() {
    var elements = document.querySelectorAll('.prettydate');
    for (var i = 0; i < elements.length; i++) {
        if (elements[i].getAttribute('data-date')) {
            elements[i].innerHTML = jSuites.calendar.prettify(elements[i].getAttribute('data-date'));
        } else {
            elements[i].setAttribute('data-date', elements[i].innerHTML);
            elements[i].innerHTML = jSuites.calendar.prettify(elements[i].innerHTML);
        }
    }
}

jSuites.calendar.now = function(date, dateOnly) {
    if (! date) {
        var date = new Date();
    }
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    var h = date.getHours();
    var i = date.getMinutes();
    var s = date.getSeconds();

    // Two digits
    var two = function(value) {
        value = '' + value;
        if (value.length == 1) {
            value = '0' + value;
        }
        return value;
    }

    if (dateOnly == true) {
        return two(y) + '-' + two(m) + '-' + two(d);
    } else {
        return two(y) + '-' + two(m) + '-' + two(d) + ' ' + two(h) + ':' + two(i) + ':' + two(s);
    }
}

// Helper to extract date from a string
jSuites.calendar.extractDateFromString = function(date, format) {
    var v1 = '' + date;
    var v2 = format.replace(/[0-9]/g,'');

    var test = 1;

    // Get year
    var y = v2.search("YYYY");
    y = v1.substr(y,4);
    if (parseInt(y) != y) {
        test = 0;
    }

    // Get month
    var m = v2.search("MM");
    m = v1.substr(m,2);
    if (parseInt(m) != m || d > 12) {
        test = 0;
    }

    // Get day
    var d = v2.search("DD");
    d = v1.substr(d,2);
    if (parseInt(d) != d  || d > 31) {
        test = 0;
    }

    // Get hour
    var h = v2.search("HH");
    if (h >= 0) {
        h = v1.substr(h,2);
        if (! parseInt(h) || h > 23) {
            h = '00';
        }
    } else {
        h = '00';
    }
    
    // Get minutes
    var i = v2.search("MI");
    if (i >= 0) {
        i = v1.substr(i,2);
        if (! parseInt(i) || i > 59) {
            i = '00';
        }
    } else {
        i = '00';
    }

    // Get seconds
    var s = v2.search("SS");
    if (s >= 0) {
        s = v1.substr(s,2);
        if (! parseInt(s) || s > 59) {
            s = '00';
        }
    } else {
        s = '00';
    }

    if (test == 1 && date.length == v2.length) {
        // Update source
        var data = y + '-' + m + '-' + d + ' ' + h + ':' +  i + ':' + s;

        return data;
    }

    return '';
}

// Helper to convert date into string
jSuites.calendar.getDateString = function(value, format) {
    // Default calendar
    if (! format) {
        var format = 'DD/MM/YYYY';
    }

    if (value) {
        var d = ''+value;
        d = d.split(' ');

        var h = '';
        var m = '';
        var s = '';

        if (d[1]) {
            h = d[1].split(':');
            m = h[1] ? h[1] : '00';
            s = h[2] ? h[2] : '00';
            h = h[0] ? h[0] : '00';
        } else {
            h = '00';
            m = '00';
            s = '00';
        }

        d = d[0].split('-');

        if (d[0] && d[1] && d[2] && d[0] > 0 && d[1] > 0 && d[1] < 13 && d[2] > 0 && d[2] < 32) {
            var calendar = new Date(d[0], d[1]-1, d[2]);
            var weekday = new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
            var months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

            d[1] = (d[1].length < 2 ? '0' : '') + d[1];
            d[2] = (d[2].length < 2 ? '0' : '') + d[2];
            h = (h.length < 2 ? '0' : '') + h;
            m = (m.length < 2 ? '0' : '') + m;
            s = (s.length < 2 ? '0' : '') + s;

            value = format;
            value = value.replace('WD', weekday[calendar.getDay()]);
            value = value.replace('DD', d[2]);
            value = value.replace('MM', d[1]);
            value = value.replace('YYYY', d[0]);
            value = value.replace('YY', d[0].substring(2,4));
            value = value.replace('MON', months[parseInt(d[1])-1].toUpperCase());

            if (h) {
                value = value.replace('HH24', h);
            }

            if (h > 12) {
                value = value.replace('HH12', h - 12);
                value = value.replace('HH', h);
            } else {
                value = value.replace('HH12', h);
                value = value.replace('HH', h);
            }

            value = value.replace('MI', m);
            value = value.replace('MM', m);
            value = value.replace('SS', s);
        } else {
            value = '';
        }
    }

    return value;
}

jSuites.calendar.isOpen = function(e) {
    if (jSuites.calendar.current) {
        if (! e.target.className || e.target.className.indexOf('jcalendar') == -1) {
            jSuites.calendar.current.close(false, false);
        }
    }
}


jSuites.color = (function(el, options) {
    var obj = {};
    obj.options = {};
    obj.values = [];

    // Global container
    if (! jSuites.color.current) {
        jSuites.color.current = null;
    }

    /**
     * @typedef {Object} defaults
     * @property {(string|Array)} value - Initial value of the compontent
     * @property {string} placeholder - The default instruction text on the element
     * @property {requestCallback} onchange - Method to be execute after any changes on the element
     * @property {requestCallback} onclose - Method to be execute when the element is closed
     */
    var defaults = {
        placeholder: '',
        value: null,
        onclose: null,
        onchange: null,
        closeOnChange: true,
        palette: null,
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    if (! obj.options.palette) {
        // Default pallete
        obj.options.palette = [
            [ "#ffebee", "#fce4ec", "#f3e5f5", "#e8eaf6", "#e3f2fd", "#e0f7fa", "#e0f2f1", "#e8f5e9", "#f1f8e9", "#f9fbe7", "#fffde7", "#fff8e1", "#fff3e0", "#fbe9e7", "#efebe9", "#fafafa", "#eceff1" ],
            [ "#ffcdd2", "#f8bbd0", "#e1bee7", "#c5cae9", "#bbdefb", "#b2ebf2", "#b2dfdb", "#c8e6c9", "#dcedc8", "#f0f4c3", "#fff9c4", "#ffecb3", "#ffe0b2", "#ffccbc", "#d7ccc8", "#f5f5f5", "#cfd8dc" ],
            [ "#ef9a9a", "#f48fb1", "#ce93d8", "#9fa8da", "#90caf9", "#80deea", "#80cbc4", "#a5d6a7", "#c5e1a5", "#e6ee9c", "#fff59d", "#ffe082", "#ffcc80", "#ffab91", "#bcaaa4", "#eeeeee", "#b0bec5" ],
            [ "#e57373", "#f06292", "#ba68c8", "#7986cb", "#64b5f6", "#4dd0e1", "#4db6ac", "#81c784", "#aed581", "#dce775", "#fff176", "#ffd54f", "#ffb74d", "#ff8a65", "#a1887f", "#e0e0e0", "#90a4ae" ],
            [ "#ef5350", "#ec407a", "#ab47bc", "#5c6bc0", "#42a5f5", "#26c6da", "#26a69a", "#66bb6a", "#9ccc65", "#d4e157", "#ffee58", "#ffca28", "#ffa726", "#ff7043", "#8d6e63", "#bdbdbd", "#78909c" ],
            [ "#f44336", "#e91e63", "#9c27b0", "#3f51b5", "#2196f3", "#00bcd4", "#009688", "#4caf50", "#8bc34a", "#cddc39", "#ffeb3b", "#ffc107", "#ff9800", "#ff5722", "#795548", "#9e9e9e", "#607d8b" ],
            [ "#e53935", "#d81b60", "#8e24aa", "#3949ab", "#1e88e5", "#00acc1", "#00897b", "#43a047", "#7cb342", "#c0ca33", "#fdd835", "#ffb300", "#fb8c00", "#f4511e", "#6d4c41", "#757575", "#546e7a" ],
            [ "#d32f2f", "#c2185b", "#7b1fa2", "#303f9f", "#1976d2", "#0097a7", "#00796b", "#388e3c", "#689f38", "#afb42b", "#fbc02d", "#ffa000", "#f57c00", "#e64a19", "#5d4037", "#616161", "#455a64" ],
            [ "#c62828", "#ad1457", "#6a1b9a", "#283593", "#1565c0", "#00838f", "#00695c", "#2e7d32", "#558b2f", "#9e9d24", "#f9a825", "#ff8f00", "#ef6c00", "#d84315", "#4e342e", "#424242", "#37474f" ],
            [ "#b71c1c", "#880e4f", "#4a148c", "#1a237e", "#0d47a1", "#006064", "#004d40", "#1b5e20", "#33691e", "#827717", "#f57f17", "#ff6f00", "#e65100", "#bf360c", "#3e2723", "#212121", "#263238" ],
        ];
    }

    // Value
    if (obj.options.value) {
        el.value = obj.options.value;
    }

    // Table container
    var container = document.createElement('div');
    container.className = 'jcolor';

    // Table container
    var backdrop = document.createElement('div');
    backdrop.className = 'jcolor-backdrop';
    container.appendChild(backdrop);

    // Content
    var content = document.createElement('div');
    content.className = 'jcolor-content';

    // Close button
    var closeButton  = document.createElement('div');
    closeButton.className = 'jcolor-close';
    closeButton.innerHTML = 'Done';
    closeButton.onclick = function() {
        obj.close();
    }
    content.appendChild(closeButton);

    // Table pallete
    var table = document.createElement('table');
    table.setAttribute('cellpadding', '7');
    table.setAttribute('cellspacing', '0');

    for (var j = 0; j < obj.options.palette.length; j++) {
        var tr = document.createElement('tr');
        for (var i = 0; i < obj.options.palette[j].length; i++) {
            var td = document.createElement('td');
            td.style.backgroundColor = obj.options.palette[j][i];
            td.setAttribute('data-value', obj.options.palette[j][i]);
            td.innerHTML = '';
            tr.appendChild(td);

            // Selected color
            if (obj.options.value == obj.options.palette[j][i]) {
                td.classList.add('jcolor-selected');
            }

            // Possible values
            obj.values[obj.options.palette[j][i]] = td;
        }
        table.appendChild(tr);
    }

    /**
     * Open color pallete
     */
    obj.open = function() {
        if (jSuites.color.current) {
            if (jSuites.color.current != obj) {
                jSuites.color.current.close();
            }
        }

        if (! jSuites.color.current) {
            // Persist element
            jSuites.color.current = obj;
            // Show colorpicker
            container.classList.add('jcolor-focus');

            const rectContent = content.getBoundingClientRect();

            if (jSuites.getWindowWidth() < 800) {
                content.style.top = '';
                content.classList.add('jcolor-fullscreen');
                jSuites.animation.slideBottom(content, 1);
                backdrop.style.display = 'block';
            } else {
                if (content.classList.contains('jcolor-fullscreen')) {
                    content.classList.remove('jcolor-fullscreen');
                    backdrop.style.display = '';
                }

                const rect = el.getBoundingClientRect();

                if (window.innerHeight < rect.bottom + rectContent.height) {
                    content.style.top = -1 * (rectContent.height + rect.height + 2) + 'px';
                } else {
                    content.style.top = '2px';
                }
            }

            container.focus();
        }
    }

    /**
     * Close color pallete
     */
    obj.close = function(ignoreEvents) {
        if (jSuites.color.current) {
            jSuites.color.current = null;
            if (! ignoreEvents && typeof(obj.options.onclose) == 'function') {
                obj.options.onclose(el);
            }
            container.classList.remove('jcolor-focus');
        }

        // Make sure backdrop is hidden
        backdrop.style.display = '';

        return obj.options.value;
    }

    /**
     * Set value
     */
    obj.setValue = function(color) {
        if (color) {
            el.value = color;
            obj.options.value = color;
        }

        // Remove current selecded mark
        var selected = container.querySelector('.jcolor-selected');
        if (selected) {
            selected.classList.remove('jcolor-selected');
        }

        // Mark cell as selected
        if (obj.values[color]) {
            obj.values[color].classList.add('jcolor-selected');
        }

        // Onchange
        if (typeof(obj.options.onchange) == 'function') {
            obj.options.onchange(el, color);
        }

        if (obj.options.closeOnChange == true) {
            obj.close();
        }
    }

    /**
     * Get value
     */
    obj.getValue = function() {
        return obj.options.value;
    }

    /**
     * If element is focus open the picker
     */
    el.addEventListener("focus", function(e) {
        obj.open();
    });

    el.addEventListener("mousedown", function(e) {
        if (! jSuites.color.current) {
            setTimeout(function() {
        obj.open();
                e.preventDefault();
            }, 200);
        }
    });

    // Select color
    container.addEventListener("mouseup", function(e) {
        if (e.target.tagName == 'TD') {
            jSuites.color.current.setValue(e.target.getAttribute('data-value'));

            if (jSuites.color.current) {
                jSuites.color.current.close();
            }
        }
    });

    // Close controller
    document.addEventListener("mousedown", function(e) {
        if (jSuites.color.current) {
            var element = jSuites.findElement(e.target, 'jcolor');
            if (! element) {
                jSuites.color.current.close();
            }
        }
    });

    // Possible to focus the container
    container.setAttribute('tabindex', '900');

    // Placeholder
    if (obj.options.placeholder) {
        el.setAttribute('placeholder', obj.options.placeholder);
    }

    // Append to the table
    content.appendChild(table);
    container.appendChild(content);

    // Insert picker after the element
    if (el.tagName == 'INPUT') {
        el.parentNode.insertBefore(container, el.nextSibling);
    } else {
        el.appendChild(container);
    }

    // Keep object available from the node
    el.color = obj;

    return obj;
});


jSuites.contextmenu = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        items: null,
        onclick: null,
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Class definition
    el.classList.add('jcontextmenu');
    // Focusable
    el.setAttribute('tabindex', '900');

    /**
     * Open contextmenu
     */
    obj.open = function(e, items) {
        if (items) {
            // Update content
            obj.options.items = items;
            // Create items
            obj.create(items);
        }

        // Coordinates
        if ((obj.options.items && obj.options.items.length > 0) || el.children.length) {
            if (e.target) {
                var x = e.clientX;
                var y = e.clientY;
            } else {
                var x = e.x;
                var y = e.y;
            }

            el.classList.add('jcontextmenu-focus');
            el.focus();

            const rect = el.getBoundingClientRect();

            if (window.innerHeight < y + rect.height) {
                el.style.top = (y - rect.height) + 'px';
            } else {
                el.style.top = y + 'px';
            }

            if (window.innerWidth < x + rect.width) {
                if (x - rect.width > 0) {
                    el.style.left = (x - rect.width) + 'px';
                } else {
                    el.style.left = '10px';
                }
            } else {
                el.style.left = x + 'px';
            }
        }
    }

    /**
     * Close menu
     */
    obj.close = function() {
        if (el.classList.contains('jcontextmenu-focus')) {
            el.classList.remove('jcontextmenu-focus');
        }
    }

    /**
     * Create items based on the declared objectd
     * @param {object} items - List of object
     */
    obj.create = function(items) {
        // Update content
        el.innerHTML = '';

        // Append items
        for (var i = 0; i < items.length; i++) {
            if (items[i].type && items[i].type == 'line') {
                var itemContainer = document.createElement('hr');
            } else {
                var itemContainer = document.createElement('div');
                var itemText = document.createElement('a');
                itemText.innerHTML = items[i].title;

                if (items[i].disabled) {
                    itemContainer.className = 'jcontextmenu-disabled';
                } else if (items[i].onclick) {
                    itemContainer.method = items[i].onclick;
                    itemContainer.addEventListener("mouseup", function() {
                        // Execute method
                        this.method(this);
                    });
                }
                itemContainer.appendChild(itemText);

                if (items[i].shortcut) {
                    var itemShortCut = document.createElement('span');
                    itemShortCut.innerHTML = items[i].shortcut;
                    itemContainer.appendChild(itemShortCut);
                }
            }

            el.appendChild(itemContainer);
        }
    }

    if (typeof(obj.options.onclick) == 'function') {
        el.addEventListener('click', function(e) {
            obj.options.onclick(obj);
        });
    }

    // Create items
    if (obj.options.items) {
        obj.create(obj.options.items);
    }

    el.addEventListener('blur', function(e) {
        setTimeout(function() {
            obj.close();
        }, 120);
    });

    if (! jSuites.contextmenu.hasEvents) {
        window.addEventListener("mousewheel", function() {
            obj.close();
        });

        document.addEventListener("contextmenu", function(e) {
            var id = jSuites.contextmenu.getElement(e.target);
            if (id) {
                var element = document.querySelector('#' + id);
                if (! element) {
                    console.error('JSUITES: Contextmenu id not found');
                } else {
                    element.contextmenu.open(e);
                    e.preventDefault();
                }
            }
        });

        jSuites.contextmenu.hasEvents = true;
    }

    el.contextmenu = obj;

    return obj;
});

jSuites.contextmenu.getElement = function(element) {
    var foundId = 0;

    function path (element) {
        if (element.parentNode && element.getAttribute('aria-contextmenu-id')) {
            foundId = element.getAttribute('aria-contextmenu-id')
        } else {
            if (element.parentNode) {
                path(element.parentNode);
            }
        }
    }

    path(element);

    return foundId;
}

/**
 * Dialog v1.0.1
 * Author: paul.hodel@gmail.com
 * https://github.com/paulhodel/jtools
 */
 
jSuites.dialog = (function() {
    var obj = {};
    obj.options = {};

    var dialog = null;
    var dialogTitle = null;
    var dialogHeader = null;
    var dialogMessage = null;
    var dialogFooter = null;
    var dialogContainer = null;
    var dialogConfirm = null;
    var dialogConfirmButton = null;
    var dialogCancel = null;
    var dialogCancelButton = null;

    obj.open = function(options) {
        if (! jSuites.dialog.hasEvents) {
            obj.init();

            jSuites.dialog.hasEvents = true;
        }
        obj.options = options;

        if (obj.options.title) {
            dialogTitle.innerHTML = obj.options.title;
        }

        if (obj.options.message) {
            dialogMessage.innerHTML = obj.options.message;
        }

        if (! obj.options.confirmLabel) {
            obj.options.confirmLabel = 'OK';
        }
        dialogConfirmButton.value = obj.options.confirmLabel;

        if (! obj.options.cancelLabel) {
            obj.options.cancelLabel = 'Cancel';
        }
        dialogCancelButton.value = obj.options.cancelLabel;

        if (obj.options.type == 'confirm') {
            dialogCancelButton.parentNode.style.display = '';
        } else {
            dialogCancelButton.parentNode.style.display = 'none';
        }

        // Append element to the app
        dialog.style.opacity = 100;

        // Append to the page
        if (jSuites.el) {
            jSuites.el.appendChild(dialog);
        } else {
            document.body.appendChild(dialog);
        }

        // Focus
        dialog.focus();

        // Show
        setTimeout(function() {
            dialogContainer.style.opacity = 100;
        }, 0);
    }

    obj.close = function() {
        dialog.style.opacity = 0;
        dialogContainer.style.opacity = 0;
        setTimeout(function() {
            dialog.remove();
        }, 100);
    }

    obj.init = function() {
        dialog = document.createElement('div');
        dialog.setAttribute('tabindex', '901');
        dialog.className = 'jdialog';
        dialog.id = 'dialog';

        dialogHeader = document.createElement('div');
        dialogHeader.className = 'jdialog-header';

        dialogTitle = document.createElement('div');
        dialogTitle.className = 'jdialog-title';
        dialogHeader.appendChild(dialogTitle);

        dialogMessage = document.createElement('div');
        dialogMessage.className = 'jdialog-message';
        dialogHeader.appendChild(dialogMessage);

        dialogFooter = document.createElement('div');
        dialogFooter.className = 'jdialog-footer';

        dialogContainer = document.createElement('div');
        dialogContainer.className = 'jdialog-container';
        dialogContainer.appendChild(dialogHeader);
        dialogContainer.appendChild(dialogFooter);

        // Confirm
        dialogConfirm = document.createElement('div');
        dialogConfirmButton = document.createElement('input');
        dialogConfirmButton.value = obj.options.confirmLabel;
        dialogConfirmButton.type = 'button';
        dialogConfirmButton.onclick = function() {
            if (typeof(obj.options.onconfirm) == 'function') {
                obj.options.onconfirm();
            }
            obj.close();
        };
        dialogConfirm.appendChild(dialogConfirmButton);
        dialogFooter.appendChild(dialogConfirm);

        // Cancel
        dialogCancel = document.createElement('div');
        dialogCancelButton = document.createElement('input');
        dialogCancelButton.value = obj.options.cancelLabel;
        dialogCancelButton.type = 'button';
        dialogCancelButton.onclick = function() {
            if (typeof(obj.options.oncancel) == 'function') {
                obj.options.oncancel();
            }
            obj.close();
        }
        dialogCancel.appendChild(dialogCancelButton);
        dialogFooter.appendChild(dialogCancel);

        // Dialog
        dialog.appendChild(dialogContainer);

        document.addEventListener('keydown', function(e) {
            if (e.which == 13) {
                if (typeof(obj.options.onconfirm) == 'function') {
                    jSuites.dialog.options.onconfirm();
                }
                obj.close();
            } else if (e.which == 27) {
                obj.close();
            }
        });
    }

    return obj;
})();

jSuites.confirm = (function(message, onconfirm) {
    if (jSuites.getWindowWidth() < 800) {
        jSuites.dialog.open({
            type: 'confirm',
            message: message,
            title: 'Confirmation',
            onconfirm: onconfirm,
        });
    } else {
        if (confirm(message)) {
            onconfirm();
        }
    }
});

jSuites.alert = function(message) {
    if (jSuites.getWindowWidth() < 800) {
        jSuites.dialog.open({
            title:'Alert',
            message:message,
        });
    } else {
        alert(message);
    }
}



jSuites.dropdown = (function(el, options) {
    var obj = {};
    obj.options = {};

    // If the element is a SELECT tag, create a configuration object
    if (el.tagName == 'SELECT') {
        var ret = jSuites.dropdown.extractFromDom(el, options);
        el = ret.el;
        options = ret.options;
    }

    // Default configuration
    var defaults = {
        url: null,
        data: [],
        multiple: false,
        autocomplete: false,
        type: null,
        width: null,
        maxWidth: null,
        opened: false,
        value: null,
        placeholder: '',
        position: false,
        onchange: null,
        onload: null,
        onopen: null,
        onclose: null,
        onfocus: null,
        onblur: null,
        allowNewOptions: true,
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Global container
    if (! jSuites.dropdown.current) {
        jSuites.dropdown.current = null;
    }

    // Containers
    obj.items = [];
    obj.groups = [];
    obj.selected = [];

    // Search options
    obj.search = '';
    obj.results = null;
    obj.numOfItems = 0;

    // Create dropdown
    el.classList.add('jdropdown');
 
    if (obj.options.type == 'searchbar') {
        el.classList.add('jdropdown-searchbar');
    } else if (obj.options.type == 'list') {
        el.classList.add('jdropdown-list');
    } else if (obj.options.type == 'picker') {
        el.classList.add('jdropdown-picker');
    } else {
        if (jSuites.getWindowWidth() < 800) {
            el.classList.add('jdropdown-picker');
            obj.options.type = 'picker';
        } else {
            if (obj.options.width) {
                el.style.width = obj.options.width;
                el.style.minWidth = obj.options.width;
            }
            el.classList.add('jdropdown-default');
            obj.options.type = 'default';
        }
    }

    // Header container
    var containerHeader = document.createElement('div');
    containerHeader.className = 'jdropdown-container-header';

    // Header
    var header = document.createElement('input');
    header.className = 'jdropdown-header';
    if (typeof(obj.options.onfocus) == 'function') {
        header.onfocus = function() {
            obj.options.onfocus(el);
        }
    }
    if (typeof(obj.options.onblur) == 'function') {
        header.onblur = function() {
            obj.options.onblur(el);
        }
    }

    // Container
    var container = document.createElement('div');
    container.className = 'jdropdown-container';

    // Dropdown content
    var content = document.createElement('div');
    content.className = 'jdropdown-content';

    // New items
    var newOptions = document.createElement('div');
    newOptions.className = 'jdropdown-create-option';
    newOptions.innerHTML = 'New option';
    //container.appendChild(newOptions);

    // Close button
    var closeButton  = document.createElement('div');
    closeButton.className = 'jdropdown-close';
    closeButton.innerHTML = 'Done';

    // Create backdrop
    var backdrop  = document.createElement('div');
    backdrop.className = 'jdropdown-backdrop';

    // Autocomplete
    if (obj.options.autocomplete == true) {
        el.setAttribute('data-autocomplete', true);

        // Handler
        var keyTimer = null;
        header.addEventListener('keyup', function(e) {
            if (keyTimer) {
                clearTimeout(keyTimer);
            }
            keyTimer = setTimeout(function() {
                obj.find(header.value);
                keyTimer = null;
            }, 500);

            if (! el.classList.contains('jdropdown-focus')) {
                if (e.which > 65) {
                    obj.open();
                }
            }
        });
    } else {
        header.setAttribute('readonly', 'readonly');
    }

    // Place holder
    if (! obj.options.placeholder && el.getAttribute('placeholder')) {
        obj.options.placeholder = el.getAttribute('placeholder');
    }

    if (obj.options.placeholder) {
        header.setAttribute('placeholder', obj.options.placeholder);
    }

    // Append elements
    containerHeader.appendChild(header);
    if (obj.options.type == 'searchbar') {
        containerHeader.appendChild(closeButton);
    } else {
        container.appendChild(closeButton);
    }
    container.appendChild(content);
    el.appendChild(containerHeader);
    el.appendChild(container);
    el.appendChild(backdrop);

    /**
     * Init dropdown
     */
    obj.init = function() {
        if (obj.options.url) {
            jSuites.ajax({
                url: obj.options.url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        // Set data
                        obj.setData(data);
                        // Set value
                        if (obj.options.value != null) {
                            obj.setValue(obj.options.value);
                        }
                        // Onload method
                        if (typeof(obj.options.onload) == 'function') {
                            obj.options.onload(el, obj, data);
                        }
                    }
                }
            });
        } else {
            // Set data
            obj.setData();
            // Set value
            if (obj.options.value != null) {
                obj.setValue(obj.options.value);
            }
            // Onload
            if (typeof(obj.options.onload) == 'function') {
                obj.options.onload(el, obj, data);
            }
        }

        // Open dropdown
        if (obj.options.opened == true) {
            obj.open();
        }
    }

    obj.getUrl = function() {
        return obj.options.url;
    }

    obj.setUrl = function(url) {
        obj.options.url = url;

        jSuites.ajax({
            url: obj.options.url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                obj.setData(data);
            }
        });
    }

    /**
     * Create a new item
     */
    obj.createItem = function(data, group) {
        var text = data.text || '';
        if (! text && data.name) {
            text = data.name;
        }
        var value = data.value || '';
        if (! value && data.id) {
            value = data.id;
        }
        // Create item
        var item = {};
        item.element = document.createElement('div');
        item.element.className = 'jdropdown-item';
        item.element.indexValue = obj.items.length;
        item.value = value;
        item.text = text;
        item.textLowerCase = item.text.toLowerCase();

        // Image
        if (data.image) {
            var image = document.createElement('img');
            image.className = 'jdropdown-image';
            image.src = data.image;
            if (! data.title) {
               image.classList.add('jdropdown-image-small');
            }
            item.element.appendChild(image);
        }

        // Set content
        var node = document.createElement('div');
        node.className = 'jdropdown-description';
        node.innerHTML = text;

        // Title
        if (data.title) {
            var title = document.createElement('div');
            title.className = 'jdropdown-title';
            title.innerHTML = data.title;
            node.appendChild(title);
        }

        // Group reference
        if (group) {
            item.group = group;
        }

        // Keep DOM accessible
        obj.items.push(item);

        // Add node to item
        item.element.appendChild(node);

        return item;
    }

    obj.setData = function(data) {
        // Update data
        if (data) {
            obj.options.data = data;
        }

        // Data
        var data = obj.options.data;

        // Remove content from the DOM
        container.removeChild(content);

        // Make sure the content container is blank
        content.innerHTML = '';

        // Reset
        obj.reset();

        // Reset items
        obj.items = [];

        // Helpers
        var items = [];
        var groups = [];

        // Create elements
        if (data.length) {
            // Prepare data
            for (var i = 0; i < data.length; i++) {
                // Compatibility
                if (typeof(data[i]) != 'object') {
                    // Correct format
                    obj.options.data[i] = data[i] = { id: data[i], name: data[i] };
                }

                // Process groups
                if (data[i].group) {
                    if (! groups[data[i].group]) {
                        groups[data[i].group] = [];
                    }
                    groups[data[i].group].push(i);
                } else {
                    items.push(i);
                }
            }

            // Groups
            var groupNames = Object.keys(groups);

            // Append groups in case exists
            if (groupNames.length > 0) {
                for (var i = 0; i < groupNames.length; i++) {
                    // Group container
                    var group = document.createElement('div');
                    group.className = 'jdropdown-group';
                    // Group name
                    var groupName = document.createElement('div');
                    groupName.className = 'jdropdown-group-name';
                    groupName.innerHTML = groupNames[i];
                    // Group arrow
                    var groupArrow = document.createElement('i');
                    groupArrow.className = 'jdropdown-group-arrow jdropdown-group-arrow-down';
                    groupName.appendChild(groupArrow);
                    // Group items
                    var groupContent = document.createElement('div');
                    groupContent.className = 'jdropdown-group-items';
                    for (var j = 0; j < groups[groupNames[i]].length; j++) {
                        var item = obj.createItem(data[groups[groupNames[i]][j]], group);

                        if (obj.numOfItems < 200) {
                            groupContent.appendChild(item.element);
                            obj.numOfItems++;
                        }
                    }
                    // Group itens
                    group.appendChild(groupName);
                    group.appendChild(groupArrow);
                    group.appendChild(groupContent);
                    // Keep group DOM
                    obj.groups.push(group);
                    // Only add to the screen if children on the group
                    if (groupContent.children.length > 0) {
                        // Add DOM to the content
                        content.appendChild(group);
                    }
                }
            }

            if (items.length) {
                for (var i = 0; i < items.length; i++) {
                    var item = obj.createItem(data[items[i]]);
                    if (obj.numOfItems < 200) {
                        content.appendChild(item.element);
                        obj.numOfItems++;
                    }
                }
            }
        }

        // Re-insert the content to the container
        container.appendChild(content);
    }

    obj.getText = function(asArray) {
        // Result
        var result = [];
        // Append options
        for (var i = 0; i < obj.selected.length; i++) {
            if (obj.items[obj.selected[i]]) {
                result.push(obj.items[obj.selected[i]].text);
            }
        }

        if (asArray) {
            return result;
        } else {
            return result.join('; ');
        }
    }

    obj.getValue = function(asArray) {
        // Result
        var result = [];
        // Append options
        for (var i = 0; i < obj.selected.length; i++) {
            if (obj.items[obj.selected[i]]) {
                result.push(obj.items[obj.selected[i]].value);
            }
        }

        if (asArray) {
            return result;
        } else {
            return result.join(';');
        }
    }

    obj.setValue = function(value) {
        // Remove values
        for (var i = 0; i < obj.selected.length; i++) {
            obj.items[obj.selected[i]].element.classList.remove('jdropdown-selected')
        } 

        // Reset selected
        obj.selected = [];

        // Set values
        if (value != null) {
            if (Array.isArray(value)) {
                for (var i = 0; i < obj.items.length; i++) {
                    for (var j = 0; j < value.length; j++) {
                        if (obj.items[i].value == value[j]) {
                            // Keep index of the selected item
                            obj.selected.push(i);
                            // Visual selection
                            obj.items[i].element.classList.add('jdropdown-selected');
                        }
                    }
                }
            } else {
                for (var i = 0; i < obj.items.length; i++) {
                    if (obj.items[i].value == value) {
                        // Keep index of the selected item
                        obj.selected.push(i);
                        // Visual selection
                        obj.items[i].element.classList.add('jdropdown-selected');
                    }
                }
            }
        }

        // Update labels
        obj.updateLabel();
    }

    obj.selectIndex = function(index) {
        // Only select those existing elements
        if (obj.items && obj.items[index]) {
            var index = index = parseInt(index);
            // Current selection
            var oldValue = obj.getValue();
            var oldLabel = obj.getText();

            // Remove cursor style
            if (obj.currentIndex != null) {
                obj.items[obj.currentIndex].element.classList.remove('jdropdown-cursor');
            }
            // Set cursor style
            obj.items[index].element.classList.add('jdropdown-cursor');

            // Update cursor position
            obj.currentIndex = index;

            // Focus behaviour
            if (! obj.options.multiple) {
                // Unselect option
                if (obj.items[index].element.classList.contains('jdropdown-selected')) {
                    // Reset selected
                    obj.resetSelected();
                } else {
                    // Reset selected
                    obj.resetSelected();
                    // Update selected item
                    obj.items[index].element.classList.add('jdropdown-selected');
                    // Add to the selected list
                    obj.selected.push(index);
                    // Close
                    obj.close();
                }
            } else {
                // Toggle option
                if (obj.items[index].element.classList.contains('jdropdown-selected')) {
                    obj.items[index].element.classList.remove('jdropdown-selected');
                    // Remove from selected list
                    var indexToRemove = obj.selected.indexOf(index);
                    // Remove select
                    obj.selected.splice(indexToRemove, 1);
                } else {
                    // Select element
                    obj.items[index].element.classList.add('jdropdown-selected');
                    // Add to the selected list
                    obj.selected.push(index);
                }

                // Update labels for multiple dropdown
                if (! obj.options.autocomplete) {
                    obj.updateLabel();
                }
            }

            // Current selection
            var newValue = obj.getValue();
            var newLabel = obj.getText();

            // Events
            if (typeof(obj.options.onchange) == 'function') {
                obj.options.onchange(el, index, oldValue, newValue, oldLabel, newLabel);
            }
        }
    }

    obj.selectItem = function(item) {
        if (jSuites.dropdown.current) {
            obj.selectIndex(item.indexValue);
        }
    }

    obj.find = function(str) {
        // Search term
        obj.search = str;

        // Force lowercase
        var str = str ? str.toLowerCase() : '';

        // Results
        obj.results = [];
        obj.numOfItems = 0;

        // Append options
        for (var i = 0; i < obj.items.length; i++) {
            if (str == null || obj.items[i].textLowerCase.indexOf(str) != -1 || obj.selected.indexOf(i) > -1) {
                obj.results.push(obj.items[i]);

                if (obj.items[i].group && obj.items[i].group.children[2].children[0]) {
                    // Remove all nodes
                    while (obj.items[i].group.children[2].children[0]) {
                        obj.items[i].group.children[2].removeChild(obj.items[i].group.children[2].children[0]);
                    }
                }
            }
        }

        // Remove all nodes
        while (content.children[0]) {
            content.removeChild(content.children[0]);
        }

        // Show 200 items at once
        var number = obj.results.length || 0;
        if (number > 200) {
            number = 200;
        }

        for (var i = 0; i < number; i++) {
            if (obj.results[i].group) {
                if (! obj.results[i].group.parentNode) {
                    content.appendChild(obj.results[i].group);
                }
                obj.results[i].group.children[2].appendChild(obj.results[i].element);
            } else {
                content.appendChild(obj.results[i].element);
            }
            obj.numOfItems++;
        }
    }

    obj.updateLabel = function() {
        // Update label
        header.value = obj.getText();
    }

    obj.open = function() {
        if (jSuites.dropdown.current != el) {
            if (jSuites.dropdown.current) {
                jSuites.dropdown.current.dropdown.close();
            }
            jSuites.dropdown.current = el;
        }

        // Focus
        if (! el.classList.contains('jdropdown-focus')) {
            // Add focus
            el.classList.add('jdropdown-focus');

            // Animation
            if (jSuites.getWindowWidth() < 800) {
                if (obj.options.type == null || obj.options.type == 'picker') {
                    jSuites.animation.slideBottom(container, 1);
                }
            }

            // Filter
            if (obj.options.autocomplete == true) {
                header.value = obj.search;
                header.focus();
            }

            // Set cursor for the first or first selected element
            var cursor = (obj.selected && obj.selected[0]) ? obj.selected[0] : 0;
            if (cursor) {
                obj.updateCursor(cursor);
            }

            // Container Size
            if (! obj.options.type || obj.options.type == 'default') {
                const rect = el.getBoundingClientRect();
                const rectContainer = container.getBoundingClientRect();

                if (obj.options.position) {
                    container.style.position = 'fixed';
                    if (window.innerHeight < rect.bottom + rectContainer.height) {
                        container.style.top = '';
                        container.style.bottom = (window.innerHeight - rect.top ) + 1 + 'px';
                    } else {
                        container.style.top = rect.bottom + 'px';
                        container.style.bottom = '';
                    }
                    container.style.left = rect.left + 'px';
                } else {
                    if (window.innerHeight < rect.bottom + rectContainer.height) {
                        container.style.top = '';
                        container.style.bottom = rect.height + 1 + 'px';
                    } else {
                        container.style.top = '';
                        container.style.bottom = '';
                    }
                }

                container.style.minWidth = rect.width + 'px';

                if (obj.options.maxWidth) {
                    container.style.maxWidth = obj.options.maxWidth;
                }
            }
        }

        // Events
        if (typeof(obj.options.onopen) == 'function') {
            obj.options.onopen(el);
        }
    }

    obj.close = function(ignoreEvents) {
        if (jSuites.dropdown.current) {
            // Remove controller
            jSuites.dropdown.current = null
            // Remove cursor
            obj.resetCursor();
            // Update labels
            obj.updateLabel();
            // Events
            if (! ignoreEvents && typeof(obj.options.onclose) == 'function') {
                obj.options.onclose(el);
            }
            // Blur
            if (header.blur) {
                header.blur();
            }
            // Remove focus
            el.classList.remove('jdropdown-focus');
        }

        return obj.getValue();
    }

    /**
     * Update position cursor
     */
    obj.updateCursor = function(index) {
        // Set new cursor
        if (obj.items && obj.items[index] && obj.items[index].element) {
            // Reset cursor
            obj.resetCursor();

            // Set new cursor
            obj.items[index].element.classList.add('jdropdown-cursor');

            // Update position
            obj.currentIndex = parseInt(index);
    
            // Update scroll to the cursor element
            var container = content.scrollTop;
            var element = obj.items[obj.currentIndex].element;
            content.scrollTop = element.offsetTop - element.scrollTop + element.clientTop - 95;
        }
    }

    /**
     * Reset cursor
     */
    obj.resetCursor = function() {
        // Remove current cursor
        if (obj.currentIndex != null) {
            // Remove visual cursor
            if (obj.items && obj.items[obj.currentIndex]) {
                obj.items[obj.currentIndex].element.classList.remove('jdropdown-cursor');
            }
            // Reset cursor
            obj.currentIndex = null;
        }
    }

    /**
     * Reset cursor
     */
    obj.resetSelected = function() {
        // Unselected all
        if (obj.selected) {
            // Remove visual selection
            for (var i = 0; i < obj.selected.length; i++) {
                if (obj.items[obj.selected[i]]) {
                    obj.items[obj.selected[i]].element.classList.remove('jdropdown-selected');
                }
            }
            // Reset current selected items
            obj.selected = [];
        }
    }

    /**
     * Reset cursor and selected items
     */
    obj.reset = function() {
        // Reset cursor
        obj.resetCursor();

        // Reset selected
        obj.resetSelected();

        // Update labels
        obj.updateLabel();
    }

    /**
     * First visible item
     */
    obj.firstVisible = function() {
        var newIndex = null;
        for (var i = 0; i < obj.items.length; i++) {
            if (obj.items[i].element.style.display != 'none') {
                newIndex = i;
                break;
            }
        }

        if (newIndex == null) {
            return false;
        }

        obj.updateCursor(newIndex);
    }

    /**
     * Navigation
     */
    obj.first = function() {
        var newIndex = null;
        for (var i = obj.currentIndex - 1; i >= 0; i--) {
            if (obj.items[i].element.style.display != 'none') {
                newIndex = i;
            }
        }

        if (newIndex == null) {
            return false;
        }

        obj.updateCursor(newIndex);
    }

    obj.last = function() {
        var newIndex = null;
        for (var i = obj.currentIndex + 1; i < obj.items.length; i++) {
            if (obj.items[i].element.style.display != 'none') {
                newIndex = i;
            }
        }

        if (newIndex == null) {
            return false;
        }

        obj.updateCursor(newIndex);
    }

    obj.next = function() {
        var newIndex = null;
        for (var i = obj.currentIndex + 1; i < obj.items.length; i++) {
            if (obj.items[i].element.parentNode) {
                newIndex = i;
                break;
            }
        }

        if (newIndex == null) {
            return false;
        }

        obj.updateCursor(newIndex);
    }

    obj.prev = function() {
        var newIndex = null;
        for (var i = obj.currentIndex - 1; i >= 0; i--) {
            if (obj.items[i].element.parentNode) {
                newIndex = i;
                break;
            }
        }

        if (newIndex == null) {
            return false;
        }

        obj.updateCursor(newIndex);
    }

    obj.loadUp = function() {
        return false;
    }

    obj.loadDown = function() {
        var test = false;

        // Search
        if (obj.results) {
            var results = obj.results;
        } else {
            var results = obj.items;
        }

        if (results.length > obj.numOfItems) {
            var numberOfItems = obj.numOfItems;
            var number = results.length - numberOfItems;
            if (number > 200) {
                number = 200;
            }

            for (var i = numberOfItems; i < numberOfItems + number; i++) {
                if (results[i].group) {
                    if (! results[i].group.parentNode) {
                        content.appendChild(results[i].group);
                    }
                    results[i].group.children[2].appendChild(results[i].element);
                } else {
                    content.appendChild(results[i].element);
                }

                obj.numOfItems++;
            }

            // New item added
            test = true;
        }

        return test;
    }

    if (! jSuites.dropdown.hasEvents) {
        if ('ontouchsend' in document.documentElement === true) {
            document.addEventListener('touchsend', jSuites.dropdown.mouseup);
        } else {
            document.addEventListener('mouseup', jSuites.dropdown.mouseup);
        }
        document.addEventListener('keydown', jSuites.dropdown.onkeydown);

        jSuites.dropdown.hasEvents = true;
    }

    // Lazyloading
    jSuites.lazyLoading(content, {
        loadUp: obj.loadUp,
        loadDown: obj.loadDown,
    });

    // Start dropdown
    obj.init();

    // Keep object available from the node
    el.dropdown = obj;

    return obj;
});

jSuites.dropdown.hasEvents = false;

jSuites.dropdown.mouseup = function(e) {
    var element = jSuites.findElement(e.target, 'jdropdown');
    if (element) {
        var dropdown = element.dropdown;
        if (e.target.classList.contains('jdropdown-header')) {
            if (element.classList.contains('jdropdown-focus') && element.classList.contains('jdropdown-default')) {
                if (dropdown.options.autocomplete == false) {
                    dropdown.close();
                } else {
                    var rect = element.getBoundingClientRect();

                    if (e.changedTouches && e.changedTouches[0]) {
                        var x = e.changedTouches[0].clientX;
                        var y = e.changedTouches[0].clientY;
                    } else {
                        var x = e.clientX;
                        var y = e.clientY;
                    }

                    if (rect.width - (x - rect.left) < 30) {
                        dropdown.close();
                    }
                }
            } else {
                dropdown.open();
            }
        } else if (e.target.classList.contains('jdropdown-group-name')) {
            var items = e.target.nextSibling.children;
            if (e.target.nextSibling.style.display != 'none') {
                for (var i = 0; i < items.length; i++) {
                    if (items[i].style.display != 'none') {
                        dropdown.selectItem(items[i]);
                    }
                }
            }
        } else if (e.target.classList.contains('jdropdown-group-arrow')) {
            if (e.target.classList.contains('jdropdown-group-arrow-down')) {
                e.target.classList.remove('jdropdown-group-arrow-down');
                e.target.classList.add('jdropdown-group-arrow-up');
                e.target.parentNode.nextSibling.style.display = 'none';
            } else {
                e.target.classList.remove('jdropdown-group-arrow-up');
                e.target.classList.add('jdropdown-group-arrow-down');
                e.target.parentNode.nextSibling.style.display = '';
            }
        } else if (e.target.classList.contains('jdropdown-item')) {
            dropdown.selectItem(e.target);
        } else if (e.target.classList.contains('jdropdown-image')) {
            dropdown.selectItem(e.target.parentNode);
        } else if (e.target.classList.contains('jdropdown-description')) {
            dropdown.selectItem(e.target.parentNode);
        } else if (e.target.classList.contains('jdropdown-title')) {
            dropdown.selectItem(e.target.parentNode.parentNode);
        } else if (e.target.classList.contains('jdropdown-close') || e.target.classList.contains('jdropdown-backdrop')) {
            dropdown.close();
        }

        e.stopPropagation();
        e.preventDefault();
    } else {
        if (jSuites.dropdown.current) {
            jSuites.dropdown.current.dropdown.close();
        }
    }
}


// Keydown controls
jSuites.dropdown.onkeydown = function(e) {
    if (jSuites.dropdown.current) {
        // Element
        var element = jSuites.dropdown.current.dropdown;
        // Index
        var index = element.currentIndex;

        if (e.shiftKey) {

        } else {
            if (e.which == 13 || e.which == 27 || e.which == 35 || e.which == 36 || e.which == 38 || e.which == 40) {
                // Move cursor
                if (e.which == 13) {
                    element.selectIndex(index)
                } else if (e.which == 38) {
                    if (index == null) {
                        element.firstVisible();
                    } else if (index > 0) {
                        element.prev();
                    }
                } else if (e.which == 40) {
                    if (index == null) {
                        element.firstVisible();
                    } else if (index + 1 < element.options.data.length) {
                        element.next();
                    }
                } else if (e.which == 36) {
                    element.first();
                } else if (e.which == 35) {
                    element.last();
                } else if (e.which == 27) {
                    element.close();
                }

                e.stopPropagation();
                e.preventDefault();
            }
        }
    }
}

jSuites.dropdown.extractFromDom = function(el, options) {
    // Keep reference
    var select = el;
    if (! options) {
        options = {};
    }
    // Prepare configuration
    if (el.getAttribute('multiple') && (! options || options.multiple == undefined)) {
        options.multiple = true;
    }
    if (el.getAttribute('placeholder') && (! options || options.placeholder == undefined)) {
        options.placeholder = el.getAttribute('placeholder');
    }
    if (el.getAttribute('data-autocomplete') && (! options || options.autocomplete == undefined)) {
        options.autocomplete = true;
    }
    if (! options || options.width == undefined) {
        options.width = el.offsetWidth;
    }
    if (el.value && (! options || options.value == undefined)) {
        options.value = el.value;
    }
    if (! options || options.data == undefined) {
        options.data = [];
        for (var j = 0; j < el.children.length; j++) {
            if (el.children[j].tagName == 'OPTGROUP') {
                for (var i = 0; i < el.children[j].children.length; i++) {
                    options.data.push({
                        id: el.children[j].children[i].value,
                        name: el.children[j].children[i].innerHTML,
                        group: el.children[j].getAttribute('label'),
                    });
                }
            } else {
                options.data.push({
                    id: el.children[j].value,
                    name: el.children[j].innerHTML,
                });
            }
        }
    }
    if (! options || options.onchange == undefined) {
        options.onchange = function(a,b,c,d) {
            if (options.multiple == true) {
                if (obj.items[b].classList.contains('jdropdown-selected')) {
                    select.options[b].setAttribute('selected', 'selected');
                } else {
                    select.options[b].removeAttribute('selected');
                }
            } else {
                select.value = d;
            }
        }
    }
    // Create DIV
    var div = document.createElement('div');
    el.parentNode.insertBefore(div, el);
    el.style.display = 'none';
    el = div;

    return { el:el, options:options };
}

/**
 * (c) jTools Text Editor
 * https://github.com/paulhodel/jtools
 *
 * @author: Paul Hodel <paul.hodel@gmail.com>
 * @description: Inline richtext editor
 */

jSuites.editor = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        // Initial HTML content
        value: null,
        // Initial snippet
        snippet: null,
        // Add toolbar
        toolbar: null,
        // Website parser is to read websites and images from cross domain
        remoteParser: null,
        // Parse URL
        parseURL: false,
        // Accept drop files
        dropZone: false,
        dropAsAttachment: false,
        acceptImages: false,
        acceptFiles: false,
        maxFileSize: 5000000, 
        // Style
        border: true,
        padding: true,
        maxHeight: null,
        focus: false,
        // Events
        onclick: null,
        onfocus: null,
        onblur: null,
        onload: null,
        onkeyup: null,
        onkeydown: null,
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Private controllers
    var imageResize = 0;
    var editorTimer = null;
    var editorAction = null;

    // Make sure element is empty
    el.innerHTML = '';

    if (typeof(obj.options.onclick) == 'function') {
        el.onclick = function(e) {
            obj.options.onclick(el, obj, e);
        }
    }

    // Prepare container
    el.classList.add('jeditor-container');

    // Padding
    if (obj.options.padding == true) {
        el.classList.add('jeditor-padding');
    }

    // Border
    if (obj.options.border == false) {
        el.style.border = '0px';
    }

    // Snippet
    var snippet = document.createElement('div');
    snippet.className = 'jsnippet';
    snippet.setAttribute('contenteditable', false);

    // Toolbar
    var toolbar = document.createElement('div');
    toolbar.className = 'jeditor-toolbar';

    // Create editor
    var editor = document.createElement('div');
    editor.setAttribute('contenteditable', true);
    editor.setAttribute('spellcheck', false);
    editor.className = 'jeditor';

    // Max height
    if (obj.options.maxHeight) {
        editor.style.overflowY = 'auto';
        editor.style.maxHeight = obj.options.maxHeight;
    }

    // Set editor initial value
    if (obj.options.value) {
        var value = obj.options.value;
    } else {
        var value = el.innerHTML ? el.innerHTML : ''; 
    }

    if (! value) {
        var value = '<br>';
    }

    /**
     * Extract images from a HTML string
     */
    var extractImageFromHtml = function(html) {
        // Create temp element
        var div = document.createElement('div');
        div.innerHTML = html;

        // Extract images
        var img = div.querySelectorAll('img');

        if (img.length) {
            for (var i = 0; i < img.length; i++) {
                obj.addImage(img[i].src);
            }
        }
    }

    /**
     * Insert node at caret
     */
    var insertNodeAtCaret = function(newNode) {
        var sel, range;

        if (window.getSelection) {
            sel = window.getSelection();
            if (sel.rangeCount) {
                range = sel.getRangeAt(0);
                var selectedText = range.toString();
                range.deleteContents();
                range.insertNode(newNode); 
                // move the cursor after element
                range.setStartAfter(newNode);
                range.setEndAfter(newNode); 
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    }

    /**
     * Append snippet or thumbs in the editor
     * @Param object data
     */
    var appendElement = function(data) {
        // Reset snippet
        snippet.innerHTML = '';

        if (data.image) {
            var div = document.createElement('div');
            div.className = 'jsnippet-image';
            div.setAttribute('data-k', 'image');
            snippet.appendChild(div);

            var image = document.createElement('img');
            image.src = data.image;
            div.appendChild(image);
        }

        var div = document.createElement('div');
        div.className = 'jsnippet-title';
        div.setAttribute('data-k', 'title');
        div.innerHTML = data.title;
        snippet.appendChild(div);

        var div = document.createElement('div');
        div.className = 'jsnippet-description';
        div.setAttribute('data-k', 'description');
        div.innerHTML = data.description;
        snippet.appendChild(div);

        var div = document.createElement('div');
        div.className = 'jsnippet-host';
        div.setAttribute('data-k', 'host');
        div.innerHTML = data.host;
        snippet.appendChild(div);

        var div = document.createElement('div');
        div.className = 'jsnippet-url';
        div.setAttribute('data-k', 'url');
        div.innerHTML = data.url;
        snippet.appendChild(div);

        editor.appendChild(snippet);
    }

    var verifyEditor = function() {
        clearTimeout(editorTimer);
        editorTimer = setTimeout(function() {
            var snippet = editor.querySelector('.jsnippet');
            var thumbsContainer = el.querySelector('.jeditor-thumbs-container');

            if (! snippet && ! thumbsContainer) {
                var html = editor.innerHTML.replace(/\n/g, ' ');
                var container = document.createElement('div');
                container.innerHTML = html;
                var thumbsContainer = container.querySelector('.jeditor-thumbs-container');
                if (thumbsContainer) {
                    thumbsContainer.remove();
                }
                var text = container.innerText; 
                var url = jSuites.editor.detectUrl(text);

                if (url) {
                    if (url[0].substr(-3) == 'jpg' || url[0].substr(-3) == 'png' || url[0].substr(-3) == 'gif') {
                        if (jSuites.editor.getDomain(url[0]) == window.location.hostname) {
                            obj.importImage(url[0], '');
                        } else {
                            obj.importImage(obj.options.remoteParser + url[0], '');
                        }
                    } else {
                        var id = jSuites.editor.youtubeParser(url[0]);
                        obj.parseWebsite(url[0], id);
                    }
                }
            }
        }, 1000);
    }

    obj.parseContent = function() {
        verifyEditor();
    }

    obj.parseWebsite = function(url, youtubeId) {
        if (! obj.options.remoteParser) {
            console.log('The remoteParser is not defined');
        } else {
            // Youtube definitions
            if (youtubeId) {
                var url = 'https://www.youtube.com/watch?v=' + youtubeId;
            }

            var p = {
                title: '',
                description: '',
                image: '',
                host: url.split('/')[2],
                url: url,
            }

            jSuites.ajax({
                url: obj.options.remoteParser + encodeURI(url.trim()),
                method: 'GET',
                dataType: 'json',
                success: function(result) {
                    // Get title
                    if (result.title) {
                        p.title = result.title;
                    }
                    // Description
                    if (result.description) {
                        p.description = result.description;
                    }
                    // Image
                    if (result.image) {
                        p.image = result.image;
                    } else if (result['og:image']) {
                        p.image = result['og:image'];
                    }
                    // Host
                    if (result.host) {
                        p.host = result.host;
                    }
                    // Url
                    if (result.url) {
                        p.url = result.url;
                    }

                    appendElement(p);
                }
            });
        }
    }

    /**
     * Set editor value
     */
    obj.setData = function(html) {
        editor.innerHTML = html;
        jSuites.editor.setCursor(editor, true);
    }

    /**
     * Get editor data
     */
    obj.getData = function(json) {
        if (! json) {
            var data = editor.innerHTML;
        } else {
            var data = {
                content : '',
            }

            // Get tag users
            var tagged = editor.querySelectorAll('.post-tag');
            if (tagged.length) {
                data.users = [];
                for (var i = 0; i < tagged.length; i++) {
                    var userId = tagged[i].getAttribute('data-user');
                    if (userId) {
                        data.users.push(userId);
                    }
                }
                data.users = data.users.join(',');
            }

            if (snippet.innerHTML) {
                var index = 0;
                data.snippet = {};
                for (var i = 0; i < snippet.children.length; i++) {
                    // Get key from element
                    var key = snippet.children[i].getAttribute('data-k');
                    if (key) {
                        if (key == 'image') {
                            data.snippet.image = snippet.children[i].children[0].getAttribute('src');
                        } else {
                            data.snippet[key] = snippet.children[i].innerHTML;
                        }
                    }
                }

                snippet.innerHTML = '';
                snippet.remove();
            }

            var text = editor.innerHTML;
            text = text.replace(/<br>/g, "\n");
            text = text.replace(/<\/div>/g, "<\/div>\n");
            text = text.replace(/<(?:.|\n)*?>/gm, "");
            data.content = text.trim();
            data = JSON.stringify(data);
        }

        return data;
    }

    // Reset
    obj.reset = function() {
        editor.innerHTML = '';
    }

    obj.addPdf = function(data) {
        if (data.result.substr(0,4) != 'data') {
            console.error('Invalid source');
        } else {
            var canvas = document.createElement('canvas');
            canvas.width = 60;
            canvas.height = 60;

            var img = new Image();
            var ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(function(blob) {
                var newImage = document.createElement('img');
                newImage.src = window.URL.createObjectURL(blob);
                newImage.setAttribute('data-extension', 'pdf');
                if (data.name) {
                    newImage.setAttribute('data-name', data.name);
                }
                if (data.size) {
                    newImage.setAttribute('data-size', data.size);
                }
                if (data.date) {
                    newImage.setAttribute('data-date', data.date);
                }
                newImage.className = 'jfile pdf';

                insertNodeAtCaret(newImage);
                jSuites.files[newImage.src] = data.result.substr(data.result.indexOf(',') + 1);
            });
        }
    }

    obj.addImage = function(src, name, size, date) {
        if (src.substr(0,4) != 'data' && ! obj.options.remoteParser) {
            console.error('remoteParser not defined in your initialization');
        } else {
            // This is to process cross domain images
            if (src.substr(0,4) == 'data') {
                var extension = src.split(';')
                extension = extension[0].split('/');
                extension = extension[1];
            } else {
                var extension = src.substr(src.lastIndexOf('.') + 1);
                // Work for cross browsers
                src = obj.options.remoteParser + src;
            }

            var img = new Image();

            img.onload = function onload() {
                var canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;

                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(function(blob) {
                    var newImage = document.createElement('img');
                    newImage.src = window.URL.createObjectURL(blob);
                    newImage.setAttribute('tabindex', '900');
                    newImage.setAttribute('data-extension', extension);
                    if (name) {
                        newImage.setAttribute('data-name', name);
                    }
                    if (size) {
                        newImage.setAttribute('data-size', size);
                    }
                    if (date) {
                        newImage.setAttribute('data-date', date);
                    }
                    newImage.className = 'jfile';
                    var content = canvas.toDataURL();
                    insertNodeAtCaret(newImage);

                    jSuites.files[newImage.src] = content.substr(content.indexOf(',') + 1);
                });
            };

            img.src = src;
        }
    }

    obj.addFile = function(files) {
        var reader = [];

        for (var i = 0; i < files.length; i++) {
            if (files[i].size > obj.options.maxFileSize) {
                alert('The file is too big');
            } else {
                // Only PDF or Images
                var type = files[i].type.split('/');

                if (type[0] == 'image') {
                    type = 1;
                } else if (type[1] == 'pdf') {
                    type = 2;
                } else {
                    type = 0;
                }

                if (type) {
                    // Create file
                    reader[i] = new FileReader();
                    reader[i].index = i;
                    reader[i].type = type;
                    reader[i].name = files[i].name;
                    reader[i].date = files[i].lastModified;
                    reader[i].size = files[i].size;
                    reader[i].addEventListener("load", function (data) {
                        // Get result
                        if (data.target.type == 2) {
                            if (obj.options.acceptFiles == true) {
                                obj.addPdf(data.target);
                            }
                        } else {
                            obj.addImage(data.target.result, data.target.name, data.total, data.target.lastModified);
                        }
                    }, false);

                    reader[i].readAsDataURL(files[i])
                } else {
                    alert('The extension is not allowed');
                }
            }
        }
    }

    // Destroy
    obj.destroy = function() {
        editor.removeEventListener('mouseup', editorMouseUp);
        editor.removeEventListener('mousedown', editorMouseDown);
        editor.removeEventListener('mousemove', editorMouseMove);
        editor.removeEventListener('keyup', editorKeyUp);
        editor.removeEventListener('keydown', editorKeyDown);
        editor.removeEventListener('dragstart', editorDragStart);
        editor.removeEventListener('dragenter', editorDragEnter);
        editor.removeEventListener('dragover', editorDragOver);
        editor.removeEventListener('drop', editorDrop);
        editor.removeEventListener('paste', editorPaste);

        if (typeof(obj.options.onblur) == 'function') {
            editor.removeEventListener('blur', editorBlur);
        }
        if (typeof(obj.options.onfocus) == 'function') {
            editor.removeEventListener('focus', editorFocus);
        }

        el.editor = null;
        el.classList.remove('jeditor-container');

        toolbar.remove();
        snippet.remove();
        editor.remove();
    }

    var isLetter = function (str) {
        var regex = /([\u0041-\u005A\u0061-\u007A\u00AA\u00B5\u00BA\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02C1\u02C6-\u02D1\u02E0-\u02E4\u02EC\u02EE\u0370-\u0374\u0376\u0377\u037A-\u037D\u0386\u0388-\u038A\u038C\u038E-\u03A1\u03A3-\u03F5\u03F7-\u0481\u048A-\u0527\u0531-\u0556\u0559\u0561-\u0587\u05D0-\u05EA\u05F0-\u05F2\u0620-\u064A\u066E\u066F\u0671-\u06D3\u06D5\u06E5\u06E6\u06EE\u06EF\u06FA-\u06FC\u06FF\u0710\u0712-\u072F\u074D-\u07A5\u07B1\u07CA-\u07EA\u07F4\u07F5\u07FA\u0800-\u0815\u081A\u0824\u0828\u0840-\u0858\u08A0\u08A2-\u08AC\u0904-\u0939\u093D\u0950\u0958-\u0961\u0971-\u0977\u0979-\u097F\u0985-\u098C\u098F\u0990\u0993-\u09A8\u09AA-\u09B0\u09B2\u09B6-\u09B9\u09BD\u09CE\u09DC\u09DD\u09DF-\u09E1\u09F0\u09F1\u0A05-\u0A0A\u0A0F\u0A10\u0A13-\u0A28\u0A2A-\u0A30\u0A32\u0A33\u0A35\u0A36\u0A38\u0A39\u0A59-\u0A5C\u0A5E\u0A72-\u0A74\u0A85-\u0A8D\u0A8F-\u0A91\u0A93-\u0AA8\u0AAA-\u0AB0\u0AB2\u0AB3\u0AB5-\u0AB9\u0ABD\u0AD0\u0AE0\u0AE1\u0B05-\u0B0C\u0B0F\u0B10\u0B13-\u0B28\u0B2A-\u0B30\u0B32\u0B33\u0B35-\u0B39\u0B3D\u0B5C\u0B5D\u0B5F-\u0B61\u0B71\u0B83\u0B85-\u0B8A\u0B8E-\u0B90\u0B92-\u0B95\u0B99\u0B9A\u0B9C\u0B9E\u0B9F\u0BA3\u0BA4\u0BA8-\u0BAA\u0BAE-\u0BB9\u0BD0\u0C05-\u0C0C\u0C0E-\u0C10\u0C12-\u0C28\u0C2A-\u0C33\u0C35-\u0C39\u0C3D\u0C58\u0C59\u0C60\u0C61\u0C85-\u0C8C\u0C8E-\u0C90\u0C92-\u0CA8\u0CAA-\u0CB3\u0CB5-\u0CB9\u0CBD\u0CDE\u0CE0\u0CE1\u0CF1\u0CF2\u0D05-\u0D0C\u0D0E-\u0D10\u0D12-\u0D3A\u0D3D\u0D4E\u0D60\u0D61\u0D7A-\u0D7F\u0D85-\u0D96\u0D9A-\u0DB1\u0DB3-\u0DBB\u0DBD\u0DC0-\u0DC6\u0E01-\u0E30\u0E32\u0E33\u0E40-\u0E46\u0E81\u0E82\u0E84\u0E87\u0E88\u0E8A\u0E8D\u0E94-\u0E97\u0E99-\u0E9F\u0EA1-\u0EA3\u0EA5\u0EA7\u0EAA\u0EAB\u0EAD-\u0EB0\u0EB2\u0EB3\u0EBD\u0EC0-\u0EC4\u0EC6\u0EDC-\u0EDF\u0F00\u0F40-\u0F47\u0F49-\u0F6C\u0F88-\u0F8C\u1000-\u102A\u103F\u1050-\u1055\u105A-\u105D\u1061\u1065\u1066\u106E-\u1070\u1075-\u1081\u108E\u10A0-\u10C5\u10C7\u10CD\u10D0-\u10FA\u10FC-\u1248\u124A-\u124D\u1250-\u1256\u1258\u125A-\u125D\u1260-\u1288\u128A-\u128D\u1290-\u12B0\u12B2-\u12B5\u12B8-\u12BE\u12C0\u12C2-\u12C5\u12C8-\u12D6\u12D8-\u1310\u1312-\u1315\u1318-\u135A\u1380-\u138F\u13A0-\u13F4\u1401-\u166C\u166F-\u167F\u1681-\u169A\u16A0-\u16EA\u1700-\u170C\u170E-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176C\u176E-\u1770\u1780-\u17B3\u17D7\u17DC\u1820-\u1877\u1880-\u18A8\u18AA\u18B0-\u18F5\u1900-\u191C\u1950-\u196D\u1970-\u1974\u1980-\u19AB\u19C1-\u19C7\u1A00-\u1A16\u1A20-\u1A54\u1AA7\u1B05-\u1B33\u1B45-\u1B4B\u1B83-\u1BA0\u1BAE\u1BAF\u1BBA-\u1BE5\u1C00-\u1C23\u1C4D-\u1C4F\u1C5A-\u1C7D\u1CE9-\u1CEC\u1CEE-\u1CF1\u1CF5\u1CF6\u1D00-\u1DBF\u1E00-\u1F15\u1F18-\u1F1D\u1F20-\u1F45\u1F48-\u1F4D\u1F50-\u1F57\u1F59\u1F5B\u1F5D\u1F5F-\u1F7D\u1F80-\u1FB4\u1FB6-\u1FBC\u1FBE\u1FC2-\u1FC4\u1FC6-\u1FCC\u1FD0-\u1FD3\u1FD6-\u1FDB\u1FE0-\u1FEC\u1FF2-\u1FF4\u1FF6-\u1FFC\u2071\u207F\u2090-\u209C\u2102\u2107\u210A-\u2113\u2115\u2119-\u211D\u2124\u2126\u2128\u212A-\u212D\u212F-\u2139\u213C-\u213F\u2145-\u2149\u214E\u2183\u2184\u2C00-\u2C2E\u2C30-\u2C5E\u2C60-\u2CE4\u2CEB-\u2CEE\u2CF2\u2CF3\u2D00-\u2D25\u2D27\u2D2D\u2D30-\u2D67\u2D6F\u2D80-\u2D96\u2DA0-\u2DA6\u2DA8-\u2DAE\u2DB0-\u2DB6\u2DB8-\u2DBE\u2DC0-\u2DC6\u2DC8-\u2DCE\u2DD0-\u2DD6\u2DD8-\u2DDE\u2E2F\u3005\u3006\u3031-\u3035\u303B\u303C\u3041-\u3096\u309D-\u309F\u30A1-\u30FA\u30FC-\u30FF\u3105-\u312D\u3131-\u318E\u31A0-\u31BA\u31F0-\u31FF\u3400-\u4DB5\u4E00-\u9FCC\uA000-\uA48C\uA4D0-\uA4FD\uA500-\uA60C\uA610-\uA61F\uA62A\uA62B\uA640-\uA66E\uA67F-\uA697\uA6A0-\uA6E5\uA717-\uA71F\uA722-\uA788\uA78B-\uA78E\uA790-\uA793\uA7A0-\uA7AA\uA7F8-\uA801\uA803-\uA805\uA807-\uA80A\uA80C-\uA822\uA840-\uA873\uA882-\uA8B3\uA8F2-\uA8F7\uA8FB\uA90A-\uA925\uA930-\uA946\uA960-\uA97C\uA984-\uA9B2\uA9CF\uAA00-\uAA28\uAA40-\uAA42\uAA44-\uAA4B\uAA60-\uAA76\uAA7A\uAA80-\uAAAF\uAAB1\uAAB5\uAAB6\uAAB9-\uAABD\uAAC0\uAAC2\uAADB-\uAADD\uAAE0-\uAAEA\uAAF2-\uAAF4\uAB01-\uAB06\uAB09-\uAB0E\uAB11-\uAB16\uAB20-\uAB26\uAB28-\uAB2E\uABC0-\uABE2\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFA6D\uFA70-\uFAD9\uFB00-\uFB06\uFB13-\uFB17\uFB1D\uFB1F-\uFB28\uFB2A-\uFB36\uFB38-\uFB3C\uFB3E\uFB40\uFB41\uFB43\uFB44\uFB46-\uFBB1\uFBD3-\uFD3D\uFD50-\uFD8F\uFD92-\uFDC7\uFDF0-\uFDFB\uFE70-\uFE74\uFE76-\uFEFC\uFF21-\uFF3A\uFF41-\uFF5A\uFF66-\uFFBE\uFFC2-\uFFC7\uFFCA-\uFFCF\uFFD2-\uFFD7\uFFDA-\uFFDC]+)/g;
        return str.match(regex) ? 1 : 0;
    }

    // Event handlers
    var editorMouseUp = function(e) {
        editorAction = false;
    }

    var editorMouseDown = function(e) {
        var close = function(snippet) {
            var rect = snippet.getBoundingClientRect();
            if (rect.width - (e.clientX - rect.left) < 40 && e.clientY - rect.top < 40) {
                snippet.innerHTML = '';
                snippet.remove();
            }
        }

        if (e.target.tagName == 'IMG') {
            if (e.target.style.cursor) {
                var rect = e.target.getBoundingClientRect();
                editorAction = {
                    e: e.target,
                    x: e.clientX,
                    y: e.clientY,
                    w: rect.width,
                    h: rect.height,
                    d: e.target.style.cursor,
                }

                if (! e.target.style.width) {
                    e.target.style.width = rect.width + 'px';
                }

                if (! e.target.style.height) {
                    e.target.style.height = rect.height + 'px';
                }

                var s = window.getSelection();
                if (s.rangeCount) {
                    for (var i = 0; i < s.rangeCount; i++) {
                        s.removeRange(s.getRangeAt(i));
                    }
                }
            } else {
                editorAction = true;
            }
        } else { 
            if (e.target.classList.contains('jsnippet')) {
                close(e.target);
            } else if (e.target.parentNode.classList.contains('jsnippet')) {
                close(e.target.parentNode);
            }

            editorAction = true;
        }
    }

    var editorMouseMove = function(e) {
        if (e.target.tagName == 'IMG') {
            if (e.target.getAttribute('tabindex')) {
                var rect = e.target.getBoundingClientRect();
                if (e.clientY - rect.top < 5) {
                    if (rect.width - (e.clientX - rect.left) < 5) {
                        e.target.style.cursor = 'ne-resize';
                    } else if (e.clientX - rect.left < 5) {
                        e.target.style.cursor = 'nw-resize';
                    } else {
                        e.target.style.cursor = 'n-resize';
                    }
                } else if (rect.height - (e.clientY - rect.top) < 5) {
                    if (rect.width - (e.clientX - rect.left) < 5) {
                        e.target.style.cursor = 'se-resize';
                    } else if (e.clientX - rect.left < 5) {
                        e.target.style.cursor = 'sw-resize';
                    } else {
                        e.target.style.cursor = 's-resize';
                    }
                } else if (rect.width - (e.clientX - rect.left) < 5) {
                    e.target.style.cursor = 'e-resize';
                } else if (e.clientX - rect.left < 5) {
                    e.target.style.cursor = 'w-resize';
                } else {
                    e.target.style.cursor = '';
                }
            }
        }

        // Move
        if (e.which == 1 && editorAction && editorAction.d) {
            if (editorAction.d == 'e-resize' || editorAction.d == 'ne-resize' ||  editorAction.d == 'se-resize') {
                editorAction.e.style.width = (editorAction.w + (e.clientX - editorAction.x)) + 'px';

                if (e.shiftKey) {
                    var newHeight = (e.clientX - editorAction.x) * (editorAction.h / editorAction.w);
                    editorAction.e.style.height = editorAction.h + newHeight + 'px';
                } else {
                    var newHeight =  null;
                }
            }

            if (! newHeight) {
                if (editorAction.d == 's-resize' || editorAction.d == 'se-resize' || editorAction.d == 'sw-resize') {
                    if (! e.shiftKey) {
                        editorAction.e.style.height = editorAction.h + (e.clientY - editorAction.y) + 'px';
                    }
                }
            }
        }
    }

    var editorKeyUp = function(e) {
        if (! editor.innerHTML) {
            editor.innerHTML = '<div><br></div>';
        }

        if (typeof(obj.options.onkeyup) == 'function') { 
            obj.options.onkeyup(el, obj, e);
        }
    }


    var editorKeyDown = function(e) {
        // Check for URL
        if (obj.options.parseURL == true) {
            verifyEditor();
        }

        if (typeof(obj.options.onkeydown) == 'function') { 
            obj.options.onkeydown(el, obj, e);
        }
    }

    var editorPaste = function(e) {
        if (e.clipboardData || e.originalEvent.clipboardData) {
            var html = (e.originalEvent || e).clipboardData.getData('text/html');
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
            var file = (e.originalEvent || e).clipboardData.files
        } else if (window.clipboardData) {
            var html = window.clipboardData.getData('Html');
            var text = window.clipboardData.getData('Text');
            var file = window.clipboardData.files
        }

        if (file.length) {
            // Paste a image from the clipboard
            obj.addFile(file);
        } else {
            // Paste text
            text = text.split('\r\n');
            var str = '';
            if (e.target.nodeName == 'DIV' && e.target.classList.contains('jeditor')) {
                for (var i = 0; i < text.length; i++) {
                    var tmp = document.createElement('div');
                    if (text[i]) {
                        tmp.innerHTML = text[i];
                    } else {
                        tmp.innerHTML = '<br/>';
                    }
                    e.target.appendChild(tmp);
                }
            } else {
                for (var i = 0; i < text.length; i++) {
                    if (text[i]) {
                        str += '<div>' + text[i] + "</div>\r\n";
                    }
                }
                // Insert text
                document.execCommand('insertHtml', false, str);
            }

            // Extra images from the paste
            if (obj.options.acceptImages == true) {
                extractImageFromHtml(html);
            }
        }

        e.preventDefault();
    }

    var editorDragStart = function(e) {
        if (editorAction && editorAction.e) {
            e.preventDefault();
        }
    }

    var editorDragEnter = function(e) {
        if (editorAction || obj.options.dropZone == false) {
            // Do nothing
        } else {
            el.classList.add('jeditor-dragging');
        }
    }

    var editorDragOver = function(e) {
        if (editorAction || obj.options.dropZone == false) {
            // Do nothing
        } else {
            if (editorTimer) {
                clearTimeout(editorTimer);
            }

            editorTimer = setTimeout(function() {
                el.classList.remove('jeditor-dragging');
            }, 100);
        }
    }

    var editorDrop = function(e) {
        if (editorAction || obj.options.dropZone == false) {
            // Do nothing
        } else {
            // Position caret on the drop
            var range = null;
            if (document.caretRangeFromPoint) {
                range=document.caretRangeFromPoint(e.clientX, e.clientY);
            } else if (e.rangeParent) {
                range=document.createRange();
                range.setStart(e.rangeParent,e.rangeOffset);
            }
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            sel.anchorNode.parentNode.focus();

            var html = (e.originalEvent || e).dataTransfer.getData('text/html');
            var text = (e.originalEvent || e).dataTransfer.getData('text/plain');
            var file = (e.originalEvent || e).dataTransfer.files;
    
            if (file.length) {
                obj.addFile(file);
            } else if (text) {
                extractImageFromHtml(html);
            }

            el.classList.remove('jeditor-dragging');
            e.preventDefault();
        }
    }

    var editorBlur = function() {
        obj.options.onblur(el, obj, obj.getData());
    }

    var editorFocus = function() {
        obj.options.onfocus(el, obj, obj.getData());
    }

    editor.addEventListener('mouseup', editorMouseUp);
    editor.addEventListener('mousedown', editorMouseDown);
    editor.addEventListener('mousemove', editorMouseMove);
    editor.addEventListener('keyup', editorKeyUp);
    editor.addEventListener('keydown', editorKeyDown);
    editor.addEventListener('dragstart', editorDragStart);
    editor.addEventListener('dragenter', editorDragEnter);
    editor.addEventListener('dragover', editorDragOver);
    editor.addEventListener('drop', editorDrop);
    editor.addEventListener('paste', editorPaste);

    // Blur
    if (typeof(obj.options.onblur) == 'function') {
        editor.addEventListener('blur', editorBlur);
    }

    // Focus
    if (typeof(obj.options.onfocus) == 'function') {
        editor.addEventListener('focus', editorFocus);
    }

    // Onload
    if (typeof(obj.options.onload) == 'function') {
        obj.options.onload(el, obj, editor);
    }

    // Set value to the editor
    editor.innerHTML = value;

    // Append editor to the containre
    el.appendChild(editor);

    // Snippet
    if (obj.options.snippet) {
        appendElement(obj.options.snippet);
    }

    // Default toolbar
    if (obj.options.toolbar == null) {
        obj.options.toolbar = jSuites.editor.getDefaultToolbar();
    }

    // Add toolbar
    if (obj.options.toolbar) {
        // Create toolbar
        jSuites.toolbar(toolbar, {
            items: obj.options.toolbar
        });
        // Append to the DOM
        el.appendChild(toolbar);
    }

    // Focus to the editor
    if (obj.options.focus) {
        jSuites.editor.setCursor(editor, obj.options.focus == 'initial' ? true : false);
    }

    el.editor = obj;

    return obj;
});

jSuites.editor.setCursor = function(element, first) {
    element.focus();
    document.execCommand('selectAll');
    var sel = window.getSelection();
    var range = sel.getRangeAt(0);
    if (first == true) {
        var node = range.startContainer;
        var size = 0;
    } else {
        var node = range.endContainer;
        var size = node.length;
    }
    range.setStart(node, size);
    range.setEnd(node, size);
    sel.removeAllRanges();
    sel.addRange(range);
}

jSuites.editor.getDomain = function(url) {
    return url.replace('http://','').replace('https://','').replace('www.','').split(/[/?#]/)[0].split(/:/g)[0];
}

jSuites.editor.detectUrl = function(text) {
    var expression = /(((https?:\/\/)|(www\.))[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]+)/ig;
    var links = text.match(expression);

    if (links) {
        if (links[0].substr(0,3) == 'www') {
            links[0] = 'http://' + links[0];
        }
    }

    return links;
}

jSuites.editor.youtubeParser = function(url) {
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);

    return (match && match[7].length == 11) ? match[7] : false;
}

jSuites.editor.getDefaultToolbar = function() { 
    return [
        {
            content: 'undo',
            onclick: function() {
                document.execCommand('undo');
            }
        },
        {
            content: 'redo',
            onclick: function() {
                document.execCommand('redo');
            }
        },
        {
            type:'divisor'
        },
        {
            content: 'format_bold',
            onclick: function(a,b,c) {
                document.execCommand('bold');

                if (document.queryCommandState("bold")) {
                    c.classList.add('selected');
                } else {
                    c.classList.remove('selected');
                }
            }
        },
        {
            content: 'format_italic',
            onclick: function(a,b,c) {
                document.execCommand('italic');

                if (document.queryCommandState("italic")) {
                    c.classList.add('selected');
                } else {
                    c.classList.remove('selected');
                }
            }
        },
        {
            content: 'format_underline',
            onclick: function(a,b,c) {
                document.execCommand('underline');

                if (document.queryCommandState("underline")) {
                    c.classList.add('selected');
                } else {
                    c.classList.remove('selected');
                }
            }
        },
        {
            type:'divisor'
        },
        {
            content: 'format_list_bulleted',
            onclick: function(a,b,c) {
                document.execCommand('insertUnorderedList');

                if (document.queryCommandState("insertUnorderedList")) {
                    c.classList.add('selected');
                } else {
                    c.classList.remove('selected');
                }
            }
        },
        {
            content: 'format_list_numbered',
            onclick: function(a,b,c) {
                document.execCommand('insertOrderedList');

                if (document.queryCommandState("insertOrderedList")) {
                    c.classList.add('selected');
                } else {
                    c.classList.remove('selected');
                }
            }
        },
        {
            content: 'format_indent_increase',
            onclick: function(a,b,c) {
                document.execCommand('indent', true, null);

                if (document.queryCommandState("indent")) {
                    c.classList.add('selected');
                } else {
                    c.classList.remove('selected');
                }
            }
        },
        {
            content: 'format_indent_decrease',
            onclick: function() {
                document.execCommand('outdent');

                if (document.queryCommandState("outdent")) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        }/*,
        {
            icon: ['format_align_left', 'format_align_right', 'format_align_center'],
            onclick: function() {
                document.execCommand('justifyCenter');

                if (document.queryCommandState("justifyCenter")) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        }
        {
            type:'select',
            items: ['Verdana','Arial','Courier New'],
            onchange: function() {
            }
        },
        {
            type:'select',
            items: ['10px','12px','14px','16px','18px','20px','22px'],
            onchange: function() {
            }
        },
        {
            icon:'format_align_left',
            onclick: function() {
                document.execCommand('JustifyLeft');

                if (document.queryCommandState("JustifyLeft")) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        },
        {
            icon:'format_align_center',
            onclick: function() {
                document.execCommand('justifyCenter');

                if (document.queryCommandState("justifyCenter")) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        },
        {
            icon:'format_align_right',
            onclick: function() {
                document.execCommand('justifyRight');

                if (document.queryCommandState("justifyRight")) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        },
        {
            icon:'format_align_justify',
            onclick: function() {
                document.execCommand('justifyFull');

                if (document.queryCommandState("justifyFull")) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        },
        {
            icon:'format_list_bulleted',
            onclick: function() {
                document.execCommand('insertUnorderedList');

                if (document.queryCommandState("insertUnorderedList")) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        }*/
    ];
}


jSuites.files = (function(element) {
    if (! element) {
        console.error('No element defined in the arguments of your method');
    }

    var obj = {};
    obj.files = [];
    obj.get = function() {
        return obj.files;
    }
    obj.set = function() {
        // Get attachments
        var files = element.querySelectorAll('.jfile');

        if (files.length > 0) {
            var data = [];
            for (var i = 0; i < files.length; i++) {
                var file = {};

                var src = files[i].getAttribute('src');

                if (files[i].classList.contains('jremove')) {
                    file.remove = 1;
                } else {
                    if (src.substr(0,4) == 'data') {
                        file.content = src.substr(src.indexOf(',') + 1);
                        file.extension = files[i].getAttribute('data-extension');
                    } else {
                        file.file = src;
                        file.extension = files[i].getAttribute('data-extension');
                        if (! file.extension) {
                            file.extension =  src.substr(src.lastIndexOf('.') + 1);
                        }
                        if (obj.files[file.file]) {
                            file.content = obj.files[file.file];
                        }
                    }

                    // Optional file information
                    if (files[i].getAttribute('data-name')) {
                        file.name = files[i].getAttribute('data-name');
                    }
                    if (files[i].getAttribute('data-file')) {
                        file.file = files[i].getAttribute('data-file');
                    }
                    if (files[i].getAttribute('data-size')) {
                        file.size = files[i].getAttribute('data-size');
                    }
                    if (files[i].getAttribute('data-date')) {
                        file.date = files[i].getAttribute('data-date');
                    }
                    if (files[i].getAttribute('data-cover')) {
                        file.cover = files[i].getAttribute('data-cover');
                    }
                }
                data[i] = file;
            }

            return data;
        }
    }

    obj.set();

    return obj;
});

jSuites.form = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        url: null,
        message: 'Are you sure? There are unsaved information in your form',
        ignore: false,
        currentHash: null,
        submitButton:null,
        validations: null,
        onload: null,
        onbeforesave: null,
        onsave: null,
        onerror: function(el, message) {
            jSuites.alert(message);
        }
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Validations
    if (! obj.options.validations) {
        obj.options.validations = {};
    }

    // submitButton
    if (obj.options.submitButton && obj.options.url) {
        obj.options.submitButton.onclick = function() {
            obj.save();
        }
    }

    if (! obj.options.validations.email) {
        obj.options.validations.email = function(data) {
            var reg = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
            return data && reg.test(data) ? true : false; 
        }
    }

    if (! obj.options.validations.length) {
        obj.options.validations.length = function(data, element) {
            var len = element.getAttribute('data-length') || 5;
            return (data.length >= len) ? true : false;
        }
    }

    if (! obj.options.validations.required) {
        obj.options.validations.required = function(data) {
            return data.trim() ? true : false;
        }
    }

    obj.setUrl = function(url) {
        obj.options.url = url;
    }

    obj.load = function() {
        jSuites.ajax({
            url: obj.options.url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                jSuites.form.setElements(el, data);

                if (typeof(obj.options.onload) == 'function') {
                    obj.options.onload(el, data);
                }
            }
        });
    }

    obj.save = function() {
        var test = obj.validate();

        if (test) {
            obj.options.onerror(el, test);
        } else {
            var data = jSuites.form.getElements(el, true);

            if (typeof(obj.options.onbeforesave) == 'function') {
                var data = obj.options.onbeforesave(el, data);

                if (data === false) {
                    console.log('Onbeforesave returned false');
                    return; 
                }
            }

            jSuites.ajax({
                url: obj.options.url,
                method: 'POST',
                dataType: 'json',
                data: data,
                success: function(result) {
                    if (typeof(obj.options.onsave) == 'function') {
                        var data = obj.options.onsave(el, result);
                    }

                    obj.reset();
                }
            });
        }
    }

    var addError = function(element) {
        // Add error in the element
        element.classList.add('error');
        // Submit button
        if (obj.options.submitButton) {
            obj.options.submitButton.setAttribute('disabled', true);
        }
        // Return error message
        var error = element.getAttribute('data-error') || 'There is an error in the form';
        element.setAttribute('title', error);
        return error;
    }

    var delError = function(element) {
        var error = false;
        // Remove class from this element
        element.classList.remove('error');
        element.removeAttribute('title');
        // Get elements in the form
        var elements = el.querySelectorAll("input, select, textarea");
        // Run all elements 
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].getAttribute('data-validation')) {
                if (elements[i].classList.contains('error')) {
                    error = true;
                }
            }
        }

        if (obj.options.submitButton) {
            if (error) {
                obj.options.submitButton.setAttribute('disabled', true);
            } else {
                obj.options.submitButton.removeAttribute('disabled');
            }
        }
    }

    obj.validateElement = function(element) {
        // Test results
        var test = false;
        // Validation
        var validation = element.getAttribute('data-validation');
        // Parse
        if (typeof(obj.options.validations[validation]) == 'function' && ! obj.options.validations[validation](element.value, element)) {
            // Not passed in the test
            test = addError(element);
        } else {
            if (element.classList.contains('error')) {
                delError(element);
            }
        }

        return test;
    }

    obj.reset = function() {
        // Get elements in the form
        var elements = el.querySelectorAll("input, select, textarea");
        // Run all elements 
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].tagName == 'INPUT' && elements[i].type == 'checkbox') {
                elements[i].removeAttribute('checked');
            } else {
                elements[i].value = '';
            }
        }
    }

    // Run form validation
    obj.validate = function() {
        var test = [];
        // Get elements in the form
        var elements = el.querySelectorAll("input, select, textarea");
        // Run all elements 
        for (var i = 0; i < elements.length; i++) {
            // Required
            if (elements[i].getAttribute('data-validation')) {
                var res = obj.validateElement(elements[i]);
                if (res) {
                    test.push(res);
                }
            }
        }
        if (test.length > 0) {
            return test.join('<br>');
        } else {
            return false;
        }
    }

    // Check the form
    obj.getError = function() {
        // Validation
        return obj.validation() ? true : false;
    }

    // Return the form hash
    obj.setHash = function() {
        return obj.getHash(jSuites.form.getElements(el));
    }

    // Get the form hash
    obj.getHash = function(str) {
        var hash = 0, i, chr;

        if (str.length === 0) {
            return hash;
        } else {
            for (i = 0; i < str.length; i++) {
              chr = str.charCodeAt(i);
              hash = ((hash << 5) - hash) + chr;
              hash |= 0;
            }
        }

        return hash;
    }

    // Is there any change in the form since start tracking?
    obj.isChanged = function() {
        var hash = obj.setHash();
        return (obj.options.currentHash != hash);
    }

    // Restart tracking
    obj.resetTracker = function() {
        obj.options.currentHash = obj.setHash();
        obj.options.ignore = false;
    }

    obj.reset = function() {
        obj.options.currentHash = obj.setHash();
        obj.options.ignore = false;
    }

    // Ignore flag
    obj.setIgnore = function(ignoreFlag) {
        obj.options.ignore = ignoreFlag ? true : false;
    }

    // Start tracking in one second
    setTimeout(function() {
        obj.options.currentHash = obj.setHash();
    }, 1000);

    // Validations
    el.addEventListener("keyup", function(e) {
        if (e.target.getAttribute('data-validation')) {
            obj.validateElement(e.target);
        }
    });

    // Alert
    if (! jSuites.form.hasEvents) {
        window.addEventListener("beforeunload", function (e) {
            if (obj.isChanged() && obj.options.ignore == false) {
                var confirmationMessage =  obj.options.message? obj.options.message : "\o/";

                if (confirmationMessage) {
                    if (typeof e == 'undefined') {
                        e = window.event;
                    }

                    if (e) {
                        e.returnValue = confirmationMessage;
                    }

                    return confirmationMessage;
                } else {
                    return void(0);
                }
            }
        });

        jSuites.form.hasEvents = true;
    }

    el.form = obj;

    return obj;
});

// Get form elements
jSuites.form.getElements = function(el, asArray) {
    var data = {};
    var elements = el.querySelectorAll("input, select, textarea");

    for (var i = 0; i < elements.length; i++) {
        var element = elements[i];
        var name = element.name;
        var value = element.value;

        if (name) {
            data[name] = value;
        }
    }

    return asArray == true ? data : JSON.stringify(data);
}

//Get form elements
jSuites.form.setElements = function(el, data) {
    var elements = el.querySelectorAll("input, select, textarea");

    for (var i = 0; i < elements.length; i++) {
        var name = elements[i].getAttribute('name');
        if (data[name]) {
            elements[i].value = data[name];
        }
    }
}

// Legacy
jSuites.tracker = jSuites.form;

jSuites.guid = function() {
    var guid = '';
    for (var i = 0; i < 32; i++) {
        guid += Math.floor(Math.random()*0xF).toString(0xF);
    }
    return guid;
}

jSuites.getWindowWidth = function() {
    var w = window,
    d = document,
    e = d.documentElement,
    g = d.getElementsByTagName('body')[0],
    x = w.innerWidth || e.clientWidth || g.clientWidth;
    return x;
}

jSuites.getWindowHeight = function() {
    var w = window,
    d = document,
    e = d.documentElement,
    g = d.getElementsByTagName('body')[0],
    y = w.innerHeight|| e.clientHeight|| g.clientHeight;
    return  y;
}

jSuites.getPosition = function(e) {
    if (e.changedTouches && e.changedTouches[0]) {
        var x = e.changedTouches[0].pageX;
        var y = e.changedTouches[0].pageY;
    } else {
        var x = (window.Event) ? e.pageX : e.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
        var y = (window.Event) ? e.pageY : e.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
    }

    return [ x, y ];
}

jSuites.click = function(el) {
    if (el.click) {
        el.click();
    } else {
        var evt = new MouseEvent('click', {
            bubbles: true,
            cancelable: true,
            view: window
        });
        el.dispatchEvent(evt);
    }
}

jSuites.findElement = function(element, condition) {
    var foundElement = false;

    function path (element) {
        if (element && ! foundElement) {
            if (typeof(condition) == 'function') {
                foundElement = condition(element)
            } else if (typeof(condition) == 'string') {
                if (element.classList && element.classList.contains(condition)) {
                    foundElement = element;
                }
            }
        }

        if (element.parentNode && ! foundElement) {
            path(element.parentNode);
        }
    }

    path(element);

    return foundElement;
}


jSuites.image = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        minWidth: false,
        onchange: null,
        singleFile: true,
        remoteParser: null,
        text:{
            extensionNotAllowed:'The extension is not allowed',
            imageTooSmall:'The resolution is too low, try a image with a better resolution. width > 800px',
        }
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Upload icon
    el.classList.add('jupload');

    // Add image
    obj.addImage = function(file) {
        if (! file.date) {
            file.date = '';
        }
        var img = document.createElement('img');
        img.setAttribute('data-date', file.lastmodified ? file.lastmodified : file.date);
        img.setAttribute('data-name', file.name);
        img.setAttribute('data-size', file.size);
        img.setAttribute('data-small', file.small ? file.small : '');
        img.setAttribute('data-cover', file.cover ? 1 : 0);
        img.setAttribute('data-extension', file.extension);
        img.setAttribute('src', file.file);
        img.className = 'jfile';
        img.style.width = '100%';

        return img;
    }

    // Add image
    obj.addImages = function(files) {
        if (obj.options.singleFile == true) {
            el.innerHTML = '';
        }

        for (var i = 0; i < files.length; i++) {
            el.appendChild(obj.addImage(files[i]));
        }
    }

    obj.addFromFile = function(file) {
        var type = file.type.split('/');
        if (type[0] == 'image') {
            if (obj.options.singleFile == true) {
                el.innerHTML = '';
            }

            var imageFile = new FileReader();
            imageFile.addEventListener("load", function (v) {

                var img = new Image();

                img.onload = function onload() {
                    var canvas = document.createElement('canvas');
                    canvas.width = img.width;
                    canvas.height = img.height;

                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    var data = {
                        file: canvas.toDataURL(),
                        extension: file.name.substr(file.name.lastIndexOf('.') + 1),
                        name: file.name,
                        size: file.size,
                        lastmodified: file.lastModified,
                    }
                    var newImage = obj.addImage(data);
                    el.appendChild(newImage);

                    // Onchange
                    if (typeof(obj.options.onchange) == 'function') {
                        obj.options.onchange(newImage);
                    }
                };

                img.src = v.srcElement.result;
            });

            imageFile.readAsDataURL(file);
        } else {
            alert(text.extentionNotAllowed);
        }
    }

    obj.addFromUrl = function(src) {
        if (src.substr(0,4) != 'data' && ! obj.options.remoteParser) {
            console.error('remoteParser not defined in your initialization');
        } else {
            // This is to process cross domain images
            if (src.substr(0,4) == 'data') {
                var extension = src.split(';')
                extension = extension[0].split('/');
                extension = extension[1];
            } else {
                var extension = src.substr(src.lastIndexOf('.') + 1);
                // Work for cross browsers
                src = obj.options.remoteParser + src;
            }

            var img = new Image();

            img.onload = function onload() {
                var canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;

                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(function(blob) {
                    var data = {
                        file: window.URL.createObjectURL(blob),
                        extension: extension
                    }
                    var newImage = obj.addImage(data);
                    el.appendChild(newImage);

                    // Keep base64 ready to go
                    var content = canvas.toDataURL();
                    jSuites.files[data.file] = content.substr(content.indexOf(',') + 1);

                    // Onchange
                    if (typeof(obj.options.onchange) == 'function') {
                        obj.options.onchange(newImage);
                    }
                });
            };

            img.src = src;
        }
    }

    var attachmentInput = document.createElement('input');
    attachmentInput.type = 'file';
    attachmentInput.setAttribute('accept', 'image/*');
    attachmentInput.onchange = function() {
        for (var i = 0; i < this.files.length; i++) {
            obj.addFromFile(this.files[i]);
        }
    }

    el.addEventListener("dblclick", function(e) {
        jSuites.click(attachmentInput);
    });

    el.addEventListener('dragenter', function(e) {
        el.style.border = '1px dashed #000';
    });

    el.addEventListener('dragleave', function(e) {
        el.style.border = '1px solid #eee';
    });

    el.addEventListener('dragstop', function(e) {
        el.style.border = '1px solid #eee';
    });

    el.addEventListener('dragover', function(e) {
        e.preventDefault();
    });

    el.addEventListener('drop', function(e) {
        e.preventDefault();  
        e.stopPropagation();


        var html = (e.originalEvent || e).dataTransfer.getData('text/html');
        var file = (e.originalEvent || e).dataTransfer.files;

        if (file.length) {
            for (var i = 0; i < e.dataTransfer.files.length; i++) {
                obj.addFromFile(e.dataTransfer.files[i]);
            }
        } else if (html) {
            if (obj.options.singleFile == true) {
                el.innerHTML = '';
            }

            // Create temp element
            var div = document.createElement('div');
            div.innerHTML = html;

            // Extract images
            var img = div.querySelectorAll('img');

            if (img.length) {
                for (var i = 0; i < img.length; i++) {
                    obj.addFromUrl(img[i].src);
                }
            }
        }

        el.style.border = '1px solid #eee';

        return false;
    });

    el.image = obj;

    return obj;
});

jSuites.lazyLoading = (function(el, options) {
    var obj = {}

    // Mandatory options
    if (! options.loadUp || typeof(options.loadUp) != 'function') {
        options.loadUp = function() {
            return false;
        }
    }
    if (! options.loadDown || typeof(options.loadDown) != 'function') {
        options.loadDown = function() {
            return false;
        }
    }
    // Timer ms
    if (! options.timer) {
        options.timer = 100;
    }

    // Timer
    var timeControlLoading = null;

    // Controls
    var scrollControls = function(e) {
        if (timeControlLoading == null) {
            var scrollTop = el.scrollTop;
            if (el.scrollTop + (el.clientHeight * 2) >= el.scrollHeight) {
                if (options.loadDown()) {
                    if (scrollTop == el.scrollTop) {
                        el.scrollTop = el.scrollTop - (el.clientHeight);
                    }
                }
            } else if (el.scrollTop <= el.clientHeight) {
                if (options.loadUp()) {
                    if (scrollTop == el.scrollTop) {
                        el.scrollTop = el.scrollTop + (el.clientHeight);
                    }
                }
            }

            timeControlLoading = setTimeout(function() {
                timeControlLoading = null;
            }, options.timer);
        }
    }

    // Onscroll
    el.onscroll = function(e) {
        scrollControls(e);
    }

    el.onwheel = function(e) {
        scrollControls(e);
    }

    return obj;
});

jSuites.loading = (function() {
    var obj = {};

    var loading = null;

    obj.show = function() {
        if (! loading) {
            loading = document.createElement('div');
            loading.className = 'jloading';
        }
        document.body.appendChild(loading);
    }

    obj.hide = function() {
        if (loading) {
            document.body.removeChild(loading);
        }
    }

    return obj;
})();

/**
 * (c) jLogin
 * https://github.com/paulhodel/jtools
 *
 * @author: Paul Hodel <paul.hodel@gmail.com>
 * @description: Login helper
 */

jSuites.login = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        url: window.location.href,
        prepareRequest: null,
        accessToken: null,
        deviceToken: null,
        facebookUrl: null,
        facebookAuthentication: null,
        maxHeight: null,
        onload: null,
        onsuccess: null,
        onerror: null,
        message: null,
        logo: null,
        newProfile: false,
        newProfileUrl: false,
        newProfileLogin: false,
        fullscreen: false,
        newPasswordValidation: null,
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Message console container
    if (! obj.options.message) {
        var messageElement = document.querySelector('.message');
        if (messageElement) {
            obj.options.message = messageElement;
        }
    }

    // Action
    var action = null;

    // Container
    var container = document.createElement('form');
    el.appendChild(container);

    // Logo
    var divLogo = document.createElement('div');
    divLogo.className = 'jlogin-logo'
    container.appendChild(divLogo);

    if (obj.options.logo) {
        var logo = document.createElement('img');
        logo.src = obj.options.logo;
        divLogo.appendChild(logo);
    }

    // Code
    var labelCode = document.createElement('label');
    labelCode.innerHTML = 'Please enter here the code received';
    var inputCode = document.createElement('input');
    inputCode.type = 'number';
    inputCode.id = 'code';
    inputCode.setAttribute('maxlength', 6);
    var divCode = document.createElement('div');
    divCode.appendChild(labelCode);
    divCode.appendChild(inputCode);

    // Hash
    var inputHash = document.createElement('input');
    inputHash.type = 'hidden';
    inputHash.name = 'h';
    var divHash = document.createElement('div');
    divHash.appendChild(inputHash);

    // Recovery
    var inputRecovery = document.createElement('input');
    inputRecovery.type = 'hidden';
    inputRecovery.name = 'recovery';
    inputRecovery.value = '1';
    var divRecovery = document.createElement('div');
    divRecovery.appendChild(inputRecovery);

    // Login
    var labelLogin = document.createElement('label');
    labelLogin.innerHTML = 'Login';
    var inputLogin = document.createElement('input');
    inputLogin.type = 'text';
    inputLogin.name = 'login';
    inputLogin.setAttribute('autocomplete', 'off');
    inputLogin.onkeyup = function() {
        this.value = this.value.toLowerCase().replace(/[^a-zA-Z0-9_+]+/gi, '');
    } 
    var divLogin = document.createElement('div');
    divLogin.appendChild(labelLogin);
    divLogin.appendChild(inputLogin);

    // Name
    var labelName = document.createElement('label');
    labelName.innerHTML = 'Name';
    var inputName = document.createElement('input');
    inputName.type = 'text';
    inputName.name = 'name';
    var divName = document.createElement('div');
    divName.appendChild(labelName);
    divName.appendChild(inputName);

    // Email
    var labelUsername = document.createElement('label');
    labelUsername.innerHTML = 'E-mail';
    var inputUsername = document.createElement('input');
    inputUsername.type = 'text';
    inputUsername.name = 'username';
    inputUsername.setAttribute('autocomplete', 'new-username');
    var divUsername = document.createElement('div');
    divUsername.appendChild(labelUsername);
    divUsername.appendChild(inputUsername);

    // Password
    var labelPassword = document.createElement('label');
    labelPassword.innerHTML = 'New password';
    var inputPassword = document.createElement('input');
    inputPassword.type = 'password';
    inputPassword.name = 'password';
    inputPassword.setAttribute('autocomplete', 'new-password');
    var divPassword = document.createElement('div');
    divPassword.appendChild(labelPassword);
    divPassword.appendChild(inputPassword);
    divPassword.onkeydown = function(e) {
        if (e.keyCode == 13) {
            obj.execute();
        }
    }

    // Repeat password
    var labelRepeatPassword = document.createElement('label');
    labelRepeatPassword.innerHTML = 'Repeat the new password';
    var inputRepeatPassword = document.createElement('input');
    inputRepeatPassword.type = 'password';
    inputRepeatPassword.name = 'password';
    var divRepeatPassword = document.createElement('div');
    divRepeatPassword.appendChild(labelRepeatPassword);
    divRepeatPassword.appendChild(inputRepeatPassword);

    // Remember checkbox
    var labelRemember = document.createElement('label');
    labelRemember.innerHTML = 'Remember me on this device';
    var inputRemember = document.createElement('input');
    inputRemember.type = 'checkbox';
    inputRemember.name = 'remember';
    inputRemember.value = '1';
    labelRemember.appendChild(inputRemember);
    var divRememberButton = document.createElement('div');
    divRememberButton.className = 'rememberButton';
    divRememberButton.appendChild(labelRemember);

    // Login button
    var actionButton = document.createElement('input');
    actionButton.type = 'button';
    actionButton.value = 'Log In';
    actionButton.onclick = function() {
        obj.execute();
    }
    var divActionButton = document.createElement('div');
    divActionButton.appendChild(actionButton);

    // Cancel button
    var cancelButton = document.createElement('div');
    cancelButton.innerHTML = 'Cancel';
    cancelButton.className = 'cancelButton';
    cancelButton.onclick = function() {
        obj.requestAccess();
    }
    var divCancelButton = document.createElement('div');
    divCancelButton.appendChild(cancelButton);

    // Captcha
    var labelCaptcha = document.createElement('label');
    labelCaptcha.innerHTML = 'Please type here the code below';
    var inputCaptcha = document.createElement('input');
    inputCaptcha.type = 'text';
    inputCaptcha.name = 'captcha';
    var imageCaptcha = document.createElement('img');
    var divCaptcha = document.createElement('div');
    divCaptcha.className = 'jlogin-captcha';
    divCaptcha.appendChild(labelCaptcha);
    divCaptcha.appendChild(inputCaptcha);
    divCaptcha.appendChild(imageCaptcha);

    // Facebook
    var facebookButton = document.createElement('div');
    facebookButton.innerHTML = 'Login with Facebook';
    facebookButton.className = 'facebookButton';
    var divFacebookButton = document.createElement('div');
    divFacebookButton.appendChild(facebookButton);
    divFacebookButton.onclick = function() {
        obj.requestLoginViaFacebook();
    }
    // Forgot password
    var inputRequest = document.createElement('span');
    inputRequest.innerHTML = 'Request a new password';
    var divRequestButton = document.createElement('div');
    divRequestButton.className = 'requestButton';
    divRequestButton.appendChild(inputRequest);
    divRequestButton.onclick = function() {
        obj.requestNewPassword();
    }
    // Create a new Profile
    var inputNewProfile = document.createElement('span');
    inputNewProfile.innerHTML = 'Create a new profile';
    var divNewProfileButton = document.createElement('div');
    divNewProfileButton.className = 'newProfileButton';
    divNewProfileButton.appendChild(inputNewProfile);
    divNewProfileButton.onclick = function() {
        obj.newProfile();
    }

    el.className = 'jlogin';

    if (obj.options.fullscreen == true) {
        el.classList.add('jlogin-fullscreen');
    }

    /** 
     * Show message
     */
    obj.showMessage = function(data) {
        var message = (typeof(data) == 'object') ? data.message : data;

        if (typeof(obj.options.showMessage) == 'function') {
            obj.options.showMessage(data);
        } else {
            jSuites.alert(data);
        }
    }

    /**
     * New profile
     */
    obj.newProfile = function() {
        container.innerHTML = '';
        container.appendChild(divLogo);
        if (obj.options.newProfileLogin) {
            container.appendChild(divLogin);
        }
        container.appendChild(divName);
        container.appendChild(divUsername);
        container.appendChild(divActionButton);
        if (obj.options.facebookAuthentication == true) {
            container.appendChild(divFacebookButton);
        }
        container.appendChild(divCancelButton);

        // Reset inputs
        inputLogin.value = '';
        inputUsername.value = '';
        inputPassword.value = '';

        // Button
        actionButton.value = 'Create new profile';

        // Action
        action = 'newProfile';
    }

    /**
     * Request the email with the recovery instructions
     */
    obj.requestNewPassword = function() {
        if (Array.prototype.indexOf.call(container.children, divCaptcha) >= 0) {
            var captcha = true;
        }

        container.innerHTML = '';
        container.appendChild(divLogo);
        container.appendChild(divRecovery);
        container.appendChild(divUsername);
        if (captcha) {
            container.appendChild(divCaptcha);
        }
        container.appendChild(divActionButton);
        container.appendChild(divCancelButton);
        actionButton.value = 'Request a new password';
        inputRecovery.value = 1;

        // Action
        action = 'requestNewPassword';
    }

    /**
     * Confirm recovery code
     */
    obj.codeConfirmation = function() {
        container.innerHTML = '';
        container.appendChild(divLogo);
        container.appendChild(divHash);
        container.appendChild(divCode);
        container.appendChild(divActionButton);
        container.appendChild(divCancelButton);
        actionButton.value = 'Confirm code';
        inputRecovery.value = 2;

        // Action
        action = 'codeConfirmation';
    }

    /**
     * Update my password
     */
    obj.changeMyPassword = function(hash) {
        container.innerHTML = '';
        container.appendChild(divLogo);
        container.appendChild(divHash);
        container.appendChild(divPassword);
        container.appendChild(divRepeatPassword);
        container.appendChild(divActionButton);
        container.appendChild(divCancelButton);
        actionButton.value = 'Change my password';
        inputHash.value = hash;

        // Action
        action = 'changeMyPassword';
    }

    /**
     * Request access default method
     */
    obj.requestAccess = function() {
        container.innerHTML = '';
        container.appendChild(divLogo);
        container.appendChild(divUsername);
        container.appendChild(divPassword);
        container.appendChild(divActionButton);
        if (obj.options.facebookAuthentication == true) {
            container.appendChild(divFacebookButton);
        }
        container.appendChild(divRequestButton);
        container.appendChild(divRememberButton);
        container.appendChild(divRequestButton);
        if (obj.options.newProfile == true) {
            container.appendChild(divNewProfileButton);
        }

        // Button
        actionButton.value = 'Login';

        // Password
        inputPassword.value = '';

        // Email persistence
        if (window.localStorage.getItem('username')) {
            inputUsername.value = window.localStorage.getItem('username');
            inputPassword.focus();
        } else {
            inputUsername.focus();
        }

        // Action
        action = 'requestAccess';
    }

    /**
     * Request login via facebook
     */
    obj.requestLoginViaFacebook = function() {
        if (typeof(deviceNotificationToken) == 'undefined') {
            FB.getLoginStatus(function(response) {
                if (! response.status || response.status != 'connected') {
                    FB.login(function(response) {
                        if (response.authResponse) {
                            obj.execute({ f:response.authResponse.accessToken });
                        } else {
                            obj.showMessage('Not authorized by facebook');
                        }
                    }, {scope: 'public_profile,email'});
                } else {
                    obj.execute({ f:response.authResponse.accessToken });
                }
            }, true);
        } else {
            jDestroy = function() {
                fbLogin.removeEventListener('loadstart', jStart);
                fbLogin.removeEventListener('loaderror', jError);
                fbLogin.removeEventListener('exit', jExit);
                fbLogin.close();
                fbLogin = null;
            }

            jStart = function(event) {
                var url = event.url;
                if (url.indexOf("access_token") >= 0) {
                    setTimeout(function(){
                        var u = url.match(/=(.*?)&/);
                        if (u[1].length > 32) {
                            obj.execute({ f:u[1] });
                        }
                        jDestroy();
                   },500);
                }

                if (url.indexOf("error=access_denied") >= 0) {
                   setTimeout(jDestroy ,500);
                   // Not authorized by facebook
                   obj.showMessage('Not authorized by facebook');
                }
            }

            jError = function(event) {
                jDestroy();
            }
        
            jExit = function(event) {
                jDestroy();
            }

            fbLogin = window.open(obj.options.facebookUrl, "_blank", "location=no,closebuttoncaption=Exit,disallowoverscroll=yes,toolbar=no");
            fbLogin.addEventListener('loadstart', jStart);
            fbLogin.addEventListener('loaderror', jError);
            fbLogin.addEventListener('exit', jExit);
        }

        // Action
        action = 'requestLoginViaFacebook';
    }

    // Perform request
    obj.execute = function(data) {
        // New profile
        if (action == 'newProfile') {
            var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
            if (! inputUsername.value || ! pattern.test(inputUsername.value)) {
                var message = 'Invalid e-mail address'; 
            }

            var pattern = new RegExp(/^[a-zA-Z0-9\_\-\.\s+]+$/);
            if (! inputLogin.value || ! pattern.test(inputLogin.value)) {
                var message = 'Invalid username, please use only characters and numbers';
            }

            if (message) {
                obj.showMessage(message);
                return false;
            }
        } else if (action == 'changeMyPassword') {
            if (inputPassword.value.length < 3) {
                var message = 'Password is too short';
            } else  if (inputPassword.value != inputRepeatPassword.value) {
                var message = 'Password should match';
            } else {
                if (typeof(obj.options.newPasswordValidation) == 'function') {
                    var val = obj.options.newPasswordValidation(obj, inputPassword.value, inputPassword.value);
                    if (val != undefined) {
                        message = val;
                    }
                }
            }

            if (message) {
                obj.showMessage(message);
                return false;
            }
        }

        // Keep email
        if (inputUsername.value != '') {
            window.localStorage.setItem('username', inputUsername.value);
        }

        // Captcha
        if (Array.prototype.indexOf.call(container.children, divCaptcha) >= 0) {
            if (inputCaptcha.value == '') {
                obj.showMessage('Please enter the captch code below');
                return false;
            }
        }

        // Url
        var url = obj.options.url;

        // Device token
        if (obj.options.deviceToken) {
            url += '?token=' + obj.options.deviceToken;
        }

        // Callback
        var onsuccess = function(result) {
            if (result) {
                // Successfully response
                if (result.success == 1) {
                    // Recovery process
                    if (action == 'requestNewPassword') {
                        obj.codeConfirmation();
                    } else if (action == 'codeConfirmation') {
                        obj.requestAccess();
                    } else if (action == 'newProfile') {
                        obj.requestAccess();
                        // New profile
                        result.newProfile = true;
                    }

                    // Token
                    if (result.token) {
                        // Set token
                        obj.options.accessToken = result.token;
                        // Save token
                        window.localStorage.setItem('Access-Token', result.token);
                    }
                }

                // Show message
                if (result.message) {
                    // Show message
                    obj.showMessage(result.message)
                }

                // Request captcha code
                if (! result.data) {
                    if (Array.prototype.indexOf.call(container.children, divCaptcha) >= 0) {
                        divCaptcha.remove();
                    }
                } else {
                    container.insertBefore(divCaptcha, divActionButton);
                    imageCaptcha.setAttribute('src', 'data:image/png;base64,' + result.data);
                }

                // Give time to user see the message
                if (result.hash) {
                    // Change password
                    obj.changeMyPassword(result.hash);
                } else if (result.url) {
                    // App initialization
                    if (result.success == 1) {
                        if (typeof(obj.options.onsuccess) == 'function') {
                            obj.options.onsuccess(result);
                        } else {
                            if (result.message) {
                                setTimeout(function() { window.location.href = result.url; }, 2000);
                            } else {
                                window.location.href = result.url;
                            }
                        }
                    } else {
                        if (typeof(obj.options.onerror) == 'function') {
                            obj.options.onerror(result);
                        }
                    }
                }
            }
        }

        // Password
        if (! data) {
            var data = jSuites.form.getElements(el, true);
            // Encode passworfd
            if (data.password) {
                data.password = jSuites.login.sha512(data.password);
            }
            // Recovery code
            if (Array.prototype.indexOf.call(container.children, divCode) >= 0 && inputCode.value) {
                data.h = jSuites.login.sha512(inputCode.value);
            }
        }

        // Loading
        el.classList.add('jlogin-loading');

        // Url
        var url = (action == 'newProfile' && obj.options.newProfileUrl) ? obj.options.newProfileUrl : obj.options.url;

        // Remote call
        jSuites.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: data,
            success: function(result) {
                // Remove loading
                el.classList.remove('jlogin-loading');
                // Callback
                onsuccess(result);
            },
            error: function(result) {
                // Error
                el.classList.remove('jlogin-loading');

                if (typeof(obj.options.onerror) == 'function') {
                    obj.options.onerror(result);
                }
            }
        });
    }

    var queryString = window.location.href.split('?');
    if (queryString[1] && queryString[1].length == 130 && queryString[1].substr(0,2) == 'h=') {
        obj.changeMyPassword(queryString[1].substr(2));
    } else {
        obj.requestAccess();
    }

    return obj;
});

jSuites.login.sha512 = (function(str) {
    function int64(msint_32, lsint_32) {
        this.highOrder = msint_32;
        this.lowOrder = lsint_32;
    }

    var H = [new int64(0x6a09e667, 0xf3bcc908), new int64(0xbb67ae85, 0x84caa73b),
        new int64(0x3c6ef372, 0xfe94f82b), new int64(0xa54ff53a, 0x5f1d36f1),
        new int64(0x510e527f, 0xade682d1), new int64(0x9b05688c, 0x2b3e6c1f),
        new int64(0x1f83d9ab, 0xfb41bd6b), new int64(0x5be0cd19, 0x137e2179)];

    var K = [new int64(0x428a2f98, 0xd728ae22), new int64(0x71374491, 0x23ef65cd),
        new int64(0xb5c0fbcf, 0xec4d3b2f), new int64(0xe9b5dba5, 0x8189dbbc),
        new int64(0x3956c25b, 0xf348b538), new int64(0x59f111f1, 0xb605d019),
        new int64(0x923f82a4, 0xaf194f9b), new int64(0xab1c5ed5, 0xda6d8118),
        new int64(0xd807aa98, 0xa3030242), new int64(0x12835b01, 0x45706fbe),
        new int64(0x243185be, 0x4ee4b28c), new int64(0x550c7dc3, 0xd5ffb4e2),
        new int64(0x72be5d74, 0xf27b896f), new int64(0x80deb1fe, 0x3b1696b1),
        new int64(0x9bdc06a7, 0x25c71235), new int64(0xc19bf174, 0xcf692694),
        new int64(0xe49b69c1, 0x9ef14ad2), new int64(0xefbe4786, 0x384f25e3),
        new int64(0x0fc19dc6, 0x8b8cd5b5), new int64(0x240ca1cc, 0x77ac9c65),
        new int64(0x2de92c6f, 0x592b0275), new int64(0x4a7484aa, 0x6ea6e483),
        new int64(0x5cb0a9dc, 0xbd41fbd4), new int64(0x76f988da, 0x831153b5),
        new int64(0x983e5152, 0xee66dfab), new int64(0xa831c66d, 0x2db43210),
        new int64(0xb00327c8, 0x98fb213f), new int64(0xbf597fc7, 0xbeef0ee4),
        new int64(0xc6e00bf3, 0x3da88fc2), new int64(0xd5a79147, 0x930aa725),
        new int64(0x06ca6351, 0xe003826f), new int64(0x14292967, 0x0a0e6e70),
        new int64(0x27b70a85, 0x46d22ffc), new int64(0x2e1b2138, 0x5c26c926),
        new int64(0x4d2c6dfc, 0x5ac42aed), new int64(0x53380d13, 0x9d95b3df),
        new int64(0x650a7354, 0x8baf63de), new int64(0x766a0abb, 0x3c77b2a8),
        new int64(0x81c2c92e, 0x47edaee6), new int64(0x92722c85, 0x1482353b),
        new int64(0xa2bfe8a1, 0x4cf10364), new int64(0xa81a664b, 0xbc423001),
        new int64(0xc24b8b70, 0xd0f89791), new int64(0xc76c51a3, 0x0654be30),
        new int64(0xd192e819, 0xd6ef5218), new int64(0xd6990624, 0x5565a910),
        new int64(0xf40e3585, 0x5771202a), new int64(0x106aa070, 0x32bbd1b8),
        new int64(0x19a4c116, 0xb8d2d0c8), new int64(0x1e376c08, 0x5141ab53),
        new int64(0x2748774c, 0xdf8eeb99), new int64(0x34b0bcb5, 0xe19b48a8),
        new int64(0x391c0cb3, 0xc5c95a63), new int64(0x4ed8aa4a, 0xe3418acb),
        new int64(0x5b9cca4f, 0x7763e373), new int64(0x682e6ff3, 0xd6b2b8a3),
        new int64(0x748f82ee, 0x5defb2fc), new int64(0x78a5636f, 0x43172f60),
        new int64(0x84c87814, 0xa1f0ab72), new int64(0x8cc70208, 0x1a6439ec),
        new int64(0x90befffa, 0x23631e28), new int64(0xa4506ceb, 0xde82bde9),
        new int64(0xbef9a3f7, 0xb2c67915), new int64(0xc67178f2, 0xe372532b),
        new int64(0xca273ece, 0xea26619c), new int64(0xd186b8c7, 0x21c0c207),
        new int64(0xeada7dd6, 0xcde0eb1e), new int64(0xf57d4f7f, 0xee6ed178),
        new int64(0x06f067aa, 0x72176fba), new int64(0x0a637dc5, 0xa2c898a6),
        new int64(0x113f9804, 0xbef90dae), new int64(0x1b710b35, 0x131c471b),
        new int64(0x28db77f5, 0x23047d84), new int64(0x32caab7b, 0x40c72493),
        new int64(0x3c9ebe0a, 0x15c9bebc), new int64(0x431d67c4, 0x9c100d4c),
        new int64(0x4cc5d4be, 0xcb3e42b6), new int64(0x597f299c, 0xfc657e2a),
        new int64(0x5fcb6fab, 0x3ad6faec), new int64(0x6c44198c, 0x4a475817)];

    var W = new Array(64);
    var a, b, c, d, e, f, g, h, i, j;
    var T1, T2;
    var charsize = 8;

    function utf8_encode(str) {
        return unescape(encodeURIComponent(str));
    }

    function str2binb(str) {
        var bin = [];
        var mask = (1 << charsize) - 1;
        var len = str.length * charsize;
    
        for (var i = 0; i < len; i += charsize) {
            bin[i >> 5] |= (str.charCodeAt(i / charsize) & mask) << (32 - charsize - (i % 32));
        }
    
        return bin;
    }

    function binb2hex(binarray) {
        var hex_tab = "0123456789abcdef";
        var str = "";
        var length = binarray.length * 4;
        var srcByte;

        for (var i = 0; i < length; i += 1) {
            srcByte = binarray[i >> 2] >> ((3 - (i % 4)) * 8);
            str += hex_tab.charAt((srcByte >> 4) & 0xF) + hex_tab.charAt(srcByte & 0xF);
        }

        return str;
    }

    function safe_add_2(x, y) {
        var lsw, msw, lowOrder, highOrder;

        lsw = (x.lowOrder & 0xFFFF) + (y.lowOrder & 0xFFFF);
        msw = (x.lowOrder >>> 16) + (y.lowOrder >>> 16) + (lsw >>> 16);
        lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

        lsw = (x.highOrder & 0xFFFF) + (y.highOrder & 0xFFFF) + (msw >>> 16);
        msw = (x.highOrder >>> 16) + (y.highOrder >>> 16) + (lsw >>> 16);
        highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

        return new int64(highOrder, lowOrder);
    }

    function safe_add_4(a, b, c, d) {
        var lsw, msw, lowOrder, highOrder;

        lsw = (a.lowOrder & 0xFFFF) + (b.lowOrder & 0xFFFF) + (c.lowOrder & 0xFFFF) + (d.lowOrder & 0xFFFF);
        msw = (a.lowOrder >>> 16) + (b.lowOrder >>> 16) + (c.lowOrder >>> 16) + (d.lowOrder >>> 16) + (lsw >>> 16);
        lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

        lsw = (a.highOrder & 0xFFFF) + (b.highOrder & 0xFFFF) + (c.highOrder & 0xFFFF) + (d.highOrder & 0xFFFF) + (msw >>> 16);
        msw = (a.highOrder >>> 16) + (b.highOrder >>> 16) + (c.highOrder >>> 16) + (d.highOrder >>> 16) + (lsw >>> 16);
        highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

        return new int64(highOrder, lowOrder);
    }

    function safe_add_5(a, b, c, d, e) {
        var lsw, msw, lowOrder, highOrder;

        lsw = (a.lowOrder & 0xFFFF) + (b.lowOrder & 0xFFFF) + (c.lowOrder & 0xFFFF) + (d.lowOrder & 0xFFFF) + (e.lowOrder & 0xFFFF);
        msw = (a.lowOrder >>> 16) + (b.lowOrder >>> 16) + (c.lowOrder >>> 16) + (d.lowOrder >>> 16) + (e.lowOrder >>> 16) + (lsw >>> 16);
        lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

        lsw = (a.highOrder & 0xFFFF) + (b.highOrder & 0xFFFF) + (c.highOrder & 0xFFFF) + (d.highOrder & 0xFFFF) + (e.highOrder & 0xFFFF) + (msw >>> 16);
        msw = (a.highOrder >>> 16) + (b.highOrder >>> 16) + (c.highOrder >>> 16) + (d.highOrder >>> 16) + (e.highOrder >>> 16) + (lsw >>> 16);
        highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

        return new int64(highOrder, lowOrder);
    }

    function maj(x, y, z) {
        return new int64(
            (x.highOrder & y.highOrder) ^ (x.highOrder & z.highOrder) ^ (y.highOrder & z.highOrder),
            (x.lowOrder & y.lowOrder) ^ (x.lowOrder & z.lowOrder) ^ (y.lowOrder & z.lowOrder)
        );
    }

    function ch(x, y, z) {
        return new int64(
            (x.highOrder & y.highOrder) ^ (~x.highOrder & z.highOrder),
            (x.lowOrder & y.lowOrder) ^ (~x.lowOrder & z.lowOrder)
        );
    }

    function rotr(x, n) {
        if (n <= 32) {
            return new int64(
             (x.highOrder >>> n) | (x.lowOrder << (32 - n)),
             (x.lowOrder >>> n) | (x.highOrder << (32 - n))
            );
        } else {
            return new int64(
             (x.lowOrder >>> n) | (x.highOrder << (32 - n)),
             (x.highOrder >>> n) | (x.lowOrder << (32 - n))
            );
        }
    }

    function sigma0(x) {
        var rotr28 = rotr(x, 28);
        var rotr34 = rotr(x, 34);
        var rotr39 = rotr(x, 39);

        return new int64(
            rotr28.highOrder ^ rotr34.highOrder ^ rotr39.highOrder,
            rotr28.lowOrder ^ rotr34.lowOrder ^ rotr39.lowOrder
        );
    }

    function sigma1(x) {
        var rotr14 = rotr(x, 14);
        var rotr18 = rotr(x, 18);
        var rotr41 = rotr(x, 41);

        return new int64(
            rotr14.highOrder ^ rotr18.highOrder ^ rotr41.highOrder,
            rotr14.lowOrder ^ rotr18.lowOrder ^ rotr41.lowOrder
        );
    }

    function gamma0(x) {
        var rotr1 = rotr(x, 1), rotr8 = rotr(x, 8), shr7 = shr(x, 7);

        return new int64(
            rotr1.highOrder ^ rotr8.highOrder ^ shr7.highOrder,
            rotr1.lowOrder ^ rotr8.lowOrder ^ shr7.lowOrder
        );
    }

    function gamma1(x) {
        var rotr19 = rotr(x, 19);
        var rotr61 = rotr(x, 61);
        var shr6 = shr(x, 6);

        return new int64(
            rotr19.highOrder ^ rotr61.highOrder ^ shr6.highOrder,
            rotr19.lowOrder ^ rotr61.lowOrder ^ shr6.lowOrder
        );
    }

    function shr(x, n) {
        if (n <= 32) {
            return new int64(
                x.highOrder >>> n,
                x.lowOrder >>> n | (x.highOrder << (32 - n))
            );
        } else {
            return new int64(
                0,
                x.highOrder << (32 - n)
            );
        }
    }

    var str = utf8_encode(str);
    var strlen = str.length*charsize;
    str = str2binb(str);

    str[strlen >> 5] |= 0x80 << (24 - strlen % 32);
    str[(((strlen + 128) >> 10) << 5) + 31] = strlen;

    for (var i = 0; i < str.length; i += 32) {
        a = H[0];
        b = H[1];
        c = H[2];
        d = H[3];
        e = H[4];
        f = H[5];
        g = H[6];
        h = H[7];

        for (var j = 0; j < 80; j++) {
            if (j < 16) {
                W[j] = new int64(str[j*2 + i], str[j*2 + i + 1]);
            } else {
                W[j] = safe_add_4(gamma1(W[j - 2]), W[j - 7], gamma0(W[j - 15]), W[j - 16]);
            }

            T1 = safe_add_5(h, sigma1(e), ch(e, f, g), K[j], W[j]);
            T2 = safe_add_2(sigma0(a), maj(a, b, c));
            h = g;
            g = f;
            f = e;
            e = safe_add_2(d, T1);
            d = c;
            c = b;
            b = a;
            a = safe_add_2(T1, T2);
        }

        H[0] = safe_add_2(a, H[0]);
        H[1] = safe_add_2(b, H[1]);
        H[2] = safe_add_2(c, H[2]);
        H[3] = safe_add_2(d, H[3]);
        H[4] = safe_add_2(e, H[4]);
        H[5] = safe_add_2(f, H[5]);
        H[6] = safe_add_2(g, H[6]);
        H[7] = safe_add_2(h, H[7]);
    }

    var binarray = [];
    for (var i = 0; i < H.length; i++) {
        binarray.push(H[i].highOrder);
        binarray.push(H[i].lowOrder);
    }

    return binb2hex(binarray);
});

jSuites.mask = (function() {
    var obj = {};
    var index = 0;
    var values = []
    var pieces = [];

    obj.run = function(value, mask, decimal) {
        if (value && mask) {
            if (! decimal) {
                decimal = '.';
            }
            if (value == Number(value)) {
                var number = (''+value).split('.');
                var value = number[0];
                var valueDecimal = number[1];
            } else {
                value = '' + value;
            }
            index = 0;
            values = [];
            // Create mask token
            obj.prepare(mask);
            // Current value
            var currentValue = value;
            if (currentValue) {
                // Checking current value
                for (var i = 0; i < currentValue.length; i++) {
                    if (currentValue[i] != null) {
                        obj.process(currentValue[i]);
                    }
                }
            }
            if (valueDecimal) {
                obj.process(decimal);
                var currentValue = valueDecimal;
                if (currentValue) {
                    // Checking current value
                    for (var i = 0; i < currentValue.length; i++) {
                        if (currentValue[i] != null) {
                            obj.process(currentValue[i]);
                        }
                    }
                }
            }
            // Formatted value
            return values.join('');
        } else {
            return '';
        }
    }

    obj.apply = function(e) {
        if (e.target && ! e.target.getAttribute('readonly')) {
            var mask = e.target.getAttribute('data-mask');
            if (mask) {
                index = 0;
                values = [];
                // Create mask token
                obj.prepare(mask);
                // Current value
                if (e.target.selectionStart < e.target.selectionEnd) {
                    var currentValue = e.target.value.substring(0, e.target.selectionStart); 
                } else {
                    var currentValue = e.target.value;
                }
                if (currentValue) {
                    // Checking current value
                    for (var i = 0; i < currentValue.length; i++) {
                        if (currentValue[i] != null) {
                            obj.process(currentValue[i]);
                        }
                    }
                }
                // New input
                if (e.keyCode > 46) {
                    obj.process(obj.fromKeyCode(e));
                    // Prevent default
                    e.preventDefault();
                }
                // Update value to the element
                e.target.value = values.join('');
                if (pieces.length == values.length && pieces[pieces.length-1].length == values[values.length-1].length) {
                    e.target.setAttribute('data-completed', 'true');
                } else {
                    e.target.setAttribute('data-completed', 'false');
                }
            }
        }
    }

    /**
     * Process inputs and save to values
     */
    obj.process = function(input) {
        do {
            if (pieces[index] == 'mm') {
                if (values[index] == null || values[index] == '') {
                    if (parseInt(input) > 1 && parseInt(input) < 10) {
                        values[index] = '0' + input;
                        index++;
                        return true;
                    } else if (parseInt(input) < 10) {
                        values[index] = input;
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (values[index] == 1 && values[index] < 2 && parseInt(input) < 3) {
                        values[index] += input;
                        index++;
                        return true;
                    } else if (values[index] == 0 && values[index] < 10) {
                        values[index] += input;
                        index++;
                        return true;
                    } else {
                        return false
                    }
                }
            } else if (pieces[index] == 'dd') {
                if (values[index] == null || values[index] == '') {
                    if (parseInt(input) > 3 && parseInt(input) < 10) {
                        values[index] = '0' + input;
                        index++;
                        return true;
                    } else if (parseInt(input) < 10) {
                        values[index] = input;
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (values[index] == 3 && parseInt(input) < 2) {
                        values[index] += input;
                        index++;
                        return true;
                    } else if (values[index] < 3 && parseInt(input) < 10) {
                        values[index] += input;
                        index++;
                        return true;
                    } else {
                        return false
                    }
                }
            } else if (pieces[index] == 'hh24') {
                if (values[index] == null || values[index] == '') {
                    if (parseInt(input) > 2 && parseInt(input) < 10) {
                        values[index] = '0' + input;
                        index++;
                        return true;
                    } else if (parseInt(input) < 10) {
                        values[index] = input;
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (values[index] == 2 && parseInt(input) < 4) {
                        values[index] += input;
                        index++;
                        return true;
                    } else if (values[index] < 2 && parseInt(input) < 10) {
                        values[index] += input;
                        index++;
                        return true;
                    } else {
                        return false
                    }
                }
            } else if (pieces[index] == 'hh') {
                if (values[index] == null || values[index] == '') {
                    if (parseInt(input) > 1 && parseInt(input) < 10) {
                        values[index] = '0' + input;
                        index++;
                        return true;
                    } else if (parseInt(input) < 10) {
                        values[index] = input;
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (values[index] == 1 && parseInt(input) < 3) {
                        values[index] += input;
                        index++;
                        return true;
                    } else if (values[index] < 1 && parseInt(input) < 10) {
                        values[index] += input;
                        index++;
                        return true;
                    } else {
                        return false
                    }
                }
            } else if (pieces[index] == 'mi' || pieces[index] == 'ss') {
                if (values[index] == null || values[index] == '') {
                    if (parseInt(input) > 5 && parseInt(input) < 10) {
                        values[index] = '0' + input;
                        index++;
                        return true;
                    } else if (parseInt(input) < 10) {
                        values[index] = input;
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (parseInt(input) < 10) {
                        values[index] += input;
                        index++;
                        return true;
                     } else {
                        return false
                    }
                }
            } else if (pieces[index] == 'yy' || pieces[index] == 'yyyy') {
                if (parseInt(input) < 10) {
                    if (values[index] == null || values[index] == '') {
                        values[index] = input;
                    } else {
                        values[index] += input;
                    }
                    
                    if (values[index].length == pieces[index].length) {
                        index++;
                    }
                    return true;
                } else {
                    return false;
                }
            } else if (pieces[index] == '#' || pieces[index] == '#.##' || pieces[index] == '#,##' || pieces[index] == '# ##') {
                if (input.match(/[0-9]/g)) {
                    if (pieces[index] == '#.##') {
                        var separator = '.';
                    } else if (pieces[index] == '#,##') {
                        var separator = ',';
                    } else if (pieces[index] == '# ##') {
                        var separator = ' ';
                    } else {
                        var separator = '';
                    }
                    if (values[index] == null || values[index] == '') {
                        values[index] = input;
                    } else {
                        values[index] += input;
                        if (separator) {
                            values[index] = values[index].match(/[0-9]/g).join('');
                            var t = [];
                            var s = 0;
                            for (var j = values[index].length - 1; j >= 0 ; j--) {
                                t.push(values[index][j]);
                                s++;
                                if (! (s % 3)) {
                                    t.push(separator);
                                }
                            }
                            t = t.reverse();
                            values[index] = t.join('');
                            if (values[index].substr(0,1) == separator) {
                                values[index] = values[index].substr(1);
                            } 
                        }
                    }
                    return true;
                } else {
                    if (pieces[index] == '#.##' && input == '.') {
                        // Do nothing
                    } else if (pieces[index] == '#,##' && input == ',') {
                        // Do nothing
                    } else if (pieces[index] == '# ##' && input == ' ') {
                        // Do nothing
                    } else {
                        if (values[index]) {
                            index++;
                            if (pieces[index]) {
                                if (pieces[index] == input) {
                                    values[index] = input;
                                    return true;
                                } else {
                                    if (pieces[index] == '0' && pieces[index+1] == input) {
                                        index++;
                                        values[index] = input;
                                        return true;
                                    }
                                }
                            }
                        }
                    }

                    return false;
                }
            } else if (pieces[index] == '0') {
                if (input.match(/[0-9]/g)) {
                    values[index] = input;
                    index++;
                    return true;
                } else {
                    return false;
                }
            } else if (pieces[index] == 'a') {
                if (input.match(/[a-zA-Z]/g)) {
                    values[index] = input;
                    index++;
                    return true;
                } else {
                    return false;
                }
            } else {
                if (pieces[index] != null) {
                    if (pieces[index] == '\\a') {
                        var v = 'a';
                    } else if (pieces[index] == '\\0') {
                        var v = '0';
                    } else if (pieces[index] == '[-]') {
                        if (input == '-' || input == '+') {
                            var v = input;
                        } else {
                            var v = ' ';
                        }
                    } else {
                        var v = pieces[index];
                    }
                    values[index] = v;
                    if (input == v) {
                        index++;
                        return true;
                    }
                }
            }

            index++;
        } while (pieces[index]);
    }

    /**
     * Create tokens for the mask
     */
    obj.prepare = function(mask) {
        pieces = [];
        for (var i = 0; i < mask.length; i++) {
            if (mask[i].match(/[0-9]|[a-z]|\\/g)) {
                if (mask[i] == 'y' && mask[i+1] == 'y' && mask[i+2] == 'y' && mask[i+3] == 'y') {
                    pieces.push('yyyy');
                    i += 3;
                } else if (mask[i] == 'y' && mask[i+1] == 'y') {
                    pieces.push('yy');
                    i++;
                } else if (mask[i] == 'm' && mask[i+1] == 'm' && mask[i+2] == 'm' && mask[i+3] == 'm') {
                    pieces.push('mmmm');
                    i += 3;
                } else if (mask[i] == 'm' && mask[i+1] == 'm' && mask[i+2] == 'm') {
                    pieces.push('mmm');
                    i += 2;
                } else if (mask[i] == 'm' && mask[i+1] == 'm') {
                    pieces.push('mm');
                    i++;
                } else if (mask[i] == 'd' && mask[i+1] == 'd') {
                    pieces.push('dd');
                    i++;
                } else if (mask[i] == 'h' && mask[i+1] == 'h' && mask[i+2] == '2' && mask[i+3] == '4') {
                    pieces.push('hh24');
                    i += 3;
                } else if (mask[i] == 'h' && mask[i+1] == 'h') {
                    pieces.push('hh');
                    i++;
                } else if (mask[i] == 'm' && mask[i+1] == 'i') {
                    pieces.push('mi');
                    i++;
                } else if (mask[i] == 's' && mask[i+1] == 's') {
                    pieces.push('ss');
                    i++;
                } else if (mask[i] == 'a' && mask[i+1] == 'm') {
                    pieces.push('am');
                    i++;
                } else if (mask[i] == 'p' && mask[i+1] == 'm') {
                    pieces.push('pm');
                    i++;
                } else if (mask[i] == '\\' && mask[i+1] == '0') {
                    pieces.push('\\0');
                    i++;
                } else if (mask[i] == '\\' && mask[i+1] == 'a') {
                    pieces.push('\\a');
                    i++;
                } else {
                    pieces.push(mask[i]);
                }
            } else {
                if (mask[i] == '#' && mask[i+1] == '.' && mask[i+2] == '#' && mask[i+3] == '#') {
                    pieces.push('#.##');
                    i += 3;
                } else if (mask[i] == '#' && mask[i+1] == ',' && mask[i+2] == '#' && mask[i+3] == '#') {
                    pieces.push('#,##');
                    i += 3;
                } else if (mask[i] == '#' && mask[i+1] == ' ' && mask[i+2] == '#' && mask[i+3] == '#') {
                    pieces.push('# ##');
                    i += 3;
                } else if (mask[i] == '[' && mask[i+1] == '-' && mask[i+2] == ']') {
                    pieces.push('[-]');
                    i += 2;
                } else {
                    pieces.push(mask[i]);
                }
            }
        }
    }

    /** 
     * Thanks for the collaboration
     */
    obj.fromKeyCode = function(e) {
        var _to_ascii = {
            '188': '44',
            '109': '45',
            '190': '46',
            '191': '47',
            '192': '96',
            '220': '92',
            '222': '39',
            '221': '93',
            '219': '91',
            '173': '45',
            '187': '61', //IE Key codes
            '186': '59', //IE Key codes
            '189': '45'  //IE Key codes
        }

        var shiftUps = {
            "96": "~",
            "49": "!",
            "50": "@",
            "51": "#",
            "52": "$",
            "53": "%",
            "54": "^",
            "55": "&",
            "56": "*",
            "57": "(",
            "48": ")",
            "45": "_",
            "61": "+",
            "91": "{",
            "93": "}",
            "92": "|",
            "59": ":",
            "39": "\"",
            "44": "<",
            "46": ">",
            "47": "?"
        };

        var c = e.which;

        if (_to_ascii.hasOwnProperty(c)) {
            c = _to_ascii[c];
        }

        if (!e.shiftKey && (c >= 65 && c <= 90)) {
            c = String.fromCharCode(c + 32);
        } else if (e.shiftKey && shiftUps.hasOwnProperty(c)) {
            c = shiftUps[c];
        } else if (96 <= c && c <= 105) {
            c = String.fromCharCode(c - 48);
        } else {
            c = String.fromCharCode(c);
        }

        return c;
    }

    if (typeof document !== 'undefined') {
        document.addEventListener('keydown', function(e) {
            if (jSuites.mask) {
                jSuites.mask.apply(e);
            }
        });
    }

    return obj;
})();


/**
 * (c) jSuites modal
 * https://github.com/paulhodel/jsuites
 *
 * @author: Paul Hodel <paul.hodel@gmail.com>
 * @description: Modal
 */

jSuites.modal = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        url: null,
        onopen: null,
        onclose: null,
        closed: false,
        width: null,
        height: null,
        title: null,
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Title
    if (! obj.options.title && el.getAttribute('title')) {
        obj.options.title = el.getAttribute('title');
    }

    var temp = document.createElement('div');
    for (var i = 0; i < el.children.length; i++) {
        temp.appendChild(el.children[i]);
    }

    obj.content = document.createElement('div');
    obj.content.className = 'jmodal_content';
    obj.content.innerHTML = el.innerHTML;

    for (var i = 0; i < temp.children.length; i++) {
        obj.content.appendChild(temp.children[i]);
    }

    obj.container = document.createElement('div');
    obj.container.className = 'jmodal';
    obj.container.appendChild(obj.content);

    if (obj.options.width) {
        obj.container.style.width = obj.options.width;
    }
    if (obj.options.height) {
        obj.container.style.height = obj.options.height;
    }
    if (obj.options.title) {
        obj.container.setAttribute('title', obj.options.title);
    } else {
        obj.container.classList.add('no-title');
    }
    el.innerHTML = '';
    el.style.display = 'none';
    el.appendChild(obj.container);

    // Backdrop
    var backdrop = document.createElement('div');
    backdrop.className = 'jmodal_backdrop';
    el.appendChild(backdrop);

    obj.open = function() {
        el.style.display = 'block';
        // Fullscreen
        const rect = obj.container.getBoundingClientRect();
        if (jSuites.getWindowWidth() < rect.width) {
            obj.container.style.top = '';
            obj.container.style.left = '';
            obj.container.classList.add('jmodal_fullscreen');
            jSuites.animation.slideBottom(obj.container, 1);
        } else {
            backdrop.style.display = 'block';
        }
        // Current
        jSuites.modal.current = obj;
        // Event
        if (typeof(obj.options.onopen) == 'function') {
            obj.options.onopen(el, obj);
        }
    }

    obj.resetPosition = function() {
        obj.container.style.top = '';
        obj.container.style.left = '';
    }

    obj.isOpen = function() {
        return el.style.display != 'none' ? true : false;
    }

    obj.close = function() {
        el.style.display = 'none';
        // Backdrop
        backdrop.style.display = '';
        // Current
        jSuites.modal.current = null;
        // Remove fullscreen class
        obj.container.classList.remove('jmodal_fullscreen');
        // Event
        if (typeof(obj.options.onclose) == 'function') {
            obj.options.onclose(el, obj);
        }
    }

    if (! jSuites.modal.hasEvents) {
        jSuites.modal.current = obj;

        if ('ontouchstart' in document.documentElement === true) {
            document.addEventListener("touchstart", jSuites.modal.mouseDownControls);
        } else {
            document.addEventListener('mousedown', jSuites.modal.mouseDownControls);
            document.addEventListener('mousemove', jSuites.modal.mouseMoveControls);
            document.addEventListener('mouseup', jSuites.modal.mouseUpControls);
        }

        document.addEventListener('keydown', jSuites.modal.keyDownControls);

        jSuites.modal.hasEvents = true;
    }

    if (obj.options.url) {
        jSuites.ajax({
            url: obj.options.url,
            method: 'GET',
            success: function(data) {
                obj.content.innerHTML = data;

                if (! obj.options.closed) {
                    obj.open();
                }
            }
        });
    } else {
        if (! obj.options.closed) {
            obj.open();
        }
    }

    // Keep object available from the node
    el.modal = obj;

    return obj;
});

jSuites.modal.current = null;
jSuites.modal.position = null;

jSuites.modal.keyDownControls = function(e) {
    if (e.which == 27) {
        if (jSuites.modal.current) {
            jSuites.modal.current.close();
        }
    }
}

jSuites.modal.mouseUpControls = function(e) {
    if (jSuites.modal.current) {
        jSuites.modal.current.container.style.cursor = 'auto';
    }
    jSuites.modal.position = null;
}

jSuites.modal.mouseMoveControls = function(e) {
    if (jSuites.modal.current && jSuites.modal.position) {
        if (e.which == 1 || e.which == 3) {
            var position = jSuites.modal.position;
            jSuites.modal.current.container.style.top = (position[1] + (e.clientY - position[3]) + (position[5] / 2)) + 'px';
            jSuites.modal.current.container.style.left = (position[0] + (e.clientX - position[2]) + (position[4] / 2)) + 'px';
            jSuites.modal.current.container.style.cursor = 'move';
        } else {
            jSuites.modal.current.container.style.cursor = 'auto';
        }
    }
}

jSuites.modal.mouseDownControls = function(e) {
    jSuites.modal.position = [];

    if (e.target.classList.contains('jmodal')) {
        setTimeout(function() {
            // Get target info
            var rect = e.target.getBoundingClientRect();

            if (e.changedTouches && e.changedTouches[0]) {
                var x = e.changedTouches[0].clientX;
                var y = e.changedTouches[0].clientY;
            } else {
                var x = e.clientX;
                var y = e.clientY;
            }

            if (rect.width - (x - rect.left) < 50 && (y - rect.top) < 50) {
                setTimeout(function() {
                    jSuites.modal.current.close();
                }, 100);
            } else {
                if (e.target.getAttribute('title') && (y - rect.top) < 50) {
                    if (document.selection) {
                        document.selection.empty();
                    } else if ( window.getSelection ) {
                        window.getSelection().removeAllRanges();
                    }

                    jSuites.modal.position = [
                        rect.left,
                        rect.top,
                        e.clientX,
                        e.clientY,
                        rect.width,
                        rect.height,
                    ];
                }
            }
        }, 100);
    }
}


jSuites.notification = (function(options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        icon: null,
        name: 'Notification',
        date: null,
        title: null,
        message: null,
        timeout: 4000,
        autoHide: true,
        closeable: true,
    };

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    var notification = document.createElement('div');
    notification.className = 'jnotification';

    var notificationContainer = document.createElement('div');
    notificationContainer.className = 'jnotification-container';
    notification.appendChild(notificationContainer);

    var notificationHeader = document.createElement('div');
    notificationHeader.className = 'jnotification-header';
    notificationContainer.appendChild(notificationHeader);

    var notificationImage = document.createElement('div');
    notificationImage.className = 'jnotification-image';
    notificationHeader.appendChild(notificationImage);

    if (obj.options.icon) {
        var notificationIcon = document.createElement('img');
        notificationIcon.src = obj.options.icon;
        notificationImage.appendChild(notificationIcon);
    }

    var notificationName = document.createElement('div');
    notificationName.className = 'jnotification-name';
    notificationName.innerHTML = obj.options.name;
    notificationHeader.appendChild(notificationName);

    if (obj.options.closeable == true) {
        var notificationClose = document.createElement('div');
        notificationClose.className = 'jnotification-close';
        notificationClose.onclick = function() {
            obj.hide();
        }
        notificationHeader.appendChild(notificationClose);
    }

    var notificationDate = document.createElement('div');
    notificationDate.className = 'jnotification-date';
    notificationHeader.appendChild(notificationDate);

    var notificationContent = document.createElement('div');
    notificationContent.className = 'jnotification-content';
    notificationContainer.appendChild(notificationContent);

    if (obj.options.title) {
        var notificationTitle = document.createElement('div');
        notificationTitle.className = 'jnotification-title';
        notificationTitle.innerHTML = obj.options.title;
        notificationContent.appendChild(notificationTitle);
    }

    var notificationMessage = document.createElement('div');
    notificationMessage.className = 'jnotification-message';
    notificationMessage.innerHTML = obj.options.message;
    notificationContent.appendChild(notificationMessage);

    obj.show = function() {
        document.body.appendChild(notification);
        if (jSuites.getWindowWidth() > 800) { 
            jSuites.animation.fadeIn(notification);
        } else {
            jSuites.animation.slideTop(notification, 1);
        }
    }

    obj.hide = function() {
        if (jSuites.getWindowWidth() > 800) { 
            jSuites.animation.fadeOut(notification, function() {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                    if (notificationTimeout) {
                        clearTimeout(notificationTimeout);
                    }
                }
            });
        } else {
            jSuites.animation.slideTop(notification, 0, function() {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                    if (notificationTimeout) {
                        clearTimeout(notificationTimeout);
                    }
                }
            });
        }
    };

    obj.show();

    if (obj.options.autoHide == true) {
        var notificationTimeout = setTimeout(function() {
            obj.hide();
        }, obj.options.timeout);
    }

    if (jSuites.getWindowWidth() < 800) {
        notification.addEventListener("swipeup", function(e) {
            obj.hide();
            e.preventDefault();
            e.stopPropagation();
        });
    }

    return obj;
});

jSuites.rating = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        number: 5,
        value: 0,
        tooltip: [ 'Very bad', 'Bad', 'Average', 'Good', 'Very good' ],
        onchange: null,
    };

    // Loop through the initial configuration
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Class
    el.classList.add('jrating');

    // Add elements
    for (var i = 0; i < obj.options.number; i++) {
        var div = document.createElement('div');
        div.setAttribute('data-index', (i + 1))
        div.setAttribute('title', obj.options.tooltip[i])
        el.appendChild(div);
    }

    // Set value
    obj.setValue = function(index) {
        for (var i = 0; i < obj.options.number; i++) {
            if (i < index) {
                el.children[i].classList.add('jrating-selected');
            } else {
                el.children[i].classList.remove('jrating-over');
                el.children[i].classList.remove('jrating-selected');
            }
        }

        obj.options.value = index;

        if (typeof(obj.options.onchange) == 'function') {
            obj.options.onchange(el, index);
        }
    }

    obj.getValue = function() {
        return obj.options.value;
    }

    if (obj.options.value) {
        for (var i = 0; i < obj.options.number; i++) {
            if (i < obj.options.value) {
                el.children[i].classList.add('jrating-selected');
            }
        }
    }

    // Events
    el.addEventListener("click", function(e) {
        var index = e.target.getAttribute('data-index');
        if (index != undefined) {
            if (index == obj.options.value) {
                obj.setValue(0);
            } else {
                obj.setValue(index);
            }
        }
    });

    el.addEventListener("mouseover", function(e) {
        var index = e.target.getAttribute('data-index');
        for (var i = 0; i < obj.options.number; i++) {
            if (i < index) {
                el.children[i].classList.add('jrating-over');
            } else {
                el.children[i].classList.remove('jrating-over');
            }
        }
    });

    el.addEventListener("mouseout", function(e) {
        for (var i = 0; i < obj.options.number; i++) {
            el.children[i].classList.remove('jrating-over');
        }
    });

    el.rating = obj;

    return obj;
});


jSuites.refresh = (function(el, options) {
    // Controls
    var touchPosition = null;
    var pushToRefresh = null;

    // Page touch move
    var pageTouchMove = function(e, page) {
        if (typeof(page.options.onpush) == 'function') {
            if (page.scrollTop < 5) {
                if (! touchPosition) {
                    touchPosition = {};
                    touchPosition.x = e.touches[0].clientX;
                    touchPosition.y = e.touches[0].clientY;
                }

                var height = e.touches[0].clientY - touchPosition.y;
                if (height > 70) {
                    if (! pushToRefresh.classList.contains('ready')) {
                        pushToRefresh.classList.add('ready');
                    }
                } else {
                    if (pushToRefresh.classList.contains('ready')) {
                        pushToRefresh.classList.remove('ready');
                    }
                    pushToRefresh.style.height = height + 'px';

                    if (height > 20) {
                        if (! pushToRefresh.classList.contains('holding')) {
                            pushToRefresh.classList.add('holding');
                            page.insertBefore(pushToRefresh, page.firstChild);
                        }
                    }
                }
            }
        }
    }

    // Page touch end
    var pageTouchEnd = function(e, page) {
        if (typeof(page.options.onpush) == 'function') {
            // Remove holding
            pushToRefresh.classList.remove('holding');
            // Refresh or not refresh
            if (! pushToRefresh.classList.contains('ready')) {
                // Reset and not refresh
                pushToRefresh.style.height = '';
                obj.hide();
            } else {
                pushToRefresh.classList.remove('ready');
                // Loading indication
                setTimeout(function() {
                    obj.hide();
                }, 1000);

                // Refresh
                if (typeof(page.options.onpush) == 'function') {
                    page.options.onpush(page);
                }
            }
        }
    }

    var obj = function(element, callback) {
        if (! pushToRefresh) {
            pushToRefresh = document.createElement('div');
            pushToRefresh.className = 'jrefresh';
        }

        element.addEventListener('touchmove', function(e) {
            pageTouchMove(e, element);
        });
        element.addEventListener('touchend', function(e) {
            pageTouchEnd(e, element);
        });
        if (! element.options) {
            element.options = {};
        }
        if (typeof(callback) == 'function') {
            element.options.onpush = callback;
        }
    }

    obj.hide = function() {
        if (pushToRefresh.parentNode) {
            pushToRefresh.parentNode.removeChild(pushToRefresh);
        }
        touchPosition = null;
    }

    return obj;
})();

/**
 * (c) Image slider
 * https://github.com/paulhodel/jtools
 *
 * @author: Paul Hodel <paul.hodel@gmail.com>
 * @description: Image Slider
 */

jSuites.slider = (function(el, options) {
    var obj = {};
    obj.options = {};
    obj.currentImage = null;

    if (options) {
        obj.options = options;
    }

    // Items
    obj.options.items = [];

    if (! el.classList.contains('jslider')) {
        el.classList.add('jslider');

        // Create container
        var container = document.createElement('div');
        container.className = 'jslider-container';

        // Move children inside
        if (el.children.length > 0) {
            // Keep children items
            for (var i = 0; i < el.children.length; i++) {
                obj.options.items.push(el.children[i]);
            }
        }
        if (obj.options.items.length > 0) {
            for (var i = 0; i < obj.options.items.length; i++) {
                obj.options.items[i].classList.add('jfile');
                var index = obj.options.items[i].src.lastIndexOf('/');
                if (index < 0) {
                    obj.options.items[i].setAttribute('data-name', obj.options.items[i].src);
                } else {
                    obj.options.items[i].setAttribute('data-name', obj.options.items[i].src.substr(index + 1));
                }
                var index = obj.options.items[i].src.lastIndexOf('/');

                container.appendChild(obj.options.items[i]);
            }
        }
        el.appendChild(container);
        // Add close buttom
        var close = document.createElement('div');
        close.className = 'jslider-close';
        close.innerHTML = '';
        close.onclick =  function() {
            obj.close();
        }
        el.appendChild(close);
    } else {
        var container = el.querySelector('slider-container');
    }

    obj.show = function(target) {
        if (! target) {
            var target = container.children[0];
        }

        if (! container.classList.contains('jslider-preview')) {
            container.classList.add('jslider-preview');
            close.style.display = 'block';
        }

        // Hide all images
        for (var i = 0; i < container.children.length; i++) {
            container.children[i].style.display = 'none';
        }

        // Show clicked only
        target.style.display = 'block';

        // Is there any previous
        if (target.previousSibling) {
            container.classList.add('jslider-left');
        } else {
            container.classList.remove('jslider-left');
        }

        // Is there any next
        if (target.nextSibling) {
            container.classList.add('jslider-right');
        } else {
            container.classList.remove('jslider-right');
        }

        obj.currentImage = target;
    }

    obj.open = function() {
        obj.show();

        // Event
        if (typeof(obj.options.onopen) == 'function') {
            obj.options.onopen(el);
        }
    }

    obj.close = function() {
        container.classList.remove('jslider-preview');
        container.classList.remove('jslider-left');
        container.classList.remove('jslider-right');

        for (var i = 0; i < container.children.length; i++) {
            container.children[i].style.display = '';
        }

        close.style.display = '';

        obj.currentImage = null;

        // Event
        if (typeof(obj.options.onclose) == 'function') {
            obj.options.onclose(el);
        }
    }

    obj.reset = function() {
        container.innerHTML = '';
    }

    obj.addFile = function(v, ignoreEvents) {
        var img = document.createElement('img');
        img.setAttribute('data-lastmodified', v.lastmodified);
        img.setAttribute('data-name', v.name);
        img.setAttribute('data-size', v.size);
        img.setAttribute('data-extension', v.extension);
        img.setAttribute('data-cover', v.cover);
        img.setAttribute('src', v.file);
        img.className = 'jfile';
        container.appendChild(img);
        obj.options.items.push(img);

        // Onchange
        if (! ignoreEvents) {
            if (typeof(obj.options.onchange) == 'function') {
                obj.options.onchange(el, v);
            }
        }
    }

    obj.addFiles = function(files) {
        for (var i = 0; i < files.length; i++) {
            obj.addFile(files[i]);
        }
    }

    obj.next = function() {
        if (obj.currentImage.nextSibling) {
            obj.show(obj.currentImage.nextSibling);
        }
    }
    
    obj.prev = function() {
        if (obj.currentImage.previousSibling) {
            obj.show(obj.currentImage.previousSibling);
        }
    }

    obj.getData = function() {
        return jSuites.files(container).get();
    }

    // Append data
    if (obj.options.data && obj.options.data.length) {
        for (var i = 0; i < obj.options.data.length; i++) {
            if (obj.options.data[i]) {
                obj.addFile(obj.options.data[i]);
            }
        }
    }

    // Allow insert
    if (obj.options.allowAttachment) {
        var attachmentInput = document.createElement('input');
        attachmentInput.type = 'file';
        attachmentInput.className = 'slider-attachment';
        attachmentInput.setAttribute('accept', 'image/*');
        attachmentInput.style.display = 'none';
        attachmentInput.onchange = function() {
            var reader = [];

            for (var i = 0; i < this.files.length; i++) {
                var type = this.files[i].type.split('/');

                if (type[0] == 'image') {
                    var extension = this.files[i].name;
                    extension = extension.split('.');
                    extension = extension[extension.length-1];

                    var file = {
                        size: this.files[i].size,
                        name: this.files[i].name,
                        extension: extension,
                        cover: 0,
                        lastmodified: this.files[i].lastModified,
                    }

                    reader[i] = new FileReader();
                    reader[i].addEventListener("load", function (e) {
                        file.file = e.target.result;
                        obj.addFile(file);
                    }, false);

                    reader[i].readAsDataURL(this.files[i]);
                } else {
                    alert('The extension is not allowed');
                }
            };
        }

        var attachmentIcon = document.createElement('i');
        attachmentIcon.innerHTML = 'attachment';
        attachmentIcon.className = 'jslider-attach material-icons';
        attachmentIcon.onclick = function() {
            jSuites.click(attachmentInput);
        }

        el.appendChild(attachmentInput);
        el.appendChild(attachmentIcon);
    }

    // Push to refresh
    var longTouchTimer = null;

    var mouseDown = function(e) {
        if (e.target.tagName == 'IMG') {
            // Remove
            var targetImage = e.target;
            longTouchTimer = setTimeout(function() {
                if (e.target.src.substr(0,4) == 'data') {
                    e.target.remove();
                } else {
                    if (e.target.classList.contains('jremove')) {
                        e.target.classList.remove('jremove');
                    } else {
                        e.target.classList.add('jremove');
                    }
                }

                // Onchange
                if (typeof(obj.options.onchange) == 'function') {
                    obj.options.onchange(el, e.target);
                }
            }, 1000);
        }
    }

    var mouseUp = function(e) {
        if (longTouchTimer) {
            clearTimeout(longTouchTimer);
        }

        // Open slider
        if (e.target.tagName == 'IMG') {
            if (! e.target.classList.contains('jremove')) {
                obj.show(e.target);
            }
        } else {
            // Arrow controls
            if (e.target.clientWidth - e.offsetX < 40) {
                // Show next image
                obj.next();
            } else if (e.offsetX < 40) {
                // Show previous image
                obj.prev();
            }
        }
    }

    container.addEventListener('mousedown', mouseDown);
    container.addEventListener('touchstart', mouseDown);
    container.addEventListener('mouseup', mouseUp);
    container.addEventListener('touchend', mouseUp);

    // Add global events
    el.addEventListener("swipeleft", function(e) {
        obj.next();
        e.preventDefault();
        e.stopPropagation();
    });

    el.addEventListener("swiperight", function(e) {
        obj.prev();
        e.preventDefault();
        e.stopPropagation();
    });

    if (! jSuites.slider.hasEvents) {
        document.addEventListener('keydown', function(e) {
            if (e.which == 27) {
                obj.close();
            }
        });

        jSuites.slider.hasEvents = true;
    }

    el.slider = obj;

    return obj;
});

/**
 * (c) jTools v1.0.1 - Element sorting
 * https://github.com/paulhodel/jtools
 *
 * @author: Paul Hodel <paul.hodel@gmail.com>
 * @description: Element drag and drop sorting
 */

jSuites.sorting = (function(el, options) {
    el.classList.add('jsorting');

    el.addEventListener('dragstart', function(e) {
        e.target.classList.add('jsorting_dragging');
    });

    el.addEventListener('dragover', function(e) {
        e.preventDefault();

        if (getElement(e.target) && ! e.target.classList.contains('jsorting')) {
            if (e.target.clientHeight / 2 > e.offsetY) {
                e.path[0].style.borderTop = '1px dotted red';
                e.path[0].style.borderBottom = '';
            } else {
                e.path[0].style.borderTop = '';
                e.path[0].style.borderBottom = '1px dotted red';
            }
        }
    });

    el.addEventListener('dragleave', function(e) {
        e.path[0].style.borderTop = '';
        e.path[0].style.borderBottom = '';
    });

    el.addEventListener('dragend', function(e) {
        e.path[1].querySelector('.jsorting_dragging').classList.remove('jsorting_dragging');
    });

    el.addEventListener('drop', function(e) {
        e.preventDefault();

        if (getElement(e.target) && ! e.target.classList.contains('jsorting')) {
            var element = e.path[1].querySelector('.jsorting_dragging');

            if (e.target.clientHeight / 2 > e.offsetY) {
                e.path[1].insertBefore(element, e.path[0]);
            } else {
                e.path[1].insertBefore(element, e.path[0].nextSibling);
            }
        }

        e.path[0].style.borderTop = '';
        e.path[0].style.borderBottom = '';
    });

    var getElement = function(element) {
        var sorting = false;

        function path (element) {
            if (element.className) {
                if (element.classList.contains('jsorting')) {
                    sorting = true;
                }
            }

            if (! sorting) {
                path(element.parentNode);
            }
        }

        path(element);

        return sorting;
    }

    for (var i = 0; i < el.children.length; i++) {
        el.children[i].setAttribute('draggable', 'true');
    };

    return el;
});

jSuites.tabs = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        data: null,
        allowCreate: false,
        onload: null,
        onchange: null,
        oncreate: null,
        animation: false,
        create: null,
        autoName: false,
        prefixName: '',
        hideHeaders: false,
    };

    // Loop through the initial configuration
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Class
    el.classList.add('jtabs');

    if (obj.options.animation == true) {
        // Border
        var border = document.createElement('div');
        border.className = 'jtabs-border';
        el.appendChild(border);

        var setBorder = function(index) {
            var rect = headers.children[index].getBoundingClientRect();
            var rectContent = content.children[index].getBoundingClientRect();
            border.style.width = rect.width + 'px';
            border.style.left = (rect.left - rectContent.left) + 'px';
            border.style.top = rect.height + 'px';
        }
    }

    // Set value
    obj.open = function(index) {
        for (var i = 0; i < headers.children.length; i++) {
            headers.children[i].classList.remove('jtabs-selected');
            if (content.children[i]) {
                content.children[i].classList.remove('jtabs-selected');
            }
        }

        headers.children[index].classList.add('jtabs-selected');
        if (content.children[index]) {
            content.children[index].classList.add('jtabs-selected');
        }

        // Hide
        if (obj.options.hideHeaders == true && (headers.children.length < 2 && obj.options.allowCreate == false)) {
            headers.style.display = 'none';
        } else {
            headers.style.display = '';
            // Set border
            if (obj.options.animation == true) {
                setTimeout(function() {
                    setBorder(index);
                }, 100);
            }
        }
    }

    obj.selectIndex = function(a) {
        var index = Array.prototype.indexOf.call(headers.children, a);
        if (index >= 0) {
            obj.open(index);
        }
    }

    obj.create = function(title, div) {
        if (typeof(obj.options.create) == 'function') {
            obj.options.create();
        } else {
            obj.appendElement(title, div);

            if (typeof(obj.options.oncreate) == 'function') {
                obj.options.oncreate(el, div)
            }
        }
    }

    obj.appendElement = function(title, div) {
        if (! title) {
            if (obj.options.autoName == true) {
                var title = obj.options.prefixName;
                title += ' ' + (parseInt(content.children.length) + 1);
            } else {
                var title = prompt('Title?', '');
            }
        }

        if (title) {
            // Add headers
            var header = document.createElement('div');
            header.innerHTML = title;
            if (obj.options.allowCreate) {
                headers.insertBefore(header, headers.lastChild);
            } else {
                headers.appendChild(header);
            }

            // Add content
            if (! div) {
                var div = document.createElement('div');
            }
            content.appendChild(div);

            // Open new tab
            obj.selectIndex(header);
        }
    }

    // Create from data
    if (obj.options.data) {
        // Make sure the component is blank
        el.innerHTML = '';
        var headers = document.createElement('div');
        var content = document.createElement('div');
        headers.classList.add('jtabs-headers');
        content.classList.add('jtabs-content');
        el.appendChild(headers);
        el.appendChild(content);

        for (var i = 0; i < obj.options.data.length; i++) {
            var headersItem = document.createElement('div');
            headers.appendChild(headersItem);
            var contentItem = document.createElement('div');
            content.appendChild(contentItem);

            headersItem.innerHTML = obj.options.data[i].title;
            if (obj.options.data[i].content) {
                contentItem.innerHTML = obj.options.data[i].content;
            } else if (obj.options.data[i].url) {
                jSuites.ajax({
                    url: obj.options.data[i].url,
                    type: 'GET',
                    success: function(result) {
                        contentItem.innerHTML = result;
                    },
                    complete: function() {
                        if (typeof(obj.options.onload) == 'function') {
                            obj.options.onload(el);

                            obj.open(0);
                        }
                    }
                });
            }
        }
    } else if (el.children[0] && el.children[1]) {
        // Create from existing elements
        var headers = el.children[0];
        var content = el.children[1];
        headers.classList.add('jtabs-headers');
        content.classList.add('jtabs-content');
    } else {
        el.innerHTML = '';
        var headers = document.createElement('div');
        var content = document.createElement('div');
        headers.classList.add('jtabs-headers');
        content.classList.add('jtabs-content');
        el.appendChild(headers);
        el.appendChild(content);
    }

    // New
    if (obj.options.allowCreate == true) {
        var add = document.createElement('i');
        add.className = 'jtabs-add';
        headers.appendChild(add);
    }

    // Events
    headers.addEventListener("click", function(e) {
        if (e.target.tagName == 'DIV') {
            obj.selectIndex(e.target);
        } else {
            obj.create();
        }
    });

    if (headers.children.length) {
        obj.open(0);
    }

    el.tabs = obj;

    return obj;
});

jSuites.tags = (function(el, options) {
    var obj = {};
    obj.options = {};

    /**
     * @typedef {Object} defaults
     * @property {(string|Array)} value - Initial value of the compontent
     * @property {number} limit - Max number of tags inside the element
     * @property {string} search - The URL for suggestions
     * @property {string} placeholder - The default instruction text on the element
     * @property {validation} validation - Method to validate the tags
     * @property {requestCallback} onbeforechange - Method to be execute before any changes on the element
     * @property {requestCallback} onchange - Method to be execute after any changes on the element
     * @property {requestCallback} onfocus - Method to be execute when on focus
     * @property {requestCallback} onblur - Method to be execute when on blur
     * @property {requestCallback} onload - Method to be execute when the element is loaded
     */
    var defaults = {
        value: null,
        limit: null,
        limitMessage: 'The limit of entries is: ',
        search: null,
        placeholder: null,
        validation: null,
        onbeforechange: null,
        onchange: null,
        onfocus: null,
        onblur: null,
        onload: null,
        colors: null,
    };

    // Loop through though the default configuration
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Search helpers
    var searchContainer = null;
    var searchTerms = null;
    var searchIndex = 0;
    var searchTimer = 0;

    /**
     * Add a new tag to the element
     * @param {(?string|Array)} value - The value of the new element
     */
    obj.add = function(value, focus) {
        if (typeof(obj.options.onbeforechange) == 'function') {
            var v = obj.options.onbeforechange(el, obj, value);
            if (v != null) {
                value = v;
            }
        }

        // Close search
        if (searchContainer) {
            searchContainer.style.display = '';
        }

        if (obj.options.limit > 0 && el.children.length >= obj.options.limit) {
            alert(obj.options.limitMessage + ' ' + obj.options.limit);
        } else {
            // Get node
            var node = getSelectionStart();

            // Mix argument string or array
            if (! value || typeof(value) == 'string') {
                var div = document.createElement('div');
                div.innerHTML = value ? value : '<br>';
                if (node && node.parentNode.classList.contains('jtags')) {
                    el.insertBefore(div, node.nextSibling);
                } else {
                    el.appendChild(div);
                }
            } else {
                if (node && node.parentNode.classList.contains('jtags')) {
                    if (! node.innerText.replace("\n", "")) {
                        el.removeChild(node);
                    }
                }

                for (var i = 0; i <= value.length; i++) {
                    if (! obj.options.limit || el.children.length < obj.options.limit) {
                        var div = document.createElement('div');
                        div.innerHTML = value[i] ? value[i] : '<br>';
                        el.appendChild(div);
                    }
                }
            }

            // Place caret
            if (focus) {
                setTimeout(function() {
                    caret(div);
                }, 0);
            }

            // Filter
            filter();

            if (typeof(obj.options.onchange) == 'function') {
                obj.options.onchange(el, obj, value ? value : '');
            }
        }
    }

    obj.remove = function(node) {
        // Remove node
        node.parentNode.removeChild(node);
        if (! el.children.length) {
            obj.add('');
        }
    }

    /**
     * Get all tags in the element
     * @return {Array} data - All tags as an array
     */
    obj.getData = function() {
        var data = [];
        for (var i = 0; i < el.children.length; i++) {
            // Get value
            var text = el.children[i].innerText.replace("\n", "");
            // Get id
            var value = el.children[i].getAttribute('data-value');
            if (! value) {
                value = text;
            }
            // Item
            if (text || value) {
                data.push({ text: text, value: value });
            }
        }
        return data;
    }

    /**
     * Get the value of one tag. Null for all tags
     * @param {?number} index - Tag index number. Null for all tags.
     * @return {string} value - All tags separated by comma
     */
    obj.getValue = function(index) {
        var value = null;

        if (index != null) {
            // Get one individual value
            value = el.children[index].getAttribute('data-value');
            if (! value) {
                value = el.children[index].innerText.replace("\n", "");
            }
        } else {
            // Get all
            var data = [];
            for (var i = 0; i < el.children.length; i++) {
                value = el.children[i].innerText.replace("\n", "");
                if (value) {
                    data.push(obj.getValue(i));
                }
            }
            value = data.join(',');
        }

        return value;
    }

    /**
     * Set the value of the element based on a string separeted by (,|;|\r\n)
     * @param {string} value - A string with the tags
     */
    obj.setValue = function(text) {
        // Remove whitespaces
        text = text.trim();

        if (text) {
            // Tags
            var data = extractTags(text);
            // Add tags to the element
            obj.add(data);
        }
    }

    obj.reset = function() {
        el.innerHTML = '<div><br></div>';
    }

    /**
     * Verify if all tags in the element are valid
     * @return {boolean}
     */
    obj.isValid = function() {
        var test = 0;
        for (var i = 0; i < el.children.length; i++) {
            if (el.children[i].classList.contains('jtags_error')) {
                test++;
            }
        }
        return test == 0 ? true : false;
    }

    /**
     * Add one element from the suggestions to the element
     * @param {object} item - Node element in the suggestions container
     */ 
    obj.selectIndex = function(item) {
        // Reset terms
        searchTerms = '';
        var node = getSelectionStart();
        // Append text to the caret
        node.innerText = item.children[1].innerText;
        // Set node id
        if (item.children[1].getAttribute('data-value')) {
            node.setAttribute('data-value', item.children[1].getAttribute('data-value'));
        }
        // Close container
        if (searchContainer) {
            searchContainer.style.display = '';
            searchContainer.innerHTML = '';
        }
        // Remove any error
        node.classList.remove('jtags_error');
        // Add new item
        obj.add();
    }

    /**
     * Search for suggestions
     * @param {object} node - Target node for any suggestions
     */
    obj.search = function(node) {
        // Create and append search container to the DOM
        if (! searchContainer) {
            var div = document.createElement('div');
            div.style.position = 'relative';
            el.parentNode.insertBefore(div, el.nextSibling);

            // Create container
            searchContainer = document.createElement('div');
            searchContainer.classList.add('jtags_search');
            div.appendChild(searchContainer);
        }

        // Search for
        var terms = node.anchorNode.nodeValue;

        // Search
        if (node.anchorNode.nodeValue && terms != searchTerms) {
            // Terms
            searchTerms = node.anchorNode.nodeValue;
            // Reset index
            searchIndex = 0;
            // Get remove results
            jSuites.ajax({
                url: obj.options.search + searchTerms,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Reset container
                    searchContainer.innerHTML = '';

                    // Print results
                    if (! data.length) {
                        // Show container
                        searchContainer.style.display = '';
                    } else {
                        // Show container
                        searchContainer.style.display = 'block';

                        // Show items
                        var len = data.length < 11 ? data.length : 10;
                        for (var i = 0; i < len; i++) {
                            // Legacy
                            var text = data[i].text;
                            if (! text && data[i].name) {
                                text = data[i].name;
                            }
                            var value = data[i].value;
                            if (! value && data[i].id) {
                                value = data[i].id;
                            }

                            var div = document.createElement('div');
                            if (i == 0) {
                                div.classList.add('selected');
                            }
                            var img = document.createElement('img');
                            if (data[i].image) {
                                img.src = data[i].image;
                            } else {
                                img.style.display = 'none';
                            }
                            div.appendChild(img);

                            var item = document.createElement('div');
                            item.setAttribute('data-value', value);
                            item.innerHTML = text;
                            div.onclick = function() {
                                // Add item
                                obj.selectIndex(this);
                            }
                            div.appendChild(item);
                            // Append item to the container
                            searchContainer.appendChild(div);
                        }
                    }
                }
            });
        }
    }

    // Destroy tags element
    obj.destroy = function() {
        // Bind events
        el.removeEventListener('mouseup', tagsMouseUp);
        el.removeEventListener('keydown', tagsKeyDown);
        el.removeEventListener('keyup', tagsKeyUp);
        el.removeEventListener('paste', tagsPaste);
        el.removeEventListener('focus', tagsFocus);
        el.removeEventListener('blur', tagsBlur);
        // Remove element
        el.parentNode.removeChild(el);
    }

    var getRandomColor = function(index) {
        var rand = function(min, max) {
            return min + Math.random() * (max - min);
        }
        return 'hsl(' + rand(1, 360) + ',' + rand(40, 70) + '%,' + rand(65, 72) + '%)';
    }

    /**
     * Filter tags
     */
    var filter = function() {
        for (var i = 0; i < el.children.length; i++) {
            // Create label design
            if (! obj.getValue(i)) {
                el.children[i].classList.remove('jtags_label');
            } else {
                el.children[i].classList.add('jtags_label');

                // Validation in place
                if (typeof(obj.options.validation) == 'function') {
                    if (obj.getValue(i)) {
                        if (! obj.options.validation(el.children[i], el.children[i].innerText, el.children[i].getAttribute('data-value'))) {
                            el.children[i].classList.add('jtags_error');
                        } else {
                            el.children[i].classList.remove('jtags_error');
                        }
                    } else {
                        el.children[i].classList.remove('jtags_error');
                    }
                }
            }
        }
    }

    /**
     * Place caret in the element node
     */
    var caret = function(e) {
        var range = document.createRange();
        var sel = window.getSelection();
        range.setStart(e, e.innerText.length);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
    }

    /**
     * Selection
     */
    var getSelectionStart = function() {
        var node = document.getSelection().anchorNode;
        if (node) {
            return (node.nodeType == 3 ? node.parentNode : node);
        } else {
            return null;
        }
    }

    /**
     * Extract tags from a string
     * @param {string} text - Raw string
     * @return {Array} data - Array with extracted tags
     */
    var extractTags = function(text) {
        /** @type {Array} */
        var data = [];

        /** @type {string} */
        var word = '';

        // Remove whitespaces
        text = text.trim();

        if (text) {
            for (var i = 0; i < text.length; i++) {
                if (text[i] == ',' || text[i] == ';' || text[i] == '\r\n') {
                    if (word) {
                        data.push(word);
                        word = '';
                    }
                } else {
                    word += text[i];
                }
            }

            if (word) {
                data.push(word);
            }
        }

        return data;
    }

    /** @type {number} */
    var anchorOffset = 0;

    /**
     * Processing event keydown on the element
     * @param e {object}
     */
    var tagsKeyDown = function(e) {
        // Anchoroffset
        anchorOffset = window.getSelection().anchorOffset;

        // Verify content
        if (! el.children.length) {
            var div = document.createElement('div');
            div.innerHTML = '<br>';
            el.appendChild(div);
        }
        // Comma
        if (e.which == 9 || e.which == 186 || e.which == 188) {
            var n = window.getSelection().anchorOffset;
            if (n > 1) {
                if (! obj.options.limit || el.children.length < obj.options.limit) {
                    obj.add('', true);
                }
            }
            e.preventDefault();
        } else if (e.which == 13) {
            // Enter
            if (searchContainer && searchContainer.style.display != '') {
                obj.selectIndex(searchContainer.children[searchIndex]);
            } else {
                var n = window.getSelection().anchorOffset;
                if (n > 1) {
                    if (! obj.options.limit || el.children.length < obj.options.limit) {
                        obj.add('', true);
                    }
                }
            }
            e.preventDefault();
        } else if (e.which == 38) {
            // Up
            if (searchContainer && searchContainer.style.display != '') {
                searchContainer.children[searchIndex].classList.remove('selected');
                if (searchIndex > 0) {
                    searchIndex--;
                }
                searchContainer.children[searchIndex].classList.add('selected');
                e.preventDefault();
            }
        } else if (e.which == 40) {
            // Down
            if (searchContainer && searchContainer.style.display != '') {
                searchContainer.children[searchIndex].classList.remove('selected');
                if (searchIndex < 9) {
                    searchIndex++;
                }
                searchContainer.children[searchIndex].classList.add('selected');
                e.preventDefault();
            }
        }
    }

    /**
     * Processing event keyup on the element
     * @param e {object}
     */
    var tagsKeyUp = function(e) {
        if (e.which == 39) {
            var n = window.getSelection().anchorOffset;
            if (n > 1 && n == anchorOffset) {
                obj.add('', true);
            }
        } else if (e.which == 13 || e.which == 38 || e.which == 40) {
            e.preventDefault();
        } else {
            if (searchTimer) {
                clearTimeout(searchTimer);
            }

            searchTimer = setTimeout(function() {
                // Current node
                var node = window.getSelection();
                // Search
                if (obj.options.search) {
                    obj.search(node);
                }
                searchTimer = null;
            }, 300);
        }

        filter();
    }

    /**
     * Processing event paste on the element
     * @param e {object}
     */
    var tagsPaste =  function(e) {
        if (e.clipboardData || e.originalEvent.clipboardData) {
            var html = (e.originalEvent || e).clipboardData.getData('text/html');
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
        } else if (window.clipboardData) {
            var html = window.clipboardData.getData('Html');
            var text = window.clipboardData.getData('Text');
        }

        obj.setValue(text);
        e.preventDefault();
    }

    /**
     * Processing event mouseup on the element
     * @param e {object}
     */
    var tagsMouseUp = function(e) {
        if (e.target.parentNode && e.target.parentNode.classList.contains('jtags')) {
            if (e.target.classList.contains('jtags_label') || e.target.classList.contains('jtags_error')) {
                const rect = e.target.getBoundingClientRect();
                if (rect.width - (e.clientX - rect.left) < 16) {
                    obj.remove(e.target);
                }
            }
        }

        if (searchContainer) {
            searchContainer.style.display = '';
        }
    }

    /**
     * Processing event focus on the element
     * @param e {object}
     */
    var tagsFocus = function(e) {
        if (! el.children.length || obj.getValue(el.children.length - 1)) {
            if (! obj.options.limit || el.children.length < obj.options.limit) {
                var div = document.createElement('div');
                div.innerHTML = '<br>';
                el.appendChild(div);
            }
        }

        if (typeof(obj.options.onfocus) == 'function') {
            obj.options.onfocus(el, obj, obj.getValue());
        }
    }

    /**
     * Processing event blur on the element
     * @param e {object}
     */
    var tagsBlur = function(e) {
        if (searchContainer) {
            setTimeout(function() {
                searchContainer.style.display = '';
            }, 200);
        }

        for (var i = 0; i < el.children.length - 1; i++) {
            // Create label design
            if (! obj.getValue(i)) {
                el.removeChild(el.children[i]);
            }
        }

        if (typeof(obj.options.onblur) == 'function') {
            obj.options.onblur(el, obj, obj.getValue());
        }
    }

    // Bind events
    el.addEventListener('mouseup', tagsMouseUp);
    el.addEventListener('keydown', tagsKeyDown);
    el.addEventListener('keyup', tagsKeyUp);
    el.addEventListener('paste', tagsPaste);
    el.addEventListener('focus', tagsFocus);
    el.addEventListener('blur', tagsBlur);

    // Prepare container
    el.classList.add('jtags');
    el.setAttribute('contenteditable', true);
    el.setAttribute('spellcheck', false);

    if (obj.options.placeholder) {
        el.placeholder = obj.options.placeholder;
    }

    // Make sure element is empty
    if (obj.options.value) {
        obj.setValue(obj.options.value);
    } else {
        el.innerHTML = '<div><br></div>';
    }

    if (typeof(obj.options.onload) == 'function') {
        obj.options.onload(el, obj);
    }

    el.tags = obj;

    return obj;
});

/**
 * (c) jSuites template renderer
 * https://github.com/paulhodel/jsuites
 *
 * @author: Paul Hodel <paul.hodel@gmail.com>
 * @description: Template renderer
 */

jSuites.template = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        url: null,
        data: null,
        filter: null,
        pageNumber: 0,
        numberOfPages: 0,
        template: null,
        render: null,
        onload: null,
        onchange: null,
        noRecordsFound: 'No records found',
        // Searchable
        search: null,
        searchInput: true,
        // Search happens in the back end
        searchValue: '',
        remoteData: null,
        // Pagination page number of items
        pagination: null,
    }

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    // Reset content
    el.innerHTML = '';

    // Input search
    if (obj.options.search && obj.options.searchInput == true) {
        // Timer
        var searchTimer = null;

        // Search container
        var searchContainer = document.createElement('div');
        searchContainer.className = 'jtemplate-results';
        var searchInput = document.createElement('input');
        searchInput.value = obj.options.searchValue;
        searchInput.onkeyup = function(e) {
            // Clear current trigger
            if (searchTimer) {
                clearTimeout(searchTimer);
            }
            // Prepare search
            searchTimer = setTimeout(function() {
                obj.search(searchInput.value.toLowerCase());
                searchTimer = null;
            }, 300)
        }
        searchContainer.appendChild(searchInput);
        el.appendChild(searchContainer);
    }

    // Pagination container
    if (obj.options.pagination) {
        var pagination = document.createElement('div');
        pagination.className = 'jtemplate-pagination';
        el.appendChild(pagination);
    }

    // Content
    var container = document.createElement('div');
    container.className = 'jtemplate-content';
    el.appendChild(container);

    // Data container
    var searchResults = null;

    obj.updatePagination = function() {
        // Reset pagination container
        if (pagination) {
            pagination.innerHTML = '';
        }

        // Create pagination
        if (obj.options.pagination > 0 && obj.options.numberOfPages > 1) {
            // Number of pages
            var numberOfPages = obj.options.numberOfPages;

            // Controllers
            if (obj.options.pageNumber < 6) {
                var startNumber = 1;
                var finalNumber = numberOfPages < 10 ? numberOfPages : 10;
            } else if (numberOfPages - obj.options.pageNumber < 5) {
                var startNumber = numberOfPages - 9;
                var finalNumber = numberOfPages;
                if (startNumber < 1) {
                    startNumber = 1;
                }
            } else {
                var startNumber = obj.options.pageNumber - 4;
                var finalNumber = obj.options.pageNumber + 5;
            }

            // First
            if (startNumber > 1) {
                var paginationItem = document.createElement('div');
                paginationItem.innerHTML = '<';
                paginationItem.title = 1;
                pagination.appendChild(paginationItem);
            }

            // Get page links
            for (var i = startNumber; i <= finalNumber; i++) {
                var paginationItem = document.createElement('div');
                paginationItem.innerHTML = i;
                pagination.appendChild(paginationItem);

                if (obj.options.pageNumber == i - 1) {
                    paginationItem.style.fontWeight = 'bold';
                }
            }

            // Last
            if (finalNumber < numberOfPages) {
                var paginationItem = document.createElement('div');
                paginationItem.innerHTML = '>';
                paginationItem.title = numberOfPages - 1;
                pagination.appendChild(paginationItem);
            }
        }
    }

    obj.addItem = function(data, beginOfDataSet) {
        // Append itens
        var content = document.createElement('div');
        // Append data
        if (beginOfDataSet) {
            obj.options.data.unshift(data);
        } else {
            obj.options.data.push(data);
        }
        // Get content
        content.innerHTML = obj.options.template[Object.keys(options.template)[0]](data);
        // Add animation
        content.children[0].classList.add('fade-in');
        // Add and do the animation
        if (beginOfDataSet) {
            container.prepend(content.children[0]);
        } else {
            container.append(content.children[0]);
        }
        // Onchange method
        if (typeof(obj.options.onchange) == 'function') {
            obj.options.onchange(el, obj.options.data);
        }
    }

    obj.removeItem = function(element) {
        if (Array.prototype.indexOf.call(container.children, element) > -1) {
            // Remove data from array
            var index = obj.options.data.indexOf(element.dataReference);
            if (index > -1) {
                obj.options.data.splice(index, 1);
            }
            // Remove element from DOM
            jSuites.animation.fadeOut(element, function() {
                element.parentNode.removeChild(element);
            });
        } else {
            console.error('Element not found');
        }
    }

    obj.setData = function(data) {
        if (data) {
            obj.options.pageNumber = 1;
            obj.options.searchValue = '';
            // Set data
            obj.options.data = data;
            // Reset any search results
            searchResults = null;

            // Render new data
            obj.render();

            // Onchange method
            if (typeof(obj.options.onchange) == 'function') {
                obj.options.onchange(el, obj.options.data);
            }
        }
    }

    obj.appendData = function(data, pageNumber) {
        if (pageNumber) {
            obj.options.pageNumber = pageNumber;
        }

        var execute = function(data) {
            // Concat data
            obj.options.data.concat(data);
            // Number of pages
            if (obj.options.pagination > 0) {
                obj.options.numberOfPages = Math.ceil(obj.options.data.length / obj.options.pagination);
            }
            var startNumber = 0;
            var finalNumber = data.length;
            // Append itens
            var content = document.createElement('div');
            for (var i = startNumber; i < finalNumber; i++) {
                content.innerHTML = obj.options.template[Object.keys(obj.options.template)[0]](data[i]);
                content.children[0].dataReference = data[i]; 
                container.appendChild(content.children[0]);
            }
        }

        if (obj.options.url && obj.options.remoteData == true) {
            // URL
            var url = obj.options.url;
            // Data
            var ajaxData = {};
            // Options for backend search
            if (obj.options.remoteData) {
                // Search value
                if (obj.options.searchValue) {
                    ajaxData.q = obj.options.searchValue;
                }
                // Page number
                if (obj.options.pageNumber) {
                    ajaxData.p = obj.options.pageNumber;
                }
                // Number items per page
                if (obj.options.pagination) {
                    ajaxData.t = obj.options.pagination;
                }
            }
            // Remote loading
            jSuites.ajax({
                url: url,
                method: 'GET',
                data: ajaxData,
                dataType: 'json',
                success: function(data) {
                    execute(data);
                }
            });
        } else {
            if (! obj.options.data) {
                console.log('TEMPLATE: no data or external url defined');
            } else {
                execute(data);
            }
        }
    }

    obj.renderTemplate = function() {
        // Data container
        var data = searchResults ? searchResults : obj.options.data;

        // Reset pagination
        obj.updatePagination();

        if (! data.length) {
            container.innerHTML = obj.options.noRecordsFound;
            container.classList.add('jtemplate-empty');
        } else {
            // Reset content
            container.classList.remove('jtemplate-empty');

            // Create pagination
            if (obj.options.pagination && data.length > obj.options.pagination) {
                var startNumber = (obj.options.pagination * obj.options.pageNumber);
                var finalNumber = (obj.options.pagination * obj.options.pageNumber) + obj.options.pagination;

                if (data.length < finalNumber) {
                    var finalNumber = data.length;
                }
            } else {
                var startNumber = 0;
                var finalNumber = data.length;
            }

            // Append itens
            var content = document.createElement('div');
            for (var i = startNumber; i < finalNumber; i++) {
                content.innerHTML = obj.options.template[Object.keys(obj.options.template)[0]](data[i]);
                content.children[0].dataReference = data[i]; 
                container.appendChild(content.children[0]);
            }
        }
    }

    obj.render = function(pageNumber, forceLoad) {
        // Update page number
        if (pageNumber != undefined) {
            obj.options.pageNumber = pageNumber;
        } else {
            if (! obj.options.pageNumber && obj.options.pagination > 0) {
                obj.options.pageNumber = 0;
            }
        }

        // Render data into template
        var execute = function() {
            // Render new content
            if (typeof(obj.options.render) == 'function') {
                container.innerHTML = obj.options.render(obj);
            } else {
                container.innerHTML = '';
            }

            // Load data
            obj.renderTemplate();

            // Onload
            if (forceLoad && typeof(obj.options.onload) == 'function') {
                obj.options.onload(el, obj);
            }
        }

        if (obj.options.url && (obj.options.remoteData == true || forceLoad)) {
            // URL
            var url = obj.options.url;
            // Data
            var ajaxData = {};
            // Options for backend search
            if (obj.options.remoteData) {
                // Search value
                if (obj.options.searchValue) {
                    ajaxData.q = obj.options.searchValue;
                }
                // Page number
                if (obj.options.pageNumber) {
                    ajaxData.p = obj.options.pageNumber;
                }
                // Number items per page
                if (obj.options.pagination) {
                    ajaxData.t = obj.options.pagination;
                }
            }
            // Remote loading
            jSuites.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                data: ajaxData,
                success: function(data) {
                    // Search and keep data in the client side
                    if (data.hasOwnProperty("total")) {
                        obj.options.numberOfPages = Math.ceil(data.total / obj.options.pagination);
                        obj.options.data = data.data;
                    } else {
                        // Number of pages
                        if (obj.options.pagination > 0) {
                            obj.options.numberOfPages = Math.ceil(data.length / obj.options.pagination);
                        }
                        obj.options.data = data;
                    }

                    // Load data for the user
                    execute();
                }
            });
        } else {
            if (! obj.options.data) {
                console.log('TEMPLATE: no data or external url defined');
            } else {
                // Number of pages
                if (obj.options.pagination > 0) {
                    if (searchResults) {
                        obj.options.numberOfPages = Math.ceil(searchResults.length / obj.options.pagination);
                    } else {
                        obj.options.numberOfPages = Math.ceil(obj.options.data.length / obj.options.pagination);
                    }
                }
                // Load data for the user
                execute();
            }
        }
    }

    obj.search = function(query) {
        obj.options.pageNumber = 0;
        obj.options.searchValue = query ? query : '';

        // Filter data
        if (obj.options.remoteData == true || ! query) {
            searchResults = null;
        } else {
            var test = function(o, query) {
                for (var key in o) {
                    var value = o[key];

                    if ((''+value).toLowerCase().search(query) >= 0) {
                        return true;
                    }
                }
                return false;
            }

            if (typeof(obj.options.filter) == 'function') {
                searchResults = obj.options.filter(obj.options.data, query);
            } else {
                searchResults = obj.options.data.filter(function(item) {
                    return test(item, query);
                });
            }
        }

        obj.render();
    }

    obj.refresh = function() {
        obj.render();
    }

    obj.reload = function() {
        obj.render(0, true);
    }

    el.addEventListener('mousedown', function(e) {
        if (e.target.parentNode.classList.contains('jtemplate-pagination')) {
            var index = e.target.innerText;
            if (index == '<') {
                obj.render(0);
            } else if (index == '>') {
                obj.render(e.target.getAttribute('title'));
            } else {
                obj.render(parseInt(index)-1);
            }
            e.preventDefault();
        }
    });

    el.template = obj;

    // Render data
    obj.render(0, true);

    return obj;
});

jSuites.toolbar = (function(el, options) {
    var obj = {};
    obj.options = {};

    // Default configuration
    var defaults = {
        app: null,
        badge: false,
        title: false,
        items: [],
    }

    // Loop through our object
    for (var property in defaults) {
        if (options && options.hasOwnProperty(property)) {
            obj.options[property] = options[property];
        } else {
            obj.options[property] = defaults[property];
        }
    }

    if (! el && options.app && options.app.el) {
        el = document.createElement('div');
        options.app.el.appendChild(el);
    }
    obj.selectItem = function(element) {
        var elements = toolbarContent.children;
        for (var i = 0; i < elements.length; i++) {
            if (element != elements[i]) {
                elements[i].classList.remove('jtoolbar-selected');
            }
        }
        element.classList.add('jtoolbar-selected');
    }

    obj.hide = function() {
        jSuites.animation.slideBottom(el, 0, function() {
            el.style.display = 'none';
        });
    }

    obj.show = function() {
        el.style.display = '';
        jSuites.animation.slideBottom(el, 1);
    }

    obj.get = function() {
        return el;
    }

    obj.setBadge = function(index, value) {
        toolbarContent.children[index].children[1].firstChild.innerHTML = value;
    }

    obj.destroy = function() {
        toolbar.remove();
        el.innerHTML = '';
    }

    obj.create = function(items) {
        // Reset anything in the toolbar
        toolbarContent.innerHTML = '';
        // Create elements in the toolbar
        for (var i = 0; i < items.length; i++) {
            var toolbarItem = document.createElement('div');
            toolbarItem.classList.add('jtoolbar-item');

            if (items[i].width) {
                toolbarItem.style.width = parseInt(items[i].width) + 'px'; 
            }

            if (items[i].k) {
                toolbarItem.k = items[i].k;
            }

            if (items[i].tooltip) {
                toolbarItem.setAttribute('title', toolbar[i].tooltip);
            }

            if (! items[i].type || items[i].type == 'i') {
                // Material icons
                var toolbarIcon = document.createElement('i');
                toolbarIcon.classList.add('material-icons');
                toolbarIcon.innerHTML = items[i].content ? items[i].content : '';
                toolbarItem.appendChild(toolbarIcon);

                // Badge options
                if (obj.options.badge == true) {
                    var toolbarBadge = document.createElement('div');
                    toolbarBadge.classList.add('jbadge');
                    var toolbarBadgeContent = document.createElement('div');
                    toolbarBadgeContent.innerHTML = items[i].badge ? items[i].badge : '';
                    toolbarBadge.appendChild(toolbarBadgeContent);
                    toolbarItem.appendChild(toolbarBadge);
                }

                // Title
                if (items[i].title) {
                    if (obj.options.title == true) {
                        var toolbarTitle = document.createElement('span');
                        toolbarTitle.innerHTML = items[i].title;
                        toolbarItem.appendChild(toolbarTitle);
                    } else {
                        toolbarItem.setProperty('title', items[i].title);
                    }
                }

                if (obj.options.app && items[i].route) {
                    // Route
                    toolbarItem.route = items[i].route;
                    // Onclick for route
                    toolbarItem.onclick = function() {
                        options.app.pages(this.route);
                    }

                    // Create pages
                    obj.options.app.pages(items[i].route, {
                        toolbarItem: toolbarItem,
                        closed: true,
                        onenter: function() {
                            obj.selectItem(this.toolbarItem);
                        }
                    });
                } else if (items[i].onclick) {
                    toolbarItem.onclick = (function (a) {
                        var b = a;
                        return function () {
                            items[b].onclick(el, obj, this);
                        };
                    })(i);
                }
            } else if (items[i].type == 'select' || items[i].type == 'dropdown') {
                if (items[i].options) {
                    toolbarItem.classList.add('jtoolbar-dropdown');
                    toolbarItem.setAttribute('tabindex', '0');
                    toolbarItem.onblur = function() {
                        this.classList.remove('jtoolbar-focus');
                    }

                    // Dropdown header
                    if (items[i].content) {
                        var dropdown = document.createElement('div');
                        dropdown.innerHTML = '<i class="material-icons">' + items[i].content + '</i>';
                    } else {
                        var dropdown = document.createElement('div');
                        dropdown.innerHTML = '';
                    }
                    dropdown.classList.add('jtoolbar-dropdown-header');
                    dropdown.onclick = function(e) {
                        if (this.parentNode.classList.contains('jtoolbar-focus')) {
                            this.parentNode.classList.remove('jtoolbar-focus');
                        } else {
                            var e = this.parentNode.parentNode.querySelectorAll('.jtoolbar-item');
                            for (var j = 0; j < e.length; j++) {
                                e[j].classList.remove('jtoolbar-focus');
                            }

                            this.parentNode.classList.add('jtoolbar-focus');

                            const rectHeader = this.getBoundingClientRect();
                            const rectContent = this.nextSibling.getBoundingClientRect();

                            if (window.innerHeight < rectHeader.bottom + rectContent.height) {
                                this.nextSibling.style.top = '';
                                this.nextSibling.style.bottom = rectHeader.height + 1 + 'px';
                            } else {
                                this.nextSibling.style.top = '';
                                this.nextSibling.style.bottom = '';
                            }
                        }
                    }

                    // Dropdown
                    var dropdownContent = document.createElement('div');
                    dropdownContent.classList.add('jtoolbar-dropdown-content');
                    toolbarItem.appendChild(dropdown);
                    toolbarItem.appendChild(dropdownContent);

                    for (var j = 0; j < items[i].options.length; j++) {
                        var dropdownItem = document.createElement('div');
                        if (typeof(items[i].render) == 'function') {
                            var value = items[i].render(items[i].options[j]);
                        } else {
                            var value = items[i].options[j];
                        }
                        dropdownItem.p = toolbarItem;
                        dropdownItem.k = j;
                        dropdownItem.v = items[i].options[j];
                        dropdownItem.innerHTML = value;
                        dropdownItem.onchange = items[i].onchange;
                        if (items[i].content) {
                            dropdownItem.onclick = function() {
                                this.onchange(el, obj, this.p, this.v, this.k);
                                this.p.classList.remove('jtoolbar-focus');
                            }
                        } else {
                            dropdownItem.onclick = function() {
                                this.parentNode.parentNode.firstChild.innerHTML = this.innerHTML;
                                this.onchange(el, obj, this.p, this.v, this.k);
                                this.p.classList.remove('jtoolbar-focus');
                            }
                        }
                        dropdownContent.appendChild(dropdownItem);

                        if (! items[i].content && j == 0) {
                            dropdown.innerHTML = value;
                        }
                    }
                }

                if (items[i].onclick) {
                    toolbarItem.onclick = (function (a) {
                        var b = a;
                        return function () {
                            items[b].onclick(el, obj, this);
                        };
                    })(i);
                }
            } else if (items[i].type == 'color') {
                toolbarItem
            } else if (items[i].type == 'divisor') {
                toolbarItem.classList.add('jtoolbar-divisor');
            }

            toolbarContent.appendChild(toolbarItem);
        }
    }

    el.classList.add('jtoolbar');
    el.onclick = function(e) {
        var element = jSuites.findElement(e.target, 'jtoolbar-item');
        if (element) {
            obj.selectItem(element);
        }
    }

    var toolbarContent = document.createElement('div');
    el.appendChild(toolbarContent);

    if (obj.options.app) {
        el.classList.add('jtoolbar-mobile');
    }

    obj.create(obj.options.items);

    el.toolbar = obj;

    return obj;
});



    return jSuites;

})));