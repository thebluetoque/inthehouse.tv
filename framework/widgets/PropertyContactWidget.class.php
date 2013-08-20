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

class bshPropertyContactWidget extends WP_Widget {

    var $image_field = 'image';

	// 1.1 Constructor
	function bshPropertyContactWidget() {
        parent::WP_Widget(
        	false,
        	__( 'Estatement: Property Contact Form', THEMENAME ),
        	array(
        		'description' => __( 'This widget shows the contact for for the property shown. You can set the options for this in the Theme Customizer, or for each property specifically in the property\'s options. It will only be shown on single property pages', THEMENAME )
        	)
        );
    }

	// 1.2 Backend Form
	function form( $instance ) {
		$defaults = array(
			'title'              => '',

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

        <p class='description'>
	        <?php echo sprintf( __( 'Estatement allows you to customize the auto-reply text and which email the messages are sent to. This can be done in the <a href="%s">Theme Customizer</a> globally, or it can be set individually for each property in the property options', THEMENAME ), admin_url( 'customize.php' ) ) ?>
        </p>

        <?php
    }

	// 1.3 Save Widget Options
	function update( $new_instance, $old_instance ) {
  		$new_instance[$this->image_field] = intval( strip_tags( $new_instance[$this->image_field] ) );
        return $new_instance;
    }

	// 1.4 Frontend Widget Display
	function widget( $args, $instance ) {
		global $post, $wpdb;
		echo $args['before_widget'];
		echo $args['before_title'] . $instance['title'] .  $args['after_title'];
		?>

		<form method='post' action='<?php echo admin_url( 'admin-ajax.php' ) ?>'>
			<input type='text' name='name' placeholder='<?php _e( 'your name', THEMENAME ) ?>'>
			<input type='text' name='email' placeholder='<?php _e( 'your email', THEMENAME ) ?>'>
			<input type='text' name='phone' placeholder='<?php _e( 'your phone number', THEMENAME ) ?>'>
			<textarea name='message' placeholder='<?php _e( 'your message', THEMENAME ) ?>'></textarea>
			<input type='hidden' name='action' value='send_contact_message'>
			<input type='hidden' name='post_id' value='<?php echo $post->ID ?>'>
			<?php wp_nonce_field( 'send_contact_message_nonce' ); ?>
			<div class='text-right'>
				<input type='submit' class='button' value='<?php _e( 'Send', THEMENAME ) ?>'>
			</div>
		</form>
		<?php
		echo $args['after_widget'];
    }
}


/***********************************************/
/*          2. Widget Registration             */
/***********************************************/

register_widget('bshPropertyContactWidget');

?>