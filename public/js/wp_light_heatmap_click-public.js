jQuery( document ).ready(
	function() {
		var wait = false;
		var prevUnixTime = 0;

		try {
			var sleepTime = Number( lightHeatmapOptionsArray['wp_light_heatmap_requests_interval'] );
		} catch (e) {
			var sleepTime = 5;
		}

		try {
			var trackClick = Boolean( lightHeatmapOptionsArray['wp_light_heatmap_track_click'] );
		} catch (e) {
			var trackClick = false;
		}

		try {
			var trackMove = Boolean( lightHeatmapOptionsArray['wp_light_heatmap_track_move'] );
		} catch (e) {
			var trackMove = false;
		}

		function generateRandomBoundary(length) {
			var boundary;
			var chars = '0123456789abcdefghijklmnopqrstuvwxyz';
			if (window.crypto && window.crypto.getRandomValues && Uint8Array) {
				boundary = new Uint8Array( length );
				window.crypto.getRandomValues( boundary );
				boundary = boundary
				.toString()
				.split( ',' )
				.map(
					function(value) {
						return chars[value % chars.length]
					}
				)
				.join( '' );
			} else {
				boundary = '';
				while (boundary.length < length) {
					boundary += chars[Math.floor( Math.random() * chars.length )];
				}
			}
			return boundary.slice( 0, length );
		}

		if ( ! Cookies.get( "wp_light_heatmap_id" ) ) {
			Cookies.set( "wp_light_heatmap_id", generateRandomBoundary( 32 ) );
		}

		jQuery( 'body' ).on(
			'mousemove mousedown',
			function(event) {
				// Use mousedown event to prevent link-following suppression
				if ( event.type === 'mousedown' && trackClick == true ) {
					sendStatus = sendAnalytics( event );
					return sendStatus;
				}
				if ( wait == false && event.type === 'mousemove' && trackMove == true ) {
					sendStatus = sendAnalytics( event );
					wait       = true;
					setTimeout( function() { wait = false; }, sleepTime * 1000 );
					return sendStatus;
				}

				function sendAnalytics() {
					// We sending requests not often than 1 req per 0.1 secs to prevent click flooding
					curUnixTime = Date.now();
					if ( curUnixTime - prevUnixTime < 100 ) {
						return false;
					}
					prevUnixTime = curUnixTime;

					// Pause the normal function in case this is a link
					event.preventDefault();

					// Make sure we aren't clicking the Heatmap functionality
					if ( (event.target.className == 'heatmap-bar-off')
					|| (event.target.className == 'heatmap-bar-on')
					|| (event.target.className == 'heatmap-bar-span')
					) {
						return false;
					}

					// Check if the overlay is on
					if ( jQuery( '.heatmap-overlay' ).length ) {
						return false;
					}
					// Function for reversing array
					jQuery.fn.reverse = [].reverse;

					// Find the current url
					current_url = document.location.href;

					// Wrap the page in a div
					// jQuery('body').children().wrapAll('<div class="wp_light_heatmap"></div');
					// Find the x and y coordinates of the click offset to the target node
					x_coord = event.pageX - jQuery( event.target ).offset().left;
					y_coord = event.pageY - jQuery( event.target ).offset().top;

					// Find the attributes of this node
					// nodeName
					nodeName = event.target.nodeName.toLowerCase();

					// nodeClass
					if (event.target.className.length > 0) {
						nodeClass = event.target.className.slice( -1 ) === " " ? event.target.className.slice( 0, -1 ).split( " " ).join( "." ) : event.target.className.split( " " ).join( "." );
					} else {
						nodeClass = ""; }

					// nodeID
					if (typeof jQuery( event.target ).attr( 'id' ) != 'undefined') {
						nodeID = jQuery( event.target ).attr( 'id' ).split( " " ).join( "#" );
					} else {
						nodeID = ""; }

					// nodeIndex
					nodeIndex = jQuery( event.target ).prevAll( nodeName ).length;

					// fullNode
					if ( nodeClass.length > 0) {
						fullNode = nodeName + '.' + nodeClass + ':eq(' + nodeIndex + ')';
					} else if ( nodeID.length > 0 ) {
						fullNode = nodeName + '#' + nodeID + ':eq(' + nodeIndex + ')';
					} else {
						fullNode = nodeName + ':eq(' + nodeIndex + ')';
					}

					jQuery_nodeParents = jQuery( event.target ).parents()
					.map(
						function () {
							// Get the index of this node
							parentIndex = jQuery( this ).prevAll( this.nodeName ).length;

							if (this.className.length > 0 ) {
								connectedClasses = this.className.slice( -1 ) === " " ? this.className.slice( 0, -1 ).split( " " ).join( "." ) : this.className.split( " " ).join( "." );
								parentIndex      = jQuery( this ).prevAll( this.nodeName + '.' + connectedClasses ).length;
								return (this.nodeName + '.' + connectedClasses + ':eq(' + parentIndex + ')');
							} else if (typeof jQuery( this ).attr( 'id' ) != 'undefined') {
								connectedIDs = jQuery( this ).attr( 'id' ).split( " " ).join( "#" );
								parentIndex  = jQuery( this ).prevAll( this.nodeName + '#' + connectedIDs ).length;
								return (this.nodeName + '#' + connectedIDs + ':eq(' + parentIndex + ')');
							} else {
								return this.nodeName + ':eq(' + parentIndex + ')';
							}
						}
					).get().reverse().join( " > " ).toLowerCase();

					// Append this node name onto its parents
					jQuerySelector = jQuery_nodeParents + ' > ' + fullNode;

					var boundary  = generateRandomBoundary( 56 );
					var endline   = '\r\n';
					var separator = '--';
					var id_cookie = Cookies.get( "wp_light_heatmap_id" );
					var data      = {
						// wp_ajax_nopriv_wp_light_heatmap_add_dot and wp_ajax_wp_light_heatmap_add_dot
						action      	: 'wp_light_heatmap_add_dot',
						x_coord    		: x_coord,
						y_coord     	: y_coord,
						timestamp   	: curUnixTime,
						heatmap_user_id : id_cookie,
						selector    	: jQuerySelector,
						current_url 	: current_url
					};

					var body = ['']
					for (let [key, value] of Object.entries( data )) {
						body.push( 'Content-Disposition: form-data; name="' + key + '"' + endline + endline + value + endline );
					}
					body = body.join( separator + boundary + endline ) + separator + boundary + separator + endline;

					jQuery.ajax(
						{
							url: MyAjax.displayurl,
							type: "POST",
							data: body,
							contentType: "multipart/form-data; boundary=" + boundary,
						}
					);
				}
			}
		);

	}
);
