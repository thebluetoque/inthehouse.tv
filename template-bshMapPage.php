<?php
/*
Template Name: Map Page
*/
get_header();
$map_type = get_post_meta( $post->ID, '_est_map_type', true );
$map_type = ( empty( $map_type ) ) ? 'roadmap' : $map_type;

$auto_location = get_post_meta( $post->ID, '_est_initial_location_auto', true );
$auto_location = ( $auto_location == 'yes' ) ? 'yes' : 'no';

$initial_location = get_post_meta( $post->ID, '_est_initial_location_geocode', true );
$initial_location = ( empty( $initial_location ) OR empty( $initial_location[0] ) OR empty( $initial_location[1] ) ) ? array( 38.830615, -104.824677 ) : $initial_location;

$icon = get_post_meta( $post->ID, '_est_marker', true );
$icon = ( empty( $marker) ) ? get_template_directory_uri() . '/images/marker.png' : $icon;

$no_results_message = get_post_meta( $post->ID, '_est_no_results_message', true );
$no_results_message = ( empty( $no_results_message ) ) ? 'There are no results near that location. Please try a different location, or increase the distance in the additional options' : $no_results_message;

$error_behavior = get_post_meta( $post->ID, '_est_error_behavior', true );
$error_behavior = ( empty( $error_behavior ) ) ? 'show_error' : $error_behavior;

$full_map = get_post_meta( $post->ID, '_est_full_page_map', true );
$full_map = ( empty( $full_map ) ) ? 'yes' : $full_map;
$map_class = ( $full_map == 'yes' ) ? 'full'
: 'no-full';

$height = get_post_meta( $post->ID, '_est_map_height', true );
$height = str_replace( 'px', '', $height );
$map_style = ( $full_map == 'no' ) ? 'height:' . ( $height + 200 ) . 'px' : '';
$map_container_style = ( $full_map == 'no' ) ? 'height:' .  $height . 'px' : '';


$enable_search = get_post_meta( $post->ID, '_est_search', true );
$enable_search = ( empty( $enable_search ) ) ? 'yes' : $enable_search;
?>
    <script type="text/javascript">
    //<![CDATA[
    var map;
    var markers = [];
    var infoWindow;
    var locationSelect;
    var autolocation = '<?php echo $auto_location ?>';

    function get_user_location() {
         navigator.geolocation.getCurrentPosition( handle_geolocation_query );
    }

	function handle_geolocation_query(position){
	    var center = new google.maps.LatLng(position.coords.latitude, position.coords.longitude )
	    searchLocationsNear(center)
	}

	function show_loader() {
		jQuery( '#map-loader' ).show();
	}

	function hide_loader() {
		jQuery( '#map-loader' ).fadeOut();
	}




    function load() {

    	if( autolocation === 'yes' ) {
	    	get_user_location()
	    }
	    else {
		    var center = new google.maps.LatLng(<?php echo $initial_location[0] ?>, <?php echo $initial_location[1] ?> )
		    searchLocationsNear( center );
      }


      map = new google.maps.Map(document.getElementById("map"), {
        center: center,
        draggable: true,
        zoom: 12,
        mapTypeId: '<?php echo $map_type ?>',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });


	document.addEventListener('touchmove', function(e) {
		map.setOptions({draggable:false});
	});



      infoWindow = new google.maps.InfoWindow();



      locationSelect = document.getElementById("locationSelect");
      locationSelect.onchange = function() {
        var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
        if (markerNum != "none"){
          google.maps.event.trigger(markers[markerNum], 'click');
        }
      };
   }

   function searchLocations() {
     var address = document.getElementById("addressInput").value;
     var geocoder = new google.maps.Geocoder();
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
        searchLocationsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
   }

   function clearLocations() {
   	if ( markers.length > 0 ) {
     infoWindow.close();
     for (var i = 0; i < markers.length; i++) {
       markers[i].setMap(null);
     }
     markers.length = 0;

     locationSelect.innerHTML = "";
     var option = document.createElement("option");
     option.value = "none";
     option.innerHTML = "See all results:";
     locationSelect.appendChild(option);
     }
   }

		function searchLocationsNear(center) {
			show_loader();
		  var radius = document.getElementById('radiusSelect').value;
		  var  form_data = jQuery('#map-page-form').serialize();
		  jQuery.ajax({
			  url: estAjax.ajaxurl,
			  type: 'post',
			  data: {
			  	action: 'load_location_xml',
			  	radius : radius,
			  	lat: center.lat(),
			  	lng: center.lng(),
			  	form_data: form_data
			  },
			  dataType: 'text',
			  success: function( data ) {
			  		hide_loader();
		  var xml = parseXml(data);
		  var markerNodes = xml.documentElement.getElementsByTagName("marker");
		  var bounds = new google.maps.LatLngBounds();
		  if( markerNodes.length == 0 ) {
			  <?php if( $error_behavior == 'show_error' ) : ?>
			  alert( '<?php echo addslashes($no_results_message) ?>' );
			  <?php endif ?>
		  }
		  else {
		  		  clearLocations();

		  for (var i = 0; i < markerNodes.length; i++) {
		    var name = markerNodes[i].getAttribute("name");
		    var address = markerNodes[i].getAttribute("address");
		    var html = markerNodes[i].getAttribute("html");
		    var url = markerNodes[i].getAttribute("url");
		    var icon = markerNodes[i].getAttribute("icon");
		    var distance = parseFloat(markerNodes[i].getAttribute("distance"));
		    var latlng = new google.maps.LatLng(
		        parseFloat(markerNodes[i].getAttribute("lat")),
		        parseFloat(markerNodes[i].getAttribute("lng")));

		    createOption(name, distance, i);
		    createMarker(latlng, name, address, html, url, icon);
		    bounds.extend(latlng);
		  }
		  map.fitBounds(bounds);

		  }

			  }
		  })
		  }


    function createMarker(latlng, name, address, html, marker_url, icon) {

    	if( icon === '' || typeof icon === 'undefined' || icon === null ) {
	    	marker_icon = '<?php echo $icon ?>';
    	}
    	else {
    		marker_icon = icon;
    	}

      var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        icon: marker_icon,
      });
      google.maps.event.addListener(marker, 'click', function() {
      	if( jQuery(window).width() < 500 ) {
      		window.location = marker_url;
      	}
      	else {
	        infoWindow.setContent(html);
        	infoWindow.open(map, marker);
        	var height = jQuery( '#mapContainer' ).height();

        	if( jQuery('#map').hasClass( 'no-full' ) && height < 600 ) {
	        	jQuery( '#map, #mapContainer' ).animate( {height: '600px' } )
        		jQuery( '#searchContainer' ).animate( { top: '520px'} )
        	}
        }
      });
      markers.push(marker);
    }

    function createOption(name, distance, num) {
      var option = document.createElement("option");
      option.value = num;
      option.innerHTML = name + "(" + distance.toFixed(1) + ")";
      locationSelect.appendChild(option);
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request.responseText, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}


    jQuery( document ).ready( function() {
	    load();
    })

    //]]>
  </script>

  <?php
  if( $enable_search != 'no' ) :
  ?>

  	<div id='searchContainer'>
		<form onsubmit='searchLocations(); return false' method='get' action='<?php echo site_url() ?>' id='map-page-form' class='custom'>
			<div id='main-search'>
				<input type='submit' class='submit button' value='<?php echo bs_get_post_meta( '_est_button_text', 'search' ); ?>'>
				<input type='text' id='addressInput' class='main-search-field' name='location' placeholder='<?php echo esc_attr( bs_get_post_meta( '_est_input_placeholder', 'Where would you like to live?' ) ) ?>'>
			</div>
			<div id='advanced-search'>

				<?php
					$show = get_post_meta( get_the_ID(), '_est_show_proximity', true );
					$show = ( empty( $show ) ) ? 'yes' : $show;
					$show_style = ( $show == 'no' ) ? 'style="display:none"' : ''
				?>

				<div class='row form-row' <?php echo $show_style ?> >
					<div class='large-4 small-12 columns'>
						<?php
							$proximity_label = get_post_meta( get_the_ID(), '_est_proximity_label', true );
							$proximity_label = ( empty( $proximity_label ) ) ? __( 'Distance', THEMENAME ) : $proximity_label;
						?>
						<label for="$detail" class='offset'><?php echo $proximity_label ?></label>
					</div>

					<div class='large-8 small-12 columns' >
					    <select id="radiusSelect">
					    	<?php
					    		$options = get_post_meta( get_the_ID(), '_est_proximity_options', true );
								$options = ( empty( $options ) ) ? '25,50,100,200,500' : $options;
					    		$unit = get_post_meta( get_the_ID(), '_est_proximity_unit', true );
								$unit = ( empty( $unit ) ) ? 'mi' : $unit;
					    		$options = explode( ',', $options );
					    		$default = get_post_meta( get_the_ID(), '_est_default_proximity', true );
					    		$default = ( empty( $default ) ) ? '100' : $default;
					    		foreach( $options as $option ) :
					    			$option = trim( $option );
									$selected = ( $option == $default ) ? 'selected="selected"' : '';
					    	?>
								<option <?php echo $selected ?> value="<?php echo $option ?>"><?php echo $option ?><?php echo $unit ?></option>
						  	<?php endforeach ?>
					    </select>
					</div>
				</div>

					<?php
						$advanced_search = bs_get_post_meta( '_est_advanced_search', 'no' );
						if( $advanced_search == 'yes' ) :
					?>

						<?php

							$search['customdatas'] = get_post_meta( $post->ID, '_est_customdatas', true );
							$search['custom_taxonomies'] = get_post_meta( $post->ID, '_est_taxonomies', true );

							$details = get_search_options( $search );

							est_vertical_search( $details );
						?>


					<?php endif ?>


			</div>


			<div class='row'>
				<div class='large-12 small-12 columns'>
					<span id='advanced-search-switch' data-open_text='<?php echo bs_get_post_meta( '_est_advanced_search_text_open', 'less options' ) ?>' data-closed_text='<?php echo bs_get_post_meta( '_est_advanced_search_text', 'more options' ) ?>'>
						<?php echo bs_get_post_meta( '_est_advanced_search_text', 'more options' ) ?>
					</span>
				</div>
			</div>

		</form>
	</div>

	<?php else : ?>

		<?php
			$proximity = get_post_meta( get_the_ID(), '_est_default_proximity', true );
		?>
		<input type='hidden' id='radiusSelect' value='<?php echo $proximity ?>'>

	<?php endif ?>

    <div style='display:none'><select id="locationSelect" style="width:100%;visibility:hidden"></select></div>

	<div id='mapContainer' class='<?php echo $map_class ?>' style='<?php echo $map_container_style ?>'>
		<div id='map-loader'></div>
		<div id="map" class='map <?php echo $map_class ?>' style='<?php echo $map_style ?>'></div>
	</div>

<?php if( $full_map == 'no' ) : ?>

<?php get_template_part( 'module', 'featured' ) ?>

<div class='row mt44'>
	<div class='large-12 small-12 columns'>
		<div id='siteContent'>
			<div class='row'>
				<div id='siteMain' class='<?php echo bsh_content_classes() ?> columns'>

					<?php

						if( have_posts() ) {
							while( have_posts() ) {
								the_post();

								echo '<div class="content">';
									the_content();
								echo '</div>';
							}
						}
						else {

						}
					?>
				</div>
				<?php if( bsh_has_sidebar() ) : ?>
					<div id='siteSidebar' class='small-12 large-4 columns <?php echo bsh_sidebar_classes() ?>'>
						<?php dynamic_sidebar( bsh_get_sidebar() ) ?>
					</div>
				<?php endif ?>
			</div>

		</div>
	</div>
</div>

<?php endif ?>

<?php get_footer() ?>