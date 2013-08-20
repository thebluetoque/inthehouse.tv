<?php
	global $options;
	$meta = get_post_meta( get_the_ID() );
	$subtitle = est_property_subtitle( $options );
?>
<div <?php post_class('layout-property-card') ?>>

	<div class='post-image'>
	<?php if( has_post_thumbnail() ) : ?>
		<a href='<?php the_permalink() ?>' class='hoverlink hide-for-small'>
			<?php the_post_thumbnail( 'est_card' ) ?>
		</a>
		<a href='<?php the_permalink() ?>' class='hoverlink show-for-small'>
			<?php the_post_thumbnail( 'est_card_wide' ) ?>
		</a>
	<?php endif ?>
	</div>

	<div class='post-content'>
		<hgroup class='mb22'>
			<h1><a class='text-link body-text-color' href='<?php the_permalink() ?>'><?php the_title() ?></a></h1>
			<?php if( !empty( $subtitle ) ) : ?>
				<h2 class='light'><?php echo $subtitle ?></h2>
			<?php endif ?>
		</hgroup>

		<?php show_property_detail_table( get_property_detail_list( $options ) ) ?>

		<a class='button small' href='<?php the_permalink() ?>'><?php _e( 'details', THEMENAME ) ?></a>
	</div>

</div>