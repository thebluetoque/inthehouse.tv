<?php
global $post;
if( !empty( $post ) ) {
	$template = get_post_meta( $post->ID, '_wp_page_template', true );
}

$class = ( !empty( $post ) AND substr_count( $template, 'template-bshMapPage.php' ) > 0 AND get_post_meta( $post->ID, '_est_full_page_map', true ) != 'no' ) ? 'fullheight' : '';
?><!DOCTYPE html>
<!--[if IE 8]>
	<html class="no-js lt-ie9 <?php echo $class ?>" lang="en">
<![endif]-->
<!--[if gt IE 8]><!--> <html class="nojs <?php echo $class ?>" <?php language_attributes(); ?>> <!--<![endif]-->

<head>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="author" href="<?php echo get_template_directory_uri() ?>/humans.txt" />

	<title>
		<?php
			global $page, $paged;
			@wp_title( '|', true, 'right' );
			bloginfo( 'name' );
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) ) {
				echo " | $site_description";
			}
			if ( $paged >= 2 || $page >= 2 ) {
				echo ' | Page ' . max( $paged, $page );
			}

		?>
	</title>

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/javascripts/vendor/respond.min.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/javascripts/vendor/html5.js" type="text/javascript"></script>
	<![endif]-->

	<meta property="og:title" content="<?php echo bsh_page_title() ?>"/>
	<meta property="og:url" content="<?php echo bsh_canonical_url() ?>"/>
	<meta property="og:site_name" content="<?php bloginfo( 'name' ) ?>"/>
	<meta property="og:image" content="<?php echo bsh_page_image() ?>"/>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head() ?>
</head>
<body <?php body_class(); ?>>
<div id="fb-root"></div>

<?php
	$headerLogo  = get_theme_mod( 'logo_image' );
	$headerLogoRetina  = get_theme_mod( 'logo_image_retina' );
	$style_image = ( empty( $headerLogo ) ) ? 'style="display:none"' : '';
	$style_text  = ( empty( $headerLogo ) ) ? '' : 'style="display:none"';
?>
<div id='siteHeader'>
	<div class='row'>
		<div class='large-12 small-12 columns'>

			<!-- Header Logo -->

			<div id='headerLogo'>
				<a href='<?php echo site_url() ?>'><img <?php echo $style_image ?> src='<?php echo $headerLogo ?>' class='retina' alt='<?php sprintf( _e( 'Logo For %s', THEMENAME ), get_bloginfo() ) ?>' data-retina='<?php echo $headerLogoRetina ?>'></a>
				<hgroup <?php echo $style_text ?>>
					<h1><a href='<?php echo site_url() ?>'><?php bloginfo() ?></a></h1>
					<h2><a href='<?php echo site_url() ?>'><?php bloginfo( 'description' ) ?></a></h2>
				</hgroup>
			</div>

			<!-- End Header Logo -->

			<div id='headerContent'>
				<!-- Contact Info -->

				<?php
					// Needs to be added to customizer.js as well
					$phone = get_theme_mod( 'header_contact_phone' );
					$contact_info = array(
						'header_contact_location' => array(
							'icon'  => 'icons/glyphicons/black/14x14/pin.png',
							'value' => get_theme_mod( 'header_contact_location' ),
							'alt'   => __( 'Map pin icon', THEMENAME )
						),
						'header_contact_phone' => array(
							'icon'  => 'icons/glyphicons/black/14x14/iphone.png',
							'value' => '<a href="tel:' . str_replace( array('+', '-' ), '', filter_var($phone, FILTER_SANITIZE_NUMBER_FLOAT)) . '">' . $phone . '</a>',
							'alt'   => __( 'Smartphone icon', THEMENAME )
						),
						'header_contact_email' => array(
							'icon' => 'icons/glyphicons/black/14x14/envelope.png',
							'value' => get_theme_mod( 'header_contact_email' ),
							'alt'   => __( 'Envelope icon', THEMENAME )
						)
					);
					$contact_info = bs_filter_option_array( $contact_info );

					if( !empty( $contact_info ) ) {
						echo "<ul id='headerContact'>";
						foreach( $contact_info as $key => $data ) {
						?>
							<li id='<?php echo $key ?>'>
								<img class='retina' src='<?php echo get_template_directory_uri() ?>/images/<?php echo $data['icon'] ?>' alt='<?php echo $data['alt'] ?>'>
								<span><?php echo $data['value'] ?></span>
							</li>
						<?php
						}
						echo "</ul>";
					}
				?>

				<!-- End Contact Info -->
			</div> <!-- End Header Content -->

			<!-- Header Menu -->

			<div id='headerMenu'>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'header_menu',
					'fallback_cb'    => 'bs_nav_menu'
				));
				?>
			</div>

			<!-- End Header Menu -->


		</div> <!-- columns -->

	</div> <!-- row -->

</div> <!-- End siteHeader -->
<?php if( substr_count( $template, 'template-bshSearchPage.php' ) == 0 AND substr_count( $template, 'template-bshMapPage.php' ) == 0  ) : ?>
<?php get_template_part( 'module', 'featured' ) ?>
<?php endif ?>