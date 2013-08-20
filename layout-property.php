<div <?php post_class( 'layout-property' ) ?>>

	<?php if( !empty( $_GET['message_sent'] ) ) : ?>
		<div class="alert-box success" data-alert>
		  <?php
			  $contact_success_message = get_theme_mod('contact_success_message');
			  $contact_success_message = ( empty( $contact_success_message ) ) ? ' Thank you for your message' : $contact_success_message;
			  echo $contact_success_message;
		  ?>
		  <a href="#" class="close">&times;</a>
		</div>
	<?php endif ?>

<?php

	$top_element = get_post_meta( $post->ID, '_est_top_element', true );
	$top_element = ( empty( $top_element ) ) ? 'slider' : $top_element;

	if( $top_element != 'none' ) {
		$thumbnail_id = get_post_thumbnail_id();
		$images = array();

		if( $top_element == 'thumbnail' ) {
			$large = wp_get_attachment_image_src( $thumbnail_id, 'est_large' );
			$small = wp_get_attachment_image_src( $thumbnail_id, 'est_small' );
			$images[$thumbnail_id] = array(
				'large' => $large[0],
				'small' => $small[0]
			);
		}
		else {
			$attachments = get_posts(array(
				'post_type' => 'attachment',
				'post_status' => 'any',
				'post_parent' => get_the_ID(),
				'posts_per_page' => -1
			));
			foreach( $attachments as $attachment ) {
				$large = wp_get_attachment_image_src( $attachment->ID, 'est_large' );
				$small = wp_get_attachment_image_src( $attachment->ID, 'est_small' );
				$images[$attachment->ID] = array(
					'large' => $large[0],
					'small' => $small[0]
				);
			}

			if( !empty( $thumbnail_id ) AND in_array( $thumbnail_id, array_keys( $images ) ) ) {
				$images[0] = $images[$thumbnail_id];
				unset( $images[$thumbnail_id] );
			}
			elseif( !empty( $thumbnail_id ) AND !in_array( $thumbnail_id, array_keys( $images ) ) ) {
				$large = wp_get_attachment_image_src( $thumbnail_id, 'est_large' );
				$small = wp_get_attachment_image_src( $thumbnail_id, 'est_small' );
				$images[0] = array(
					'large' => $large[0],
					'small' => $small[0]
				);
			}
		}
		ksort( $images );
		$imageCount = count( $images );
	}
?>

<?php if( $top_element != 'none' ) : ?>
	<div id='post-slider'>
		<?php
			$detail = get_post_meta( get_the_ID(), '_est_ribbon_field', true );
			$detail = ( empty( $detail ) OR $detail == 'default' ) ? get_theme_mod( 'property_ribbon_field' ) : $detail;
			$detail = ( empty( $detail ) ) ? '_est_meta_price' : $detail;
			$value = get_post_meta( get_the_ID(), $detail ) ;

			$value = est_customdata_value( $detail, $value );
			if( !empty( $value ) ) :
		?>
			<div id='price-flag'><span class='price'><?php echo $value ?></span></div>
		<?php endif ?>
		<div id="property-slider" class="flexslider">
			<ul class="slides">
				<?php foreach( $images as $image ) : ?>
					<li>
						<img src="<?php echo $image['large'] ?>" />
					</li>
				<?php endforeach ?>
			</ul>
		</div>

		<?php if( $imageCount > 1 ) : ?>
		<div id="property-carousel" class="flexslider carousel">
			<ul class="slides">
				<?php foreach( $images as $image ) : ?>
					<li>
						<img src="<?php echo $image['small'] ?>" />
					</li>
				<?php endforeach ?>
			</ul>
		</div>
		<?php endif ?>
	</div>
<?php endif ?>

<div class='content'>
	<?php the_content() ?>
</div>

<?php
if( get_theme_mod( 'property_commenting' ) == 'yes' ) {
	comments_template();
}
?>

</div>