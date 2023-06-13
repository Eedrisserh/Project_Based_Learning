window.anwpFLVideos = {};
( function( window, $, app ) {

	// Constructor.
	app.init = function() {
		app.cache();
		app.bindEvents();
	};

	// Cache document elements.
	app.cache = function() {
		app.$c = {
			body: $( document.body ),
			window: $( window ),
			ytPlayer: null,
			ytIFramePlayer: null
		};
	};

	// Combine all events.
	app.bindEvents = function() {
		if ( document.readyState !== 'loading' ) {
			app.documentLoaded();
		} else {
			document.addEventListener( 'DOMContentLoaded', app.documentLoaded );
		}
	};

	app.documentLoaded = function() {

		if ( ! app.$c.body.find( '.anwp-fl-yt-video' ).length && ! app.$c.body.find( '#anwp-fl-iframe-yt-match-video' ).length ) {
			return false;
		}

		app.createAPIScript();

		if ( 'undefined' === typeof window.onYouTubeIframeAPIReady ) {
			window.onYouTubeIframeAPIReady = app.onPlayerReady;
		} else if ( 'undefined' !== typeof window.YT ) {
			app.onPlayerReady();
		} else if ( 'function' === typeof window.onYouTubeIframeAPIReady ) {

			var previous = window.onYouTubeIframeAPIReady;

			window.onYouTubeIframeAPIReady = function() {
				if ( previous ) {
					previous();
				}

				app.onPlayerReady();
			};
		}
	};

	app.createAPIScript = function() {
		var youtubeScriptId = 'anwp-fl-youtube-api';
		var youtubeScript   = document.getElementById( youtubeScriptId );

		if ( null === youtubeScript ) {
			var tag         = document.createElement( 'script' );
			var firstScript = document.getElementsByTagName( 'script' )[ 0 ];

			tag.src = 'https://www.youtube.com/iframe_api';
			tag.id  = youtubeScriptId;
			firstScript.parentNode.insertBefore( tag, firstScript );
		}
	};

	app.onPlayerReady = function() {
		app.initGameVideoPlayer();
		app.initListVideoPlayer();
	};

	app.initGameVideoPlayer = function() {
		var $player = $( '#anwp-fl-iframe-yt-match-video' );

		if ( ! $player.length ) {
			return false;
		}

		app.$c.ytIFramePlayer = new YT.Player( 'anwp-fl-iframe-yt-match-video', {
			height: '',
			width: '100%',
			videoId: $player.data( 'video' ),
			host: 'https://www.youtube-nocookie.com',
			playerVars: {origin: $player.data( 'origin' ), enablejsapi: 1}
		} );
	};

	app.initListVideoPlayer = function() {
		var $videos = app.$c.body.find( '.anwp-fl-yt-video' );

		if ( ! $videos.length ) {
			return false;
		}

		app.$c.body.append( '<div id="anwp-v-videos-yt-modal"><div id="anwp-v-yt-player"></div></div>' );

		$videos.modaal( {
			custom_class: 'anwp-v-videos-yt-modal',
			type: 'video',
			content_source: '#anwp-v-videos-yt-modal',
			before_open: function( e ) {

				if ( ! $( '#anwp-v-videos-yt-modal' ).find( '#anwp-v-yt-player' ).length ) {
					$( '#anwp-v-videos-yt-modal' ).append( '<div id="anwp-v-yt-player"></div>' );
				}

				if ( app.$c.ytIFramePlayer ) {
					app.$c.ytIFramePlayer.pauseVideo();
				}

				app.$c.ytPlayer = new YT.Player( 'anwp-v-yt-player', {
					width: '100%',
					videoId: $( e.target ).data( 'video' ),
					host: 'https://www.youtube-nocookie.com',
					events: {
						'onReady': app.playPlayer
					}
				} );
			},
			after_close: function() {
				app.$c.ytPlayer.destroy();
			}
		} );
	};

	app.playPlayer = function( event ) {
		event.target.playVideo();
	};

	// Engage!
	app.init();
}( window, jQuery, window.anwpFLVideos ) );
