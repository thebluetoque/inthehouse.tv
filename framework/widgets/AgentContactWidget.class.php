<?php
/***********************************************/
/*               About This File               */
/***********************************************/
/*
	This file contains the contact widget
	This widget displays contact information,
	social links and customized text.
*/

/***********************************************/
/*              Table of Contents              */
/***********************************************/
/*
	1. Contact Widget Class
		1.1 Constructor
		1.2 Backend Form
		1.3 Save Widget Options
		1.4 Frontend Widget Display

	2. Widget Registration

*/

/***********************************************/
/*          1. Contact Widget Class            */
/***********************************************/

class bshAgentContactWidget extends WP_Widget {

	// 1.1 Constructor
	function bshAgentContactWidget() {
        parent::WP_Widget(
        	false,
        	__( 'Estatement: Agent Contact Widget', THEMENAME ),
        	array(
        		'description' => __( 'This widget displays a nicely formatted contact sheet on Agent pages. The widget will not show up on other pages', THEMENAME )
        	),
        	array(
        		'width' => '400px'
        	)
        );
    }

	// 1.2 Backend Form
	function form( $instance ) {
		$defaults = array(
			'title'              => '',
			'show_description'   => 'yes'
		);
		$values = wp_parse_args( $instance, $defaults );
		?>
        <p>
        	<label for='<?php echo $this->get_field_id('title'); ?>'>
        		<?php _e( 'Title:', THEMENAME ); ?>
        		<input class='widefat' id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $values['title']; ?>" />
        	</label>
        </p>

        <p>
        	<label>
        		<?php _e( 'Show User Description:', THEMENAME ); ?><br>
        		<?php $checked = ( $values['show_description'] == 'yes' ) ? 'checked="checked"' : '' ?>
        		<input <?php echo $checked ?> type='checkbox' id='<?php echo $this->get_field_id( 'show_description' ); ?>' name='<?php echo $this->get_field_name( 'show_description' ); ?>' value='yes' /><label for='<?php echo $this->get_field_id( 'show_description' ); ?>'> <?php _e( 'Show agent\'s description', THEMENAME ) ?></label>
        	</label>
        </p>




        <?php
    }

	// 1.3 Save Widget Options
	function update( $new_instance, $old_instance ) {
		$new_instance['show_description'] = ( $new_instance['show_description'] == 'yes' ) ? 'yes' : 'no';
        return $new_instance;
    }

	// 1.4 Frontend Widget Display
	function widget( $args, $instance ) {
		global $post, $wpdb;

		if( is_singular( 'property' ) ) {
			$agents = get_post_meta( $post->ID, '_est_agent' );
			$agents = empty( $agents ) ? array( $post->post_author ) : $agents;

			$i = 0;
			foreach( $agents as $agent_id ) {
				$agent = get_userdata( $agent_id );
				$instance['first'] = ( $i == 0 ) ? true : false;
				$instance['multiple'] = true;
				$this->widget_content( $args, $instance, $agent );
				$i++;
			}
		}
		elseif( isset( $instance['agents'] ) ) {
			foreach( $instance['agents'] as $agent ) {
				$instance['first'] = ( $i == 0 ) ? true : false;
				$instance['multiple'] = true;
				$this->widget_content( $args, $instance, $agent );
				$i++;
			}
		}
		else {
			$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
			$this->widget_content( $args, $instance, $author );
		}

    }


    function widget_content( $args, $instance, $author ) {
		echo $args['before_widget'];
		if( !empty( $instance['title'] ) AND !isset( $instance['multiple'] ) ) {
			echo $args['before_title'] . $instance['title'] .  $args['after_title'];
		}

		if( !empty( $instance['first'] ) AND !empty( $instance['title'] ) ) {
			echo '<div class="agent-title">' . $args['before_title'] . $instance['title'] .  $args['after_title'] . '</div>';
		}

		echo '<header>';
		echo get_avatar( $author->ID, 80 );
		echo '<div class="agent-details">';
			echo '<h1 class="agent-name"><a href="' . get_author_posts_url( $author->ID ) . '">' . $author->display_name . '</a></h1>';
			if( !empty( $author->position ) ) {
				echo '<div class="agent-position">' . $author->position . '</div>';
			}

			if( !empty( $author->phone_number ) OR !empty( $author->user_email ) ) {

				$color = ( $args['id']  === 'footerbar' ) ? 'white' : 'black';

				echo '<ul class="contact m22">';
					if( !empty( $author->phone_number ) ) {
						echo '<li class="phoneNumber"><span class="icon"><img alt="' . __( 'Phone Icon', THEMENAME ) . '" src="' . get_template_directory_uri() . '/images/icons/glyphicons/' . $color . '/14x14/iphone.png"></span> <a href="tel:' . str_replace( ' ', '', $author->phone_number ) . '">' . $author->phone_number . '</a></li>';
					}
					if( !empty( $author->user_email ) ) {
						echo '<li class="email"><span class="icon"><img alt="' . __( 'Envelope Icon', THEMENAME ) . '" src="' . get_template_directory_uri() . '/images/icons/glyphicons/' . $color . '/14x14/envelope.png"></span> <a href="mailto:' . $author->user_email . '">' . $author->user_email . '</a></li>';
					}

				echo '</ul>';
			}
		echo '</div>';
		echo '</header>';

		if( !empty( $author->twitter_username ) OR !empty( $author->facebook_link ) OR !empty( $author->linkedin_link ) OR !empty( $author->flickr_link ) OR !empty( $author->pinterest_link ) ) {
			echo '<div class="social">';
			if( !empty( $author->twitter_username ) ) {
				echo '<a class="twitter socialIcon" href="http://twitter.com/' . $author->twitter_username .'"></a>';
			}
			if( !empty( $author->facebook_profile ) ) {
				echo '<a class="facebook socialIcon" href="' . $author->facebook_profile .'"></a>';
			}
			if( !empty( $author->linkedin_profile ) ) {
				echo '<a class="linkedin socialIcon" href="' . $author->linkedin_profile .'"></a>';
			}
			if( !empty( $author->flickr_profile ) ) {
				echo '<a class="flickr socialIcon" href="' . $author->flickr_profile .'"></a>';
			}
			if( !empty( $author->pinterest_profile ) ) {
				echo '<a class="pinterest socialIcon" href="' . $author->pinterest_profile .'"></a>';
			}
			echo '</div>';
		}

		if( !empty( $author->description ) AND $instance['show_description'] == 'yes' ) {
			echo '<div class="text mb22">' . wpautop( $author->description ) . '</div>';
		}


		echo $args['after_widget'];

    }

}


/***********************************************/
/*          2. Widget Registration             */
/***********************************************/

register_widget( 'bshAgentContactWidget' );

?>