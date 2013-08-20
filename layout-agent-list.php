<?php
	global $user, $options;
	$instance['agents'] = array( $user );
	$instance['show_description'] = 'yes';

	$show_properties = ( !empty( $options['_est_show_properties'] ) AND $options['_est_show_properties'] == 'hide' ) ? false : true;

	$class = 'large-12';
	if( $show_properties == true ) {
		$class = 'large-8';
	}

	$properties = est_get_agent_properties( $user->ID );
	if( !empty( $properties ) ) :
?>


<div class='layout-agent-list'>
<div class='row'>
	<div class='<?php echo $class ?> small-12 columns'>
		<?php the_widget( 'bshAgentContactWidget', $instance ) ?>
	</div>
	<?php if( $show_properties == true ) : ?>
	<div class='large-4 small-12 columns'>
		<?php
			$posts_per_page = ( empty( $user->description ) ) ? 3 : 5;
			global $wp_query;
			$temp_query = $wp_query;
			$wp_query = null;
			$args = array(
				'post_type' => 'property',
				'post_status' => 'publish',
				'post__in' => $properties,
				'posts_per_page' => $posts_per_page
			);
			$wp_query = new WP_Query( $args );

			if( have_posts() ) {
				echo '<h3>' . __( 'Properties', THEMENAME ) . '</h3>';
				echo '<ul class="side-nav">';
				while( have_posts() ) {
					the_post();
				?>
					<li><a href='<?php the_permalink() ?>'><?php the_title() ?></a></li>
				<?php

				}

				if( $wp_query->found_posts > $posts_per_page ) {
					echo '<li><a href="' . get_author_posts_url( $user->ID ) . '">' . __( 'more properties &raquo;', THEMENAME ) . '</a></li>';
				}

				echo '</ul>';
			}
			$wp_query = $temp_query;
			wp_reset_postdata();
		?>
	</div>
	<?php endif ?>
</div>
</div>
<?php endif ?>