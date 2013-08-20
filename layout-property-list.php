<?php
	global $options;

	$meta = get_post_meta( get_the_ID() );

	$subtitle_fields = get_option( 'est_property_subtitles' );
	$subtitle = '';
	if( empty( $subtitle_fields ) ) {
		if( !empty( $meta['_est_meta_state'][0] ) AND !empty( $meta['_est_meta_city'][0] ) ) {
			$subtitle = $meta['_est_meta_city'][0] . ', ' . $meta['_est_meta_state'][0];
		}
		elseif( empty( $meta['_est_meta_state'][0] ) AND !empty( $meta['_est_meta_country'][0] ) AND !empty( $meta['_est_meta_city'][0] ) )  {
			$subtitle = $meta['_est_meta_city'][0] . ', ' . $meta['_est_meta_country'][0];
		}
		elseif( empty( $meta['_est_meta_state'][0] ) AND empty( $meta['_est_meta_country'][0] ) AND !empty( $meta['_est_meta_city'][0] ) )  {
			$subtitle = $meta['_est_meta_city'][0];
		}
		elseif( !empty( $meta['_est_meta_state'][0] ) AND empty( $meta['_est_meta_country'][0] ) AND empty( $meta['_est_meta_city'][0] ) )  {
			$subtitle = $meta['_est_meta_state'][0];
		}
	}
	else {
		asort( $subtitle_fields );
		$used_fields = array();
		foreach( $subtitle_fields as $key => $order ) {
			$value = get_post_meta( $post->ID, $key, true );
			if( !empty( $value ) ) {
				$used_fields[] = est_customdata_value( $key, $value, array( 'post_id' => $options['post_id'], 'property_id' => $post->ID ) );
			}
		}
		$subtitle = implode( ', ', $used_fields );
	}
?>
<div <?php post_class('layout-property-list') ?>>

	<div class='row'>
		<?php if( has_post_thumbnail() ) : ?>
			<div class='large-6 small-12 columns'>
				<div class='post-image'>
					<a href='<?php the_permalink() ?>' class='hoverlink hide-for-small'>
						<?php the_post_thumbnail( 'est_card' ) ?>
					</a>
					<a href='<?php the_permalink() ?>' class='hoverlink show-for-small mb11'>
						<?php the_post_thumbnail( 'est_card_wide' ) ?>
					</a>
				</div>
			</div>
		<?php endif ?>

		<div class='large-6 small-12 columns'>
			<hgroup>
				<h1><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h1>
				<?php if( !empty( $subtitle ) ) : ?>
					<h2 class='light'><?php echo $subtitle ?></h2>
				<?php endif ?>
			</hgroup>

			<?php
				show_property_detail_table( get_property_detail_list( $options ), '' )
			?>

		</div>

	</div>


</div>