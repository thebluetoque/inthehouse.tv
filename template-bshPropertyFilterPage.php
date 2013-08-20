<?php
/*
Template Name: Property Filter
*/
get_header();
while( have_posts() ) : the_post();
	global $details, $search;
	$search = array( 'customdatas' => array(), 'custom_taxonomies' => array() );
	$search['customdatas'] = get_post_meta( $post->ID, '_est_customdatas', true );
	$search['custom_taxonomies'] = get_post_meta( $post->ID, '_est_taxonomies', true );
	$details = get_search_options( $search );

	$position = get_post_meta( get_the_ID(), '_est_filter_location', true );
	$class_content = ( $position == 'left' ) ? 'push-4' : '';
	$class_filter = ( $position == 'left' ) ? 'pull-8' : '';
?>

<div class='row mt44'>
	<div class='large-8 small-12 columns <?php echo $class_content ?>'>
		<div id='siteContent' class='nopadding nobgcolor'>
			<div id='filterNoResults' style='display:none'>
				<h1><?php echo get_post_meta( get_the_ID(), '_est_no_results_title', true ) ?></h1>
				<p><?php echo get_post_meta( get_the_ID(), '_est_no_results_message', true ) ?></p>
			</div>
			<div id='propertyFilter'>
			<?php
			global $wp_query;
			$temp_query = $wp_query;
			$wp_query = null;
			$args = array(
				'post_type' => 'property',
				'posts_per_page' => -1
			);
			$wp_query = new WP_Query( $args );
			if( have_posts() ) {
				while( have_posts() ) {
					the_post();
					get_template_part( 'layout-property-filter' );
				}
				?>

				<?php

				bsh_pagination();
			}
			$wp_query = $temp_query;
			wp_reset_postdata();

			?>
			</div>
		</div>
	</div>
	<div class='large-4 small-12 columns <?php echo $class_filter ?>' id='siteSidebar'>
		<form class='custom inContentSearch' id='filterPageSearch'>
			<?php
				$title = get_post_meta( $post->ID, '_est_search_title', true );
				if( !empty( $title ) ) :
			?>
			<div class='searchTitle'>
				<div class='title'><?php echo $title ?></div>
			</div>
			<?php endif ?>
			<?php

				est_vertical_search( $details, array( 'sliders' => false ) );
			?>
		</form>

		<?php dynamic_sidebar( bsh_get_sidebar() ) ?>

	</div>


</div>


<?php
	endwhile;
	get_footer();
?>