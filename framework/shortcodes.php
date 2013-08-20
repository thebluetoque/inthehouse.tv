<?php
/***********************************************/
/*               About This File               */
/***********************************************/
/*
	This file contains the shortcodes for the
	theme. Documentations for each shortcode
	should be made separately, based on the
	arguments defined here. Minimal inline
	documentation about arguments are provided
	here as well. The format of the argument
	documentation is:

	@argument [- required][- internal]: Description (possible values)

*/

/***********************************************/
/*              Table of Contents              */
/***********************************************/
/*

	1. Shortcodes
		1.1  Line
		1.2  Row
		1.3  Column
		1.4  Map
		1.5  Tabs
		1.6  Accordion
		1.7  Section
		1.8  Button
		1.9  Highlight
		1.10 Message
		1.11 Slideshow
		1.12 Postlist
		1.13 Sidebar

	2. Shortcode Helper Functions
		1.1 Shortcode Style Generator
*/

/***********************************************/
/*                 Shortcodes                  */
/***********************************************/

// 1.1 Line
/*
Outputs a separator line. Optionally a link
can be added to either side of the line.
@url: The url of the link
@text: The link text for the link
@position: The position (left, right)
@color: The color of the line (color)
*/
add_shortcode( 'line', COMPANYPREFIX . '_shortcode_line' );
function bsh_shortcode_line( $args ) {
	global $shortcode_style;
	$defaults = array(
		'url'         => '',
		'text'        => 'link',
		'position'    => 'left',
		'color'       => ''
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$shortcode_style = '';
	bsh_shortcode_style( 'background-color', $color, 'color' );

	$output = '<div class="line primary-links" style="' . $shortcode_style . '">';
	if( !empty( $url ) ) {
		$output .= '<a class="align' . $position . '" href="' . $url . '">' . $text . '</a>';
	}
	$output .= '</div>';

	return $output;

}


// 1.3 Row
/*
A wrapper used for columns. Must be used together
with the [column] shortcode for it to work.
*/
add_shortcode( 'row', COMPANYPREFIX . '_shortcode_row' );
function bsh_shortcode_row( $args, $content ) {
	$output = '<div class="row user-columns mb44">' . do_shortcode( $content ) . '</div>';
	return $output;
}

// 1.4 Column
/*
A shortcode to wrap column content. Must be used
together with the [row] shortcode
@width - required: The columns widths ( one - twelve )
*/
add_shortcode( 'column', COMPANYPREFIX . '_shortcode_column' );
function bsh_shortcode_column( $args, $content ) {
	$defaults = array(
		'width' => 'twelve',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$output = '<div class="small-12 large-' . $width . ' columns">' . do_shortcode( $content ) . '</div>';

	return $output;
}


// 1.5 Map
/*
Outputs a Google Map
@location - required: The location to center the map on
@type: The type of map shown (HYBRID, SATELLITE, ROADMAP, TERRAIN)
@zoom: The zoom level (1-18)
@marker: Weather or not a marker should be placed in the center (yes/no)
@height: The map height (400px)
*/
add_shortcode( 'map', COMPANYPREFIX . '_shortcode_map' );
function bsh_shortcode_map( $args ) {
	$defaults = array(
		'location' => '',
		'type'     => 'ROADMAP',
		'zoom'     => '14',
		'marker'   => 'yes',
		'height'   => '400px'
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	if( empty( $location ) ) {
		global $post;
		$metas = array( '_est_meta_country', '_est_meta_city', '_est_meta_state', '_est_meta_address', '_est_meta_zipcode' );

		$location = array();
		foreach( $metas as $meta ) {
			$value = get_post_meta( $post->ID, $meta, true );
			if( !empty( $value ) ) {
				$location[] = $value;
			}
		}
		$location = implode( ', ', $location );
	}


	$id = rand( 9999, 999999 );
	ob_start();
	?>

	<div class="map-canvas" id='map-<?php echo $id ?>' style="width: 100%; height:<?php echo $height ?>"></div>
		<script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			var geocoder = new google.maps.Geocoder();
			var address = '<?php echo $location  ?>';
			geocoder.geocode( { 'address': address}, function(results, status) {
				var lat = results[0].geometry.location.lat();
				var lng = results[0].geometry.location.lng();
				var latLng = new google.maps.LatLng(lat, lng);
				var mapOptions = {
				  scrollwheel: false,
				  center: latLng,
				  zoom: <?php echo $zoom ?>,
				  mapTypeId: google.maps.MapTypeId.<?php echo strtoupper($type) ?>
				};

				var map = new google.maps.Map( $("#map-<?php echo $id ?>")[0],
			    mapOptions);

			    <?php if( $marker == 'yes' ) : ?>
			    var marker = new google.maps.Marker({
			      position: latLng,
			      map: map,
			    });
			    <?php endif ?>
			})
		})

		</script>


	<?php
	$output = ob_get_clean();

	return $output;
}



// 1.6 Tabs
/*
Outputs a tabbed interface. Must be used together
with the [section] shortcode.
@contained: If set, the tab contents will be inside a box (yes/no)
@pill:  Use pill style tabs (yes/no)
*/
add_shortcode( 'tabs', COMPANYPREFIX . '_shortcode_tabs' );
function bsh_shortcode_tabs( $args, $content ) {
	$defaults = array(
		'contained'  => 'yes',
		'pill'       => 'no'
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$output = '';

	$output = '<div class="section-container tabs" data-section="tabs">';
	$output .= do_shortcode( $content );
	$output .= '</div>';

	return $output;

}


// 1.7 Accordion
/*
Outputs a collapsable accordion. Must be
used together with the [section] shortcode.
*/
add_shortcode( 'accordion',  COMPANYPREFIX . '_shortcode_accordion' );
function bsh_shortcode_accordion( $args, $content ) {
	$content = str_replace( '[section ', '[section type="accordion" ', $content );
	$pos = strpos( $content, '[section');
	$content_1 = substr( $content, 0, $pos + 8 );
	$content_2 = substr( $content, $pos + 8 );
	$content = $content_1 . ' active="yes"' . $content_2;

	$output = '<div class="section-container accordion" data-section="accordion">';
	$output .= do_shortcode( $content );
	$output .= '</div>';
	return $output;
}

// 1.8 Section
/*
Used for creating accordion and tab sections.
Must be used together with the [accordion] or
the [tabs] shortcode.
@id - internal: A unique identifier
@active - internal: The active marker for the element
@type - internal: Used to dostinguish between types
@title - required: The title shown as the tab/accordion title
*/
add_shortcode( 'section', COMPANYPREFIX . '_shortcode_section' );
function bsh_shortcode_section( $args, $content ) {
	$defaults = array(
		'id'      => '',
		'active'  => '',
		'type'    => 'tab',
		'title'   => 'My Tab'
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );


	$output = '
	  <section>
    <p class="title" data-section-title><a href="#">' . $title . '</a></p>
    <div class="content" data-section-content>
      '. do_shortcode( $content ).'
    </div>
  </section>


	';

	return $output;

}

// 1.9 Button
/*
Used to create nicely formatted buttons
@url: The url to which the button points to
@background: The background color (color)
@color: The text color of the button (color)
@gradient: Adds or removes a gradient (no)
@radius: The amount of borde radius (+px)
@arrow: Weather or not to generate an arrow - uses the text color (yes/no)
*/
add_shortcode( 'button', COMPANYPREFIX . '_shortcode_button' );
function bsh_shortcode_button( $args, $content ) {
	global $shortcode_style;
	$defaults = array(
		'url'        => '',
		'background' => 'primary',
		'color'      => 'primary_text',
		'gradient'   => 'no',
		'radius'     => '5px',
		'arrow'      => 'no',
		'width'      => '',
		'hovercolor' => ''
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$shortcode_style = '';
	bsh_shortcode_style( 'background-color', $background, 'color' );
	if( $gradient === 'yes' ) {
		bsh_shortcode_style( 'background', $background, 'gradient' );
	}
	bsh_shortcode_style( 'border-color', $background, 'border-darken' );
	bsh_shortcode_style( 'color', $color, 'color' );
	if( !empty( $radius ) ) {
		bsh_shortcode_style( 'bordrer-radius', $radius, 'radius' );
	}
	bsh_shortcode_style( 'text-shadow', $background, 'text-shadow' );

	if( !empty( $width ) ) {
		bsh_shortcode_style( 'width', $width );
	}

	$arrow_html = '';
	if( $arrow  == 'yes' ) {
		$arrow_html = '<span class="arrow"';
		if( !empty( $color ) ) {
			$arrow_html .= 'style="border-left:6px solid ' . $color . '"';
		}
		$arrow_html .= '></span>';
	}

	$id = 'button-' . strtolower( substr( sha1( time() . rand( 999, 99999 ) ), 0, 8 ) );

	if( empty( $url ) ) {
		$output = '<span style="' . $shortcode_style . '" class="button" id="' . $id . '">' . $content . $arrow_html .'</span>';
	}
	else {
		$output = '<a style="' . $shortcode_style . '" id="' . $id . '" href="' . $url . '" class="button">' . $content . $arrow_html . '</a>';
	}

	if( !empty( $hovercolor ) ) {
		$output .= '
			<style type="text/css">
				#' . $id . ':hover {
					background: ' . $hovercolor . ' !important
				}
			</style>
		';
	}

	return $output;
}

// 1.10 Highlight
/*
Used to highlight text
@background: The background color (color)
@color: The text color (color)
*/
add_shortcode( 'highlight', COMPANYPREFIX . '_shortcode_highlight' );
function bsh_shortcode_highlight( $args, $content ) {
	global $shortcode_style;
	$defaults = array(
		'background' => 'primary',
		'color'      => 'primary_text',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$shortcode_style = '';
	bsh_shortcode_style( 'background-color', $background, 'color' );
	bsh_shortcode_style( 'border-color', $background, 'border-darken' );
	bsh_shortcode_style( 'color', $color, 'color' );

	if( empty( $url ) ) {
		$output = '<span style="' . $shortcode_style . '" class="highlight">' . $content .'</span>';
	}
	else {
		$output = '<a style="' . $shortcode_style . '" class="highlight">' . $content . '</a>';
	}

	return $output;
}


// 1.11 Message
/*
Creates a callout style closable message
@background: The background color (color)
@color: The text color (color)
@radius: The amount of borde radius (+px)
*/
add_shortcode( 'message', COMPANYPREFIX . '_shortcode_message' );
function bsh_shortcode_message( $args, $content ) {
	global $shortcode_style;
	$defaults = array(
		'background'  => '',
		'color' => '',
		'radius' => '0px'
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$shortcode_style = '';
	bsh_shortcode_style( 'background-color', $background, 'color' );
	bsh_shortcode_style( 'border-color', $background, 'border-darken' );
	bsh_shortcode_style( 'color', $color, 'color' );
	if( !empty( $radius ) ) {
		bsh_shortcode_style( 'border-radius', $radius, 'radius' );
	}

	$output = '<div data-alert style="' . $shortcode_style . '" class="alert-box message">' . do_shortcode($content) . ' <a href="" class="close">x</a></div>';

	return $output;

}


// 1.12 Slideshow
/*
Slideshows work exactly like the gallery shortcode
but pull the images into a nicely formatted slider
*/
add_shortcode( 'slideshow', COMPANYPREFIX . '_shortcode_slideshow' );
function bsh_shortcode_slideshow( $args ) {
	global $shortcode_style, $post;
	$defaults = array(
		'ids'         => '',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );


	if( empty( $ids ) ) {
		$images = get_posts( 'post_type=attachment&post_mime_type=image&parent=' . $post->ID );
		foreach( $images as $image ) {
			$ids[] = $image->ID;
		}
	}
	else {
		$ids = explode( ',', $ids );
		$ids = array_map( 'trim', $ids );
	}

	$random = substr( sha1( time() . rand( 2234, 9999 ) ), 6 );

	$output = '<div class="post-slider flexslider shortcode-slider" id="slider-' . $random . '"><ul class="slides">';
	foreach( $ids as $id ) {
		$image = wp_get_attachment_image( $id, bsh_get_layout_size( 'est_large' ) );
		$output .= '<li>' . $image . '</li>';
	}
	$output .= '</ul></div>';

	$output .= '
		<script type="text/javascript">
			jQuery(window).load(function( ) {
				jQuery("#slider-' . $random . '").flexslider({
					animation: "slide"
				});
			});
		</script>
	';

	return $output;

}

// 1.13 Postlist
/*
Postlists allow you to display a list of posts inside
the contents of another post
*/
add_shortcode( 'postlist', COMPANYPREFIX . '_shortcode_postlist' );
function bsh_shortcode_postlist( $args ) {
	global $shortcode_style, $post, $wp_query;
	$defaults = array(
		'post_type'         => 'post',
		'post_status'       => 'publish',
		'count'             => '3',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$temp_query = $wp_query;
	$wp_query = null;

	$atts = array(
		'post_type'      => $post_type,
		'post_status'    => $post_status,
		'posts_per_page' => $count
	);

	$wp_query = new WP_Query( $atts );
	$output = '';

	ob_start();
	if( have_posts() ) {
		echo '<div class="content-postlist">';
		while( have_posts() ) {
			the_post();
			get_template_part( 'layout', 'post-list' );
		}
		echo '</div>';
	}


	$output = ob_get_clean();

	$wp_query = $temp_query;
	wp_reset_postdata();

	return $output;


}



// 1.14 Sidebar
/*
Allows you to insert a dynamic sidebar anywhere
*/
add_shortcode( 'sidebar', COMPANYPREFIX . '_shortcode_sidebar' );
function bsh_shortcode_sidebar( $args ) {
	global $shortcode_style, $post, $wp_query;
	$defaults = array(
		'name'              => 'Sidebar',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	ob_start();
	dynamic_sidebar( $name );
	$output = ob_get_clean();
	return $output;

}

// 1.15 Title
add_shortcode( 'title', COMPANYPREFIX . '_shortcode_title' );
function bsh_shortcode_title( $args, $content ) {
	$defaults = array(
		'icon'        => 'star',
		'iconcolor'   => 'white',
		'linktext'    => '',
		'linkurl'     => ''
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );


	$output = '<div class="section-title">';
	$output .= '<span class="flag">';
	$output .= 	"<img class='retina' width='18' height='18' src='" . get_template_directory_uri() . "/images/icons/glyphicons/" . $iconcolor . "/24x24/" . $icon . ".png' alt='Icon'>";

	$output .= '</span>';

	if( !empty( $linktext ) AND !empty( $linkurl ) ) {
		$output .= '<a href="' . $linkurl . '">' . $linktext . '</a>';
	}

	$output .= '<h2>' . $content . '</h2>';


	$output .= '</div>';

	return $output;

}


add_shortcode( 'propertylist', COMPANYPREFIX . '_shortcode_propertylist' );
function bsh_shortcode_propertylist( $args, $content ) {
	global $wp_query, $options;
	$defaults = array(
		'layout'              => 'card',
		'count'               => '3',
		'include'             => '',
		'columns'             => 3,
		'orderby'             => 'date',
		'details'             => '',
		'single_address'      => false,
		'single_address_name' => 'Location',
		'subtitle'            => ''
	);


	$args = wp_parse_args( $args, $defaults );
	$options = $args;
	extract( $args );

	$options['customdatas'] = array();
	$options['custom_taxonomies'] = array();
	if( !empty( $options['details'] ) ) {
		$options['details'] = explode( '|', $details );
		$i=1;
		foreach( $options['details'] as $detail ) {
			if( substr_count( $detail, 'taxonomy-' ) > 0 ) {
				$taxonomy = trim( str_replace( 'taxonomy-', '', $detail ) );
				$options['custom_taxonomies'][$taxonomy] = array(
					'show' => 'yes',
					'order' => $i
				);
			}
			else {
				$name = trim( '_est_meta_' . $detail );
				$options['customdatas'][$name] = array(
					'show' => 'yes',
					'order' => $i
				);
			}
			if( $detail == 'single_address' ) {
				$options['single_address_order'] = $i;
			}
			$i++;
		}
	}
	else {
		$defaults = get_option( 'est_customdata_default' );
		$options['customdatas'] = $defaults;
	}


	$options['_est_single_field_address_name'] = $single_address_name;
	$options['layout'] = $layout;

	$output = '';
	$temp_query = $wp_query;
	$wp_query = null;

	$atts = array(
		'post_type'       => 'property',
		'posts_per_page'  => $count,
		'orderby'         => $orderby
	);

	if( !empty( $property_category ) OR !empty( $property_type ) ) {
		$atts['tax_query'] = array();
		if( !empty( $property_category ) ) {
			$terms = explode( ',', $property_category );
			$terms = array_map( 'trim', $terms );

			$atts['tax_query'][] = array(
				'taxonomy' => 'property_category',
				'field'    => 'ID',
				'terms'    => $terms
			);
		}
		if( !empty( $property_type ) ) {
			$terms = explode( ',', $property_type );
			$terms = array_map( 'trim', $terms );

			$atts['tax_query'][] = array(
				'taxonomy' => 'property_type',
				'field'    => 'ID',
				'terms'    => $terms
			);
		}
	}

	if( !empty( $include ) ) {
		$post_in = explode( ',', $include );
		$post_in = array_map( 'trim', $post_in );

		$atts = array(
			'post_type' => 'property',
			'posts_per_page' => -1,
			'post__in' => $post_in,
		);
	}

	$wp_query = new WP_Query( $atts );

	if( have_posts() ) {
		ob_start();
		echo '<div class="propertylist mb44">';
		$i=1;

		$column_numbers = array(
			1 => 12,
			2 => 6,
			3 => 4,
			4 => 3
		);

		$column_number = ( in_array( $columns, array_keys( $column_numbers ) ) ) ? $column_numbers[$columns] : 4;
		$column_number = ( $layout === 'list' ) ? 12 : $column_number;
		$row_repeat = ( in_array( $columns, $column_numbers ) ) ? $columns : 3;


		while( have_posts() ) {
			the_post();

			if( ( $i + ( $row_repeat - 1 ) ) % $row_repeat === 0 ) {
				echo '<div class="row">';
			}

				echo '<div class="large-' . $column_number . ' small-12 columns">';
					get_template_part( 'layout', 'property-' . $layout );
				echo '</div>';

			if( $i % $row_repeat === 0 ) {
				echo '</div>';
			}

			$i++;
		}

		if( ( $i - 1 ) % $row_repeat != 0 ) {
			echo '</div>';
		}

		echo '</div>';
	}

	$output .= ob_get_clean();

	$wp_query = $temp_query;
	wp_reset_postdata();

	return $output;

}


// 1.6 Push
add_shortcode( 'push', COMPANYPREFIX . '_shortcode_push' );
function bsh_shortcode_push( $args, $content ) {
	$defaults = array(
		'height'  => '44px',
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$output = '';

	$output = '<div style="height:' . $height . '"></div>';

	return $output;

}




/***********************************************/
/*         Shortcode Helper Functions          */
/***********************************************/

// 2.1 Shortcode Style Generator
function bsh_shortcode_style( $property, $value, $type = 'default' ) {
	global $shortcode_style;
	if( !empty( $value ) ) {
		if( $type === 'color' OR $type === 'gradient' OR $type === 'border-darken' ) {
			$value = bsh_determine_color( $value );
		}
		if( $type === 'text-shadow' ){
			if( $value == 'primary' ) {
				$shortcode_style .= 'text-shadow: -1px -1px 0 rgba(0,0,0,0.1);';
			}
			else {
				$color = new Color( $value );
				$shortcode_style .= 'text-shadow: -1px -1px 0 #' . $color->darken(10) . ';';
			}
		}

		if( $type === 'gradient' ){
			$color = new Color( $value );
			$gradient = $color->makeGradient( 10 );
			$shortcode_style .= "
				background: #" . $gradient['light'] . ";
				background: -moz-linear-gradient(top,  #" . $gradient['light'] . " 0%, #" . $gradient['dark'] . " 100%);
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#" . $gradient['light'] . "), color-stop(100%,#" . $gradient['dark'] . "));
				background: -webkit-linear-gradient(top,  #" . $gradient['light'] . " 0%,#" . $gradient['dark'] . " 100%);
				background: -o-linear-gradient(top,  #" . $gradient['light'] . " 0%,#" . $gradient['dark'] . " 100%);
				background: -ms-linear-gradient(top,  #" . $gradient['light'] . " 0%,#" . $gradient['dark'] . " 100%);
				background: linear-gradient(to bottom,  #" . $gradient['light'] . " 0%,#" . $gradient['dark'] . " 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $gradient['light'] . "', endColorstr='" . $gradient['dark'] . "',GradientType=0 );
			";

		}
		elseif( $type === 'border-darken' ) {
			$color = new Color( $value );
			$shortcode_style .= $property . ': #' . $color->darken(10) . ';';
		}
		elseif( $type === 'radius' ) {
			$shortcode_style .= '
				-moz-border-radius: ' . $value . ';
				-khtml-border-radius: ' . $value . ';
				-ie-border-radius: ' . $value . ';
				-o-border-radius: ' . $value . ';
				border-radius: ' . $value . ';
			';
		}
		else {
			$css = $property . ': ' . $value;
			$shortcode_style .= $css . ';';
		}
	}
}

?>