<?php
/***********************************************/
/*               About This File               */
/***********************************************/
/*
	This file contains the featured item widget.
	The featured item allows for the upload of
	an image, the specification of some text and
	a link to show as a featured item.
*/

/***********************************************/
/*              Table of Contents              */
/***********************************************/
/*
	1. Featured Item Widget Class
		1.1 Constructor
		1.2 Backend Form
		1.3 Save Widget Options
		1.4 Frontend Widget Display

	2. Widget Registration

*/

/***********************************************/
/*       1. Featured Item Widget Class         */
/***********************************************/

class bshMapWidget extends WP_Widget {

    var $image_field = 'image';

	// 1.1 Constructor
	function bshMapWidget() {
        parent::WP_Widget(
        	false,
        	__( 'Estatement: Map Widget', THEMENAME ),
        	array(
        		'description' => __( 'This widget can output a map using the details you specify', THEMENAME )
        	)
        );
    }

	// 1.2 Backend Form
	function form( $instance ) {
		$defaults = array(
			'title'              => '',
			'location'           => 'Colorado Springs, CO',
			'type'               => 'ROADMAP',
			'zoom'               => '14',
			'marker'             => 'yes',
			'height'             => '250px',
		);
		$values = wp_parse_args( $instance, $defaults );
        $image  = new WidgetImageField( $this, $values['image'] );

		?>
        <p>
        	<label for='<?php echo $this->get_field_id('title'); ?>'>
        		<?php _e( 'Title:', THEMENAME ); ?>
        		<input class='widefat' id='<?php echo $this->get_field_id( 'title' ); ?>' name='<?php echo $this->get_field_name( 'title' ); ?>' type='text' value='<?php echo $values['title']; ?>' />
        	</label>
        </p>

        <p>
        	<label for='<?php echo $this->get_field_id('location'); ?>'>
        		<?php _e( 'Location:', THEMENAME ); ?>
        		<input class='widefat' id='<?php echo $this->get_field_id( 'location' ); ?>' name='<?php echo $this->get_field_name( 'location' ); ?>' type='text' value='<?php echo $values['location']; ?>' />
        	</label>
        </p>

        <p>
        	<label for='<?php echo $this->get_field_id('autolocation'); ?>'>
        		<?php $checked = ( $values['autolocation'] == 'yes' ) ? 'checked="checked"' : ''; ?>
				<input type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'autolocation' ); ?>' name='<?php echo $this->get_field_name( 'autolocation' ); ?>' value='yes'> <label for='<?php echo $this->get_field_id( 'autolocation' ); ?>'><?php _e( 'Automatic Location', THEMENAME ) ?><br><label for='<?php echo $this->get_field_id( 'autolocation' ); ?>'><?php _e( 'If on a property page, get the location for this map from the location of the property (this will override the previous setting)', THEMENAME ) ?></label><br>
        	</label>
        </p>


        <p>
        	<label for='<?php echo $this->get_field_id('type'); ?>'>
        		<?php _e( 'Map Type:', THEMENAME ); ?>
        		<?php
					$current = $values['type'];
					$choices = array(
						'ROADMAP' => 'Road Map',
						'SATELLITE' => 'Satellite Map',
						'TERRAIN' => 'Terrain Map',
						'HYBRID' => 'Hybrid Map',
					)
        		?>
        		<select class='widefat' id='<?php echo $this->get_field_id( 'type' ); ?>' name='<?php echo $this->get_field_name( 'type' ); ?>'>
        			<?php foreach( $choices as $value => $name ) : ?>
						<option value='<?php echo $value ?>'><?php echo $name ?></option>
					<?php endforeach ?>
        		</select>
        	</label>
        </p>

        <p>
        	<label for='<?php echo $this->get_field_id('marker'); ?>'>
        		<?php _e( 'Show Marker:', THEMENAME ); ?><br>
        		<?php
					$choices = array(
						'yes' => 'Yes',
						'no' => 'No',
					)
        		?>
        			<?php
        				foreach( $choices as $value => $name ) :
						$checked = ( $values['marker'] == $value OR ( empty( $values['marker'] ) AND $value == 'yes' ) ) ? 'checked="checked"' : ''
        			?>
						<input type='radio' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'marker' ); ?>-<?php echo $value ?>' name='<?php echo $this->get_field_name( 'marker' ); ?>' value='<?php echo $value ?>'> <label for='<?php echo $this->get_field_id( 'marker' ); ?>-<?php echo $value ?>'><?php echo $name ?></label><br>
					<?php endforeach ?>
        	</label>
        </p>


        <p>
        	<label for='<?php echo $this->get_field_id('zoom'); ?>'>
        		<?php _e( 'Zoom:', THEMENAME ); ?>
        		<input class='widefat' id='<?php echo $this->get_field_id( 'zoom' ); ?>' name='<?php echo $this->get_field_name( 'zoom' ); ?>' type='text' value='<?php echo $values['zoom']; ?>' />
        	</label>
        </p>

        <p>
        	<label for='<?php echo $this->get_field_id('height'); ?>'>
        		<?php _e( 'Height:', THEMENAME ); ?>
        		<input class='widefat' id='<?php echo $this->get_field_id( 'height' ); ?>' name='<?php echo $this->get_field_name( 'height' ); ?>' type='text' value='<?php echo $values['height']; ?>' />
        	</label>
        </p>

        <?php
    }

	// 1.3 Save Widget Options
	function update( $new_instance, $old_instance ) {
  		$new_instance['autolocation'] = ( $new_instance['autolocation']  == 'yes' ) ? 'yes' : 'no';
        return $new_instance;
    }

	// 1.4 Frontend Widget Display
	function widget( $args, $instance ) {
		global $post, $wpdb;
		echo $args['before_widget'];
		echo $args['before_title'] . $instance['title'] .  $args['after_title'];

		$location = $instance['location'];
		if( !empty( $post ) AND $post->post_type == 'property' ) {
			$metas = array( '_est_meta_country', '_est_meta_city', '_est_meta_state', '_est_meta_address', '_est_meta_zipcode' );

			$property_location = array();
			foreach( $metas as $meta ) {
				$value = get_post_meta( $post->ID, $meta, true );
				if( !empty( $value ) ) {
					$property_location[] = $value;
				}
			}
			$property_location = implode( ', ', $property_location );

			if( !empty( $property_location ) ) {
				$location = $property_location;
			}
		}


		echo do_shortcode( '[map location="' . $location . '" zoom="' . $instance['zoom'] . '" type="' . $instance['type'] . '" marker="' . $instance['marker'] . '" height="' . $instance['height'] . '"]' );
		echo $args['after_widget'];
    }
}


/***********************************************/
/*          2. Widget Registration             */
/***********************************************/

register_widget('bshMapWidget');

?>