<?php
/*
Template Name: Property Listing
*/
get_header();
global $options;

$options['customdatas'] = get_post_meta( $post->ID, '_est_customdata', true );
$options['single_address'] = get_post_meta( $post->ID, '_est_single_field_address', true );
$options['custom_taxonomies'] = get_post_meta( $post->ID, '_est_custom_taxonomies', true );
$options['single_address_order'] = get_post_meta( $post->ID, '_est_single_field_address_order', true );
$options['_est_single_field_address_name'] = get_post_meta( $post->ID, '_est_single_field_address_name', true );

$options['post_id'] = $post->ID;
?>

<div class='row mt44'>
	<div class='large-12 small-12 columns'>
		<div id='siteContent'>

			<?php
				if( bsh_show_title() ) {
					echo '<div class="page-title">';
					echo do_shortcode( '[title icon="home"]' . the_title( '', '', false ) . '[/title]' );
					echo '</div>';
				}

			?>

			<div class='row'>
				<div id='siteMain' class='<?php echo bsh_content_classes() ?> columns'>


				<?php
					$show_search = get_post_meta( $post->ID, '_est_show_search', true );
					if( $show_search == 'yes' OR ( $show_search == 'search' AND !empty( $_GET ) ) ) :
				?>
				<form class='custom inContentSearch'>
					<?php
						$title = get_post_meta( $post->ID, '_est_search_title', true );
						if( !empty( $title ) ) :
					?>
					<div class='searchTitle'>
						<div class='title'><?php echo $title ?></div>
					</div>
					<?php endif ?>
					<?php
						$search = array( 'customdatas' => array(), 'custom_taxonomies' => array() );
						$search['customdatas'] = get_post_meta( $post->ID, '_est_customdatas', true );
						$search['custom_taxonomies'] = get_post_meta( $post->ID, '_est_taxonomies', true );

						$details = get_search_options( $search );
						est_vertical_search( $details );
						$text = get_post_meta( $post->ID, '_est_search_button_text', true );
						$text = ( empty( $text ) ) ? 'Search' : $text;
					?>
						<div class='form-row row mt11'>
							<div class='small-12 large-12 columns text-right'>
								<input type='submit' class='button' value='<?php echo $text ?>'>
							</div>
						</div>
				</form>
				<?php endif ?>

				<div class='property-list'>
					<?php
					while ( have_posts() ) : the_post();
					$temp_query = $wp_query;
					$wp_query = null;

					$metas = get_post_meta( $post->ID );
					$posts_per_page = ( !empty( $metas['_est_count'][0] ) ) ? $metas['_est_count'][0] : get_option( 'posts_per_page' );
					global $paged;
					$args = array(
						'post_type' => 'property',
						'posts_per_page' => $posts_per_page,
						'paged' => $paged
					);


					if( !empty( $metas['_est_property_category'][0] ) OR !empty( $metas['_est_property_type'][0] ) ) {
						$args['tax_query'] = array();
						if( !empty( $metas['_est_property_category'][0] ) ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'property_category',
								'field'    => 'ID',
								'terms'    => unserialize( $metas['_est_property_category'][0] )
							);
						}
						if( !empty( $metas['_est_property_type'][0] ) ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'property_type',
								'field'    => 'ID',
								'terms'    => unserialize( $metas['_est_property_type'][0] ),
							);
						}
					}

					$search_args = get_args_from_search();

					$args = wp_parse_args( $search_args, $args );

					if( !empty( $args['meta_query'] ) AND count( $args['meta_query'] ) > 1 ) {
						$args['meta_query']['relation'] = 'AND';
					}


					$wp_query = new WP_Query( $args );
					if( have_posts() ) {
						while( have_posts() ) {
							the_post();
							get_template_part( 'layout-property-list' );
						}

						bsh_pagination();
					}
					else {
						bsh_no_posts();
					}

					$wp_query = $temp_query;
					wp_reset_postdata();
					endwhile;
					?>
					</div>
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

<?php get_footer(); ?>
