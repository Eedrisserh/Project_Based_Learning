/** WPLP Custom plugin for TinyME Editor v.0.1 **/
(function($){
	var wplp_select_open = false;
	
    tinymce.create('tinymce.plugins.wplp', {
    	
    	init : function(ed, url) {
    		var t = this;
    		
    		t.url = url;
    		t.editor = ed;
    		
    		ed.onBeforeSetContent.add(function(ed, o) {
    			o.content = t._do_wplp(o.content);
    		});
    		
        	ed.onPostProcess.add(function(ed, o) {
        		if (o.get)
        			o.content = t._get_wplp(o.content);
        	});
    	},
    	
    	_do_wplp : function(co) {
    		return co.replace(/\[frontpage_news([^\]]*)\]/g, function(a,b){
    			return '<img src="'+tinymce.baseURL+'/plugins/wpgallery/img/t.gif" class="wplped mceItem" title="frontpage_news'+tinymce.DOM.encode(b)+'" />';
    		});
    	},
    	
        _get_wplp : function(co) {
        	function getAttr(s, n) {
        		n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
        		return n ? tinymce.DOM.decode(n[1]) : '';
        	};
        	return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(a,im) {
        		var cls = getAttr(im, 'class');
        		if ( cls.indexOf('wplped') != -1 )
        			return '<p>['+tinymce.trim(getAttr(im, 'title'))+']</p>';
        		return a;
        	});
        },

        createControl : function(id, controlManager) {
            if (id == 'wplp_button') {
                var button = controlManager.createButton('wplp_button', {
                    title : 'Insert WP Latest Posts shortcode', // title of the button
                    image : '../wp-content/plugins/wp-frontpage-news/img/wplp_tmce_icon.png',  // path to the button's image
                    onclick : function() {
                    	
                    	if( wplp_select_open ) {
                    		$('#wplp_widgetlist').hide('slide', function() { $('#wplp_widgetlist').remove() });

                    		wplp_select_open = false;
                    		return true;
                    	}
                    	
                    	wplp_select_open = true;
                    	
                    	var html = '<div id="wplp_widgetlist">' +
                    		'<select id="wplp_widget_select" size="7">';
                    	$.each(wplp_widgets, function( index, value ){
                    		if( 'undefined' !== typeof value )
                    			html = html + '<option value="' + index + '">' + value + '</option>';
                    	});
                    	html = html + '</select>' +
                    		'</div>';
                    	
                		var select = $( html );
                    	select.appendTo($('div#content_toolbargroup').parent()).hide().show( 'slide' );
                    	
                    	select.on( 'change', function(e){
                        	insertShortcode( $('option:selected', this).val(), $('option:selected', this).text() );
                        	$(this).hide('slide', function() { $('#wplp_widgetlist').remove() });
                        	wplp_select_open = false;
                        });
                    	
                    	return false;
                    }
                });
                return button;
            }
            return null;
        }
    });
 
    /** Registers the plugin. **/
    tinymce.PluginManager.add('wplp', tinymce.plugins.wplp);
    
    function insertShortcode( widget_id, widget_title ) {
    	var shortcode = '[frontpage_news';
    	if( null != widget_id )
    		shortcode += ' widget="' + widget_id + '"';
    	if( null != widget_title )
    		shortcode += ' name="' + widget_title + '"';
    	shortcode += ']';
    	
    	/** Inserts the shortcode into the active editor and reloads display **/
    	var ed = tinyMCE.activeEditor;
		ed.execCommand('mceInsertContent', 0, shortcode);            			
		setTimeout(function() { ed.hide(); }, 1);
	    setTimeout(function() { ed.show(); }, 10);
    }
})( jQuery );
