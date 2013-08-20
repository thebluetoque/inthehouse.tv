<?php
	global $query_string;
	get_header();
	query_posts( $query_string . '&post_type=property&posts_per_page=3' );
?>


<div class='row mt44'>


	<div class='large-12 small-12 columns'>
		<div id='siteContent'>
			<div class='row'>

				<div class='columns large-12 small-12'>
					<?php
						$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
						echo '<div class="page-title mb22">';
						$title = sprintf( __( '%s\'s Properties', THEMENAME ), $author->display_name );
						echo do_shortcode( '[title icon="home"]' . $title . '[/title]' );
						echo '</div>';
					?>
				</div>


				<div id='siteMain' class='<?php echo bsh_content_classes() ?> columns'>
					<?php
						while ( have_posts() ) {
							the_post();
							get_template_part( 'layout-property', 'list' );
						}

						bsh_pagination();
					?>
				</div>

				<?php if( bsh_has_sidebar() ) : ?>
					<div id='siteSidebar' class='small-12 large-4 columns <?php echo bsh_sidebar_classes() ?>'>
						<?php dynamic_sidebar( 'Agent Page Sidebar' ) ?>
					</div>
				<?php endif ?>

			</div>
		</div>
	</div>
</div>

<?php get_footer() ?>