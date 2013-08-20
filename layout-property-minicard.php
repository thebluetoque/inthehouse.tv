<?php
	global $options;
	$meta = get_post_meta( get_the_ID() );

	$location = '';
	if( !empty( $meta['_est_state'][0] ) AND !empty( $meta['_est_city'][0] ) ) {
		$location = $meta['_est_city'][0] . ', ' . $meta['_est_state'][0];
	}
	elseif( empty( $meta['_est_state'][0] ) AND !empty( $meta['_est_country'][0] ) AND !empty( $meta['_est_city'][0] ) )  {
		$location = $meta['_est_city'][0] . ', ' . $meta['_est_country'][0];
	}
	elseif( empty( $meta['_est_state'][0] ) AND empty( $meta['_est_country'][0] ) AND !empty( $meta['_est_city'][0] ) )  {
		$location = $meta['_est_city'][0];
	}
	elseif( !empty( $meta['_est_state'][0] ) AND empty( $meta['_est_country'][0] ) AND empty( $meta['_est_city'][0] ) )  {
		$location = $meta['_est_state'][0];
	}

	if( empty( $options['details'] ) ) {
		$customdata_defaults = get_option( 'est_customdata_default' );
		$options['details'] = implode( '|', $customdata_defaults );
	}

?>
<div <?php post_class('layout-property-minicard mb22small') ?>>

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
			<?php if( !empty( $location ) ) : ?>
				<h2 class='light'><?php echo $location ?></h2>
			<?php endif ?>
		</hgroup>

		<div class='details'>
			<div class="info light">
			<?php
				$details = get_property_detail_list( $options );
				$details = array_slice( $details, 0, 2 );
				$display = array();
				foreach( $details as $detail ) {
					$display[] = $detail['value'];
				}
				echo implode( ' - ', $display );
			?>
			</div>
			<a class='button small' href='<?php the_permalink() ?>'><?php _e( 'details', THEMENAME ) ?></a>

		</div>
	</div>

</div>