<?php
/*
Template Name: Agent List
*/
get_header();
global $options;

$show_admins = get_post_meta( get_the_ID(), '_est_show_admins', true );
$users['administrator'] = ( $show_admins == 'hide' ) ? array() : @get_users( array( 'role' => 'administrator' ) );
$users['agent'] = @get_users( array( 'role' => 'agent' ) );
$users = array_merge( $users['administrator'], $users['agent'] );

while( have_posts() ) : the_post();
$options['_est_show_properties'] = get_post_meta( get_the_ID(), '_est_show_properties', true );


?>

<div class='row mt44'>
	<div class='large-12 small-12 columns'>
		<div id='siteContent'>

			<?php
				if( bsh_show_title() ) {
					echo '<div class="page-title">';
					echo do_shortcode( '[title icon="nameplate"]' . the_title( '', '', false ) . '[/title]' );
					echo '</div>';
				}

			?>

			<div class='row'>
				<div id='siteMain' class='<?php echo bsh_content_classes() ?> columns'>

					<?php if( !empty( $post->post_content ) ) : ?>
					<div class='content mb44'>
						<?php the_content() ?>
					</div>
					<?php endif ?>

					<div class='agent-list'>
					<?php
						foreach( $users as $user ) {
							get_template_part( 'layout', 'agent-list' );
						}
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

<?php endwhile; get_footer(); ?>
