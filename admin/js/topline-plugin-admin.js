var map;

(function( $ ) {
	'use strict';

	$(function() {

		if($('#properties-table').length) {
		  var propertiesTable = $('#properties-table').dataTable();
		}
		if($('#floorplans-table').length) {
		  var floorplansTable = $('#floorplans-table').dataTable();
		}
		$('.subview-icons').on('click', 'a', function(e) {
			$(this).hasClass('list-view');
			
			e.preventDefault();
		});
		$('.floorplans-grid-view').hide();
		$('.grid-view').on('click', function(e) {
			$('.subview-icons .active').removeClass('active');
			$(this).addClass('active');
			$('.floorplans-list-view').hide();
			$('.floorplans-grid-view').fadeIn('250');
			e.preventDefault;
		});
		$('.list-view').on('click', function(e) {
			$('.subview-icons .active').removeClass('active');
			$(this).addClass('active');
			$('.floorplans-list-view').fadeIn();
			$('.floorplans-grid-view').hide();
			e.preventDefault;
		});

		$('.topline-edit-link').on('click', function(e){
			var $toggle = $(this);

			var $toggleOn = $toggle.data('toggle-on');
			var $toggleOff = $toggle.data('toggle-off');

			$('.' + $toggleOff).hide();
			$('.' + $toggleOn).fadeIn();

			/** swap the toggles now **/
			$toggle.data('toggle-on', $toggleOff);
			$toggle.data('toggle-off', $toggleOn);

			e.preventDefault();
		});


		/**
		 * Property.js
		 */
		
		if($('#map').length) {
			// Provide your access token
			L.mapbox.accessToken = 'pk.eyJ1IjoiZXJpY25rYXR6IiwiYSI6Ii02WG15NmcifQ.kt0olME7-cN3yfk-qxi59g';
			// Create a map in the div #map
			map = L.mapbox.map('map', 'ericnkatz.jpkjjm17', { zoomControl: false, attributionControl: false }).setZoom(15);

			// Disable drag and zoom handlers.
			map.dragging.disable();
			map.touchZoom.disable();
			map.doubleClickZoom.disable();
			map.scrollWheelZoom.disable();

			// Disable tap handler, if present.
			if (map.tap) map.tap.disable();

			if (typeof maplocation !== 'undefined') {
				console.log(maplocation);
				map.setView([maplocation.lat, maplocation.lng], 15);

				L.marker([maplocation.lat, maplocation.lng], {
					icon: L.mapbox.marker.icon({
						'marker-size': 'large',
						'marker-symbol': 'building',
						'marker-color': '#333'
					})
				}).addTo(map);
			}
		}
	});
	
})( jQuery );
