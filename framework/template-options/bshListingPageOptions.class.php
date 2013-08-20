<?php
/***********************************************/
/*               About This File               */
/***********************************************/
/*
	This file generates the options needed for
	the default page template.
*/

/***********************************************/
/*              Table of Contents              */
/***********************************************/
/*

	1. Default Page Options Class
		1.1 Constructor
		1.2 Options Box Content

	2. Instantiating The Options

*/

/***********************************************/
/*       1. Default Page Options Class         */
/***********************************************/

class bshListingPageOptions extends bshOptions {

	// 1.1 Constructor
	function __construct() {
		$args = array(
			'title'     => __( 'Listing Page Options', THEMENAME ),
			'post_type' => 'page',
			'template'  => 'template-bshListingPage.php',
			'context'   => 'normal',
			'priority'  => 'high'
		);
        parent::__construct( $args );
        $this->setup_options();
	}

	// 1.2 Options Box Content
    public function options_box_content( $post ) {
        ?>
        	<div id='bshLogo'></div>
        	<div id='optionsContainer'>
        		<div id='menuBackground'></div>

	        	<ul id='bshMenu'>
	        		<li class='active'><?php _e( 'Structure', THEMENAME ) ?></li>
	        		<li><?php _e( 'Listing Properties', THEMENAME ) ?></li>
	        		<li><?php _e( 'Advanced Search', THEMENAME ) ?></li>
	        		<li><?php _e( 'Property Details', THEMENAME ) ?></li>
	        		<li><?php _e( 'Help', THEMENAME) ?></l1>
	        		<li><?php _e( 'Shortcode Guide', THEMENAME) ?></l1>
	        		<li><?php _e( 'Get Support', THEMENAME) ?></l1>
	        	</ul>
	        	<div id='bshOptions'>
		        	<input id='bshSaveTop' name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="Update">

	        		<section class='active'>
	        			<div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php echo sprintf( __( 'By default the layout of this page is inherited from the default layout which can be changed in the <a href="%s">Theme Customizer</a>. If you need a different layout on this page, you can override the default setting.', THEMENAME ), esc_url( admin_url( 'customize.php' ) ) ) ?>
        						</div>
        					</div>

	        				<label for='_est_layout' class='sectionTitle'><?php _e( 'Layout', THEMENAME ) ?></label>
	        				<?php
	        					$choices = array(
	        						'Global Setting' => 'default',
	        						'2 Columns - Sidebar on the Right' => '2col_right',
	        						'2 Columns - Sidebar on the Left'  => '2col_left',
	        						'1 Column' => '1col'
	        					)
	        				?>
	        				<ul class='choices'>
		        				<?php
		        					$current = get_post_meta( $post->ID,  '_est_layout', true );

		        					$i=1; foreach( $choices as $choice => $value ) :
		        					$checked = ( $current == $value OR ( empty( $current ) AND $value == 'default' ) ) ? 'checked="checked"' : '';
		        				?>
		        				<li>
		        				<input <?php echo $checked ?> type='radio' id='_est_layout-<?php echo $i ?>' name='_est_layout' value='<?php echo $value ?>'><label for='_est_layout-<?php echo $i ?>'><?php echo $choice ?></label><br>
		        				</li>
		        				<?php $i++; endforeach ?>
	        				</ul>
	        			</div>

	        			<div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php _e( 'By default the title of this page is shown under the header. If you would like to hide this title, check the radio button next to  "Hide"', THEMENAME ) ?>
        						</div>
        					</div>

	        				<label for='_est_title' class='sectionTitle'><?php _e( 'Page Title', THEMENAME ) ?></label>
	        				<?php
	        					$choices = array(
	        						'Show' => 'show',
	        						'Hide' => 'hide',
	        					)
	        				?>
	        				<ul class='choices'>
		        				<?php
		        					$current = get_post_meta( $post->ID, '_est_title', true );
		        					$i=1; foreach( $choices as $choice => $value ) :
		        					$checked = ( $current == $value OR ( empty( $current ) AND $value == 'show' ) ) ? 'checked="checked"' : '';
		        				?>
		        				<li>
		        				<input <?php echo $checked ?> type='radio' id='_est_title-<?php echo $i ?>' name='_est_title' value='<?php echo $value ?>'><label for='_est_title-<?php echo $i ?>'><?php echo $choice ?></label><br>
		        				</li>
		        				<?php $i++; endforeach ?>
	        				</ul>
	        			</div>


	        			<div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php echo sprintf( __( 'If you are using a layout with a sidebar the default sidebar will be shown. You can set the default sidebar in the <a href="%s">Theme Customizer</a>. If you would like to use a different sidebar on this page, choose one here.', THEMENAME ), esc_url( admin_url( 'customize.php' ) ) ) ?>
        						</div>
        					</div>

	        				<label for='_est_sidebar' class='sectionTitle'><?php _e( 'Sidebar', THEMENAME ) ?></label>

	        				<?php
		        				$current = get_post_meta( $post->ID,  '_est_sidebar', true );
								$choices = explode(',', get_theme_mod( 'sidebars' ) );
								$sidebars['default'] = 'Default';
								$sidebars['Sidebar'] = 'Sidebar';
								foreach( $choices as $choice ) {
									$choice = trim( $choice );
									if( !empty( $choice ) ) {
										$sidebars[$choice] = $choice;
									}
								}
	        					$current = get_post_meta( $post->ID, '_est_sidebar', true );
	        				?>
	        				<select id='_est_sidebar' name='_est_sidebar'>
		        				<?php
		        					foreach( $sidebars as $value => $name ) :
		        					$selected = ( $current == $value OR ( empty( $current ) AND $value == 'default' ) ) ? 'selected="selected"' : '';
		        				?>
		        				<option value='<?php echo $value ?>' <?php echo $selected ?>>
		        				<?php echo $name ?>
		        				</option>
		        				<?php endforeach ?>
	        				</select>
	        			</div>

	        		</section>

	        		<section>

	        			 <div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php _e( 'Specify the number of properties that should be shown on each page', THEMENAME ) ?>
        						</div>
        					</div>

        					<?php
        						$value = get_post_meta( $post->ID, '_est_count', true );
        						$value = ( empty( $value ) ) ? get_option( 'posts_per_page' ) : $value;
        					?>
	        				<label for='_est_count' class='sectionTitle'><?php _e( 'Properties Per Page', THEMENAME ) ?></label>
		        			<input type='text' class='widefat' id='_est_count' name='_est_count' value='<?php echo $value ?>'>

	        			</div>


	        			<div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php echo _e( 'By Selecting a number of property types you can narrow the properties shown to only the selected types. If no types are selected all properties will be shown.', THEMENAME ) ?>
        						</div>
        					</div>

	        				<label for='_est_property_type' class='sectionTitle'><?php _e( 'Property Types', THEMENAME ) ?></label>

	        				<?php
	        					$categories = get_terms( 'property_type' );
	        					if( empty( $categories ) ) {
	        						_e( 'You haven\'t created any property types yet. You can do so by clicking on any property item (or creating one) and adding a type on the fly using the "Property Types" box on the right. You can also click on "Property Types" in "Properties" in the left hand menu', THEMENAME );
	        					}
	        					else {

	        					?>

			        				<span class='checkAll'>select all</span>
			        				<span class='checkNone'>select none</span>

			        				<div class='clear'></div>


	        					<?php
		        					$categories_1 = array_slice( $categories, 0, ceil( count( $categories ) / 2 ) );
		        					$categories_2 = array_slice( $categories, ceil( count( $categories ) / 2 ) );
		        				?>
		        				<ul class='choices half-left'>
			        				<?php
			        					$current = get_post_meta( $post->ID,  '_est_property_type', true );

			        					$i=1; foreach( $categories_1 as $category ) :
			        					$checked = ( is_array( $current ) AND in_array( $category->term_id, $current ) ) ? 'checked="checked"' : '';
			        				?>
			        				<li>
			        				<input <?php echo $checked ?> type='checkbox' id='_est_property_type-<?php echo $i ?>' name='_est_property_type[]' value='<?php echo $category->term_id ?>'><label for='_est_property_type-<?php echo $i ?>'><?php echo $category->name ?></label><br>
			        				</li>
			        				<?php $i++; endforeach ?>
		        				</ul>
		        				<ul class='choices half-right'>
			        				<?php

			        					foreach( $categories_2 as $category ) :
			        					$checked = ( is_array( $current ) AND in_array( $category->term_id, $current ) ) ? 'checked="checked"' : '';
			        				?>
			        				<li>
			        				<input <?php echo $checked ?> type='checkbox' id='_est_property_type-<?php echo $i ?>' name='_est_property_type[]' value='<?php echo $category->term_id ?>'><label for='_est_property_type-<?php echo $i ?>'><?php echo $category->name ?></label><br>
			        				</li>
			        				<?php $i++; endforeach ?>
		        				</ul>
		        			<?php } ?>

		        			<input type='hidden' id='_est_property_type-<?php echo $i ?>' name='_est_property_type[]' value='none'>
	        				<div class='clear'></div>

	        			</div>



	        			<div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php echo _e( 'By Selecting a number of property categories you can narrow the properties shown to only the selected categories. If no categories are selected all properties will be shown.', THEMENAME ) ?>
        						</div>
        					</div>

	        				<label for='_est_property_category' class='sectionTitle'><?php _e( 'Property Categories', THEMENAME ) ?></label>

	        				<?php
	        					$categories = get_terms( 'property_category' );
	        					if( empty( $categories ) ) {
	        						_e( 'You haven\'t created any property categories yet. You can do so by clicking on any property item (or creating one) and adding a type on the fly using the "Property Categories" box on the right. You can also click on "Property Categories" in "Properties" in the left hand menu', THEMENAME );
	        					}
	        					else {

	        					?>

			        				<span class='checkAll'>select all</span>
			        				<span class='checkNone'>select none</span>

			        				<div class='clear'></div>


	        					<?php
		        					$categories_1 = array_slice( $categories, 0, ceil( count( $categories ) / 2 ) );
		        					$categories_2 = array_slice( $categories, ceil( count( $categories ) / 2 ) );
		        				?>
		        				<ul class='choices half-left'>
			        				<?php
			        					$current = get_post_meta( $post->ID,  '_est_property_category', true );

			        					$i=1; foreach( $categories_1 as $category ) :
			        					$checked = ( is_array( $current ) AND in_array( $category->term_id, $current ) ) ? 'checked="checked"' : '';
			        				?>
			        				<li>
			        				<input <?php echo $checked ?> type='checkbox' id='_est_property_category-<?php echo $i ?>' name='_est_property_category[]' value='<?php echo $category->term_id ?>'><label for='_est_property_category-<?php echo $i ?>'><?php echo $category->name ?></label><br>
			        				</li>
			        				<?php $i++; endforeach ?>
		        				</ul>
		        				<ul class='choices half-right'>
			        				<?php

			        					foreach( $categories_2 as $category ) :
			        					$checked = ( is_array( $current ) AND in_array( $category->term_id, $current ) ) ? 'checked="checked"' : '';
			        				?>
			        				<li>
			        				<input <?php echo $checked ?> type='checkbox' id='_est_property_category-<?php echo $i ?>' name='_est_property_category[]' value='<?php echo $category->term_id ?>'><label for='_est_property_category-<?php echo $i ?>'><?php echo $category->name ?></label><br>
			        				</li>
			        				<?php $i++; endforeach ?>
		        				</ul>
		        			<?php } ?>

		        			<input type='hidden' id='_est_property_category-<?php echo $i ?>' name='_est_property_category[]' value='none'>
	        				<div class='clear'></div>

	        			</div>

	        		</section>

	        		<section>

	        			<div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php _e( 'Specify when the search options should be shown.', THEMENAME ) ?>
        						</div>
        					</div>

	        				<label for='_est_show_search' class='sectionTitle'><?php _e( 'Show Search Options?', THEMENAME ) ?></label>

	        				<?php
	        					$choices = array(
	        						'Always Show Search' => 'yes',
	        						'Only When User Has Searched' => 'search',
	        						'Never Show' => 'no'
	        					)
	        				?>
	        				<ul class='choices'>
		        				<?php
        							$current = get_post_meta( $post->ID, '_est_show_search', true );
        							$current = ( empty( $current ) ) ? 'no' : $current;

		        					$i=1; foreach( $choices as $choice => $value ) :
		        					$checked = ( $current == $value OR ( empty( $current ) AND $value == 'default' ) ) ? 'checked="checked"' : '';
		        				?>
		        				<li>
		        				<input <?php echo $checked ?> type='radio' id='_est_show_search-<?php echo $i ?>' name='_est_show_search' value='<?php echo $value ?>'><label for='_est_show_search-<?php echo $i ?>'><?php echo $choice ?></label><br>
		        				</li>
		        				<?php $i++; endforeach ?>
	        				</ul>


	        			</div>

	        			 <div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php _e( 'Specify the title of the search box. If left blank the title will be hidden', THEMENAME ) ?>
        						</div>
        					</div>

        					<?php
        						$value = get_post_meta( $post->ID, '_est_search_title', true );
        					?>
	        				<label for='_est_search_title' class='sectionTitle'><?php _e( 'Search Page Title', THEMENAME ) ?></label>
		        			<input type='text' class='widefat' id='_est_search_title' name='_est_search_title' value='<?php echo $value ?>'>

	        			</div>

	        			 <div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php _e( 'Specify the text of the search button', THEMENAME ) ?>
        						</div>
        					</div>

        					<?php
        						$value = get_post_meta( $post->ID, '_est_search_button_text', true );
        					?>
	        				<label for='_est_search_button_text' class='sectionTitle'><?php _e( 'Search Button Text', THEMENAME ) ?></label>
		        			<input type='text' class='widefat' id='_est_search_button_text' name='_est_search_button_text' value='<?php echo $value ?>'>

	        			</div>


	        			 <div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php _e( 'Specify the label of the general search terms field', THEMENAME ) ?>
        						</div>
        					</div>

        					<?php
        						$value = get_post_meta( $post->ID, '_est_search_terms_label', true );
        					?>
	        				<label for='_est_search_terms_label' class='sectionTitle'><?php _e( 'Search Terms Label', THEMENAME ) ?></label>
		        			<input type='text' class='widefat' id='_est_search_terms_label' name='_est_search_terms_label' value='<?php echo $value ?>'>

	        			</div>


	        			<div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php echo _e( 'Select the taxonomies you would like to add to this search page', THEMENAME ) ?>
        						</div>
        					</div>

	        				<label for='_est_details' class='sectionTitle'><?php _e( 'Built In Taxonomies To Show', THEMENAME ) ?></label>

	        				<?php
								$args = array(
								  'public'   => true,
								  '_builtin' => false

								);
								$output = 'names'; // or objects
								$operator = 'and'; // 'and' or 'or'
								$taxonomies = get_option( 'est_taxonomies' );
	        					$selection = get_post_meta( $post->ID, '_est_taxonomies', true );
	        					?>

			        				<span class='checkAll'><?php _e( 'select all', THEMENAME ) ?></span>
			        				<span class='checkNone'><?php _e( 'select none', THEMENAME ) ?></span>

		        				<table class='choices'>
		        					<thead>
		        					<tr>
		        						<th></th>
		        						<th class='text-left'><?php _e( 'Taxonomy', THEMENAME ) ?></th>
		        						<th class='text-left'><?php _e( 'Control Type', THEMENAME ) ?></th>
		        						<th><?php _e( 'Order', THEMENAME ) ?></th>
		        					</tr>
		        					</thead>
		        					<tbody>

			        				<?php
			        					foreach( $taxonomies as $taxonomy => $data ) :
			        					if( empty( $selection ) ) {
			        						$checked = '';
			        					}
			        					else {
			        					$checked = ( !empty( $selection[$taxonomy]['show'] ) AND $selection[$taxonomy]['show'] == 'yes' ) ? 'checked="checked"' : '';
			        					}
			        				?>
			        				<tr>
				        				<td class='checkbox'>
					        				<input <?php echo $checked ?> type='checkbox' id='_est_taxonomies-<?php echo $i ?>' name='_est_taxonomies[<?php echo $taxonomy ?>][show]' value='yes'>
				        				</td>
				        				<td>
					        				<label for='_est_taxonomies-<?php echo $i ?>'><?php echo $data['labels']['name'] ?></label>
				        				</td>
							        	<td>
											<select name='_est_taxonomies[<?php echo $taxonomy ?>][field]'>
				        				<?php
				        					$fields = array(
				        						'select' => 'Dropdown Box',
				        						'slider' => 'Range Slider',
				        						'checkbox' => 'Checkboxes',
												'radio'    => 'Radio Buttons',
				        						'text' => 'Text Field',
				        					);
				        					foreach( $fields as $field => $name ) {
					        					if( empty( $selection ) ) {
					        						$selected = '';
					        					}
					        					else {
					        						$selected = ( $selection[$taxonomy]['field'] == $field ) ? 'selected="selected"' : '';
					        					}
 				        						echo '<option ' . $selected . ' value="' . $field . '">' . $name . '</option>';
				        					}
				        				?>
				        				</td>
				        				<td class='order'>
				        					<?php
				        						$order = ( !empty( $selection[$taxonomy]['order'] ) ) ? $selection[$taxonomy]['order'] : '';
				        					?>
				        					<input type='text' name='_est_taxonomies[<?php echo $taxonomy ?>][order]' value='<?php echo $order ?>'>
				        				</td>

			        				</tr>
			        				<input type='hidden' name='_est_taxonomies[<?php echo $taxonomy ?>][type]' value='taxonomy'>

			        				<?php $i++; endforeach ?>
			        				</tbody>
		        				</table>
	        				<div class='clear'></div>

	        			</div>


	        			<div class='option'>
        					<div class='help'>
        						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
        						<div class='content'>
        						<?php echo _e( 'Select the default in details you would like to add to this search page', THEMENAME ) ?>
        						</div>
        					</div>

	        				<label for='_est_customdatas' class='sectionTitle'><?php _e( 'Built In Details To Show', THEMENAME ) ?></label>

	        				<?php
	        					$details = get_option( 'est_customdata' );
	        					$selection = get_post_meta( $post->ID, '_est_customdatas', true );
	        					?>

			        				<span class='checkAll'><?php _e( 'select all', THEMENAME ) ?></span>
			        				<span class='checkNone'><?php _e( 'select none', THEMENAME ) ?></span>

		        				<table class='choices'>
		        					<thead>
		        					<tr>
		        						<th></th>
		        						<th class='text-left'><?php _e( 'Custom Field', THEMENAME ) ?></th>
		        						<th class='text-left'><?php _e( 'Control Type', THEMENAME ) ?></th>
		        						<th><?php _e( 'Order', THEMENAME ) ?></th>
		        					</tr>
		        					</thead>
		        					<tbody>

			        				<?php
			        					foreach( $details as $key => $datail ) :
			        					$checked = ( !empty( $selection[$key]['show'] ) AND $selection[$key]['show'] == 'yes' ) ? 'checked="checked"' : '';
		        				?>
			        				<tr>
				        				<td class='checkbox'>
					        				<input <?php echo $checked ?> type='checkbox' id='_est_customdatas-<?php echo $i ?>' name='_est_customdatas[<?php echo $key ?>][show]' value='yes'>
				        				</td>
				        				<td>
					        				<label for='_est_customdatas-<?php echo $i ?>'><?php echo $datail['name'] ?></label>
				        				</td>
							        	<td>
											<select name='_est_customdatas[<?php echo $key ?>][field]'>
				        				<?php
				        					$fields = array(
				        						'select' => 'Dropdown Box',
				        						'slider' => 'Range Slider',
				        						'checkbox' => 'Checkboxes',
												'radio'    => 'Radio Buttons',
				        						'text' => 'Text Field',
				        					);
				        					foreach( $fields as $field => $name ) {
												if( empty( $selection )  ) {
													$selected = '';
												}
												else {
				        							$selected = ( $selection[$key]['field'] == $field ) ? 'selected="selected"' : '';
												}
 				        						echo '<option ' . $selected . ' value="' . $field . '">' . $name . '</option>';
				        					}
				        				?>
				        				</td>
				        				<td class='order'>
				        					<?php
				        						$order = ( !empty( $selection[$key]['order'] ) ) ? $selection[$key]['order'] : '';
				        					?>
				        					<input type='text' name='_est_customdatas[<?php echo $key ?>][order]' value='<?php echo $order ?>'>
				        				</td>

			        				</tr>
				        			<input type='hidden' name='_est_customdatas[<?php echo $key ?>][type]' value='customdata'>
		        				<?php $i++; endforeach ?>
			        				</tbody>
		        				</table>
	        				<div class='clear'></div>

	        			</div>

		        	</section>


	        		<section>

	        			<div class='option'>
	    					<div class='help'>
	    						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
	    						<div class='content'>
	    						<?php echo _e( 'Select the taxonomies you would like to display.', THEMENAME ) ?>
	    						</div>
	    					</div>

	        				<label for='_est_custom_taxonomies' class='sectionTitle'><?php _e( 'Select Taxonomies', THEMENAME ) ?></label>


						        <table style='width:100%'>
						        	<tr>
						        		<td></td>
						        		<td><strong><?php _e( 'Custom Taxonomy', THEMENAME ) ?></strong></td>
						        		<td><strong><?php _e( 'Order', THEMENAME ) ?></strong></td>
						        	</tr>
						        <?php
						        	$taxonomies = get_option( 'est_taxonomies' );
						        	$value = get_post_meta( $post->ID, '_est_custom_taxonomies', true );

						        	foreach( $taxonomies as $taxonomy ) :
						        		$checked = ( !empty( $value[$taxonomy['slug']] ) AND $value[$taxonomy['slug']]['show'] == 'yes' ) ? 'checked="checked"' : '';
						        		$order = !empty( $value[$taxonomy['slug']]['order'] ) ? $value[$taxonomy['slug']]['order'] : ''

						        ?>
						        	<tr>
						       			<td>
						       				<input id='_est_custom_taxonomies_<?php echo $taxonomy['slug'] ?>' <?php echo $checked ?> type='checkbox' name='_est_custom_taxonomies[<?php echo $taxonomy['slug'] ?>][show]' value='yes'>
						       			</td>
						       			<td style='width:60%;'>
							       			<label for='_est_custom_taxonomies_<?php echo $taxonomy['slug'] ?>'><?php echo $taxonomy['labels']['name'] ?></label>
							       		</td>
							       		<td>
							       			<input value='<?php echo $order ?>' type='text' style='width:40px;' name='_est_custom_taxonomies[<?php echo $taxonomy['slug'] ?>][order]'>
							       		</td>
						        	</tr>
						        <?php endforeach ?>
						        </table>

        				</div>


	        			<div class='option'>
	    					<div class='help'>
	    						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
	    						<div class='content'>
	    						<?php echo _e( 'The following section allows you to set up which custom fields are shown. To create your own order make sure to select "custom" from the dropdown selector as well as setting up the order below.', THEMENAME ) ?>
	    						</div>
	    					</div>

	        				<label for='_est_customdata' class='sectionTitle'><?php _e( 'Select Custom Fields', THEMENAME ) ?></label>


						        <table style='width:100%'>
						        	<tr>
						        		<td></td>
						        		<td><strong><?php _e( 'Custom Field', THEMENAME ) ?></strong></td>
						        		<td><strong><?php _e( 'Order', THEMENAME ) ?></strong></td>
						        	</tr>
						        <?php
						        	$value = get_post_meta( $post->ID, '_est_customdata', true );
						        	$customdata = get_option( 'est_customdata' );
						        	foreach( $customdata as $field ) :
						        		$checked = ( !empty( $value[$field['key']] ) AND $value[$field['key']]['show'] == 'yes' ) ? 'checked="checked"' : '';
						        		$order = !empty( $value[$field['key']]['order'] ) ? $value[$field['key']]['order'] : ''

						        ?>
						        	<tr>
						       			<td>
						       				<input id='est_customfields_<?php echo $field['key'] ?>' <?php echo $checked ?> type='checkbox' name='_est_customdata[<?php echo $field['key'] ?>][show]' value='yes'>
						       			</td>
						       			<td style='width:60%;'>
							       			<label for='est_customfields_<?php echo $field['key'] ?>'><?php echo $field['name'] ?></label>
							       		</td>
							       		<td>
							       			<input value='<?php echo $order ?>' type='text' style='width:40px;' name='_est_customdata[<?php echo $field['key'] ?>][order]'>
							       		</td>
						        	</tr>
						        <?php endforeach ?>
						        </table>

        				</div>

	        			<div class='option'>
	    					<div class='help'>
	    						<span class='title'><?php _e( 'help', THEMENAME ) ?></span>
	    						<div class='content'>
	    						<?php echo _e( 'If you select to show the location as a single field, all the location data will be condensed into one row. If you choose to show only selected fields only the location fields selected will be added to this single row.', THEMENAME ) ?>
	    						</div>
	    					</div>


	        				<label for='__est_single_field_address' class='sectionTitle'><?php _e( 'Condense Location', THEMENAME ) ?></label>

	        				<?php
	        					$value = get_post_meta( $post->ID, '_est_single_field_address', true );
	        					$checked = ( $value == 'yes' ) ? 'checked="checked"' : ''
	        				?>

						    <table style='width:100%'>
						        	<tr>
						        		<td></td>
						        		<td><strong><?php _e( 'Custom Field', THEMENAME ) ?></strong></td>
						        		<td><strong><?php _e( 'Order', THEMENAME ) ?></strong></td>
						        	</tr>

						    	<tr>
						    		<td>
							    		<input <?php echo $checked ?> type='checkbox' id='_est_single_field_address' name='_est_single_field_address' value='yes'>
							    	</td>
							    	<td style='width:60%'>
								    	<label for='_est_single_field_address'><?php _e( 'Show Location as single field', THEMENAME ); ?></label>
								    </td>
								    <td>
								    	<?php $value = get_post_meta( $post->ID, '_est_single_field_address_order', true ) ?>
							       		<input type='text' style='width:40px;' name='_est_single_field_address_order' value='<?php echo $value ?>'>
								    </td>
								</tr>
							</table>

	        				<?php
	        					$value = get_post_meta( $post->ID, '_est_single_field_address_name', true );
								$value = ( empty( $value ) ) ? __( 'Location: ', THEMENAME ) : $value;
 	        				?>
							<label><?php _e( 'Condensed Location Title:', THEMENAME ) ?> </label>
							<input type='text' name='_est_single_field_address_name' value='<?php echo $value ?>'>

		        		</div>


	        		</section>


	        		<section class='helpSection'>
	        			<?php
	        			_e('
							<p>The property listing template allows you to create a list of properties. Using the page options you can narrow this list down to a subset of your properties which is great for creating separate lists for different property types.</p>
<ul>
<li>Structure
<ul>
<li><strong>Layout</strong>: Determines the type of layout you would like for this specific page</li>
<li><strong>Page Title</strong>: Allows you to show or hide the page title</li>
<li><strong>Sidebar</strong>: Allows you ro show a specific sidebar for this page</li>
</ul>
</li>
<li>Listing Contents
<ul>
<li><strong>Properties Per Page</strong>: Determines the number of properties shown on one page</li>
<li><strong>Property Types</strong>: Select the property types to show in this listing</li>
<li><strong>Property Categories</strong>: Select the property categories to show in this listing</li>
</ul>
</li>
</ul>
								        			', THEMENAME );
	        			?>
	        		</section>
	        		<section class='helpSection'>
	        			<?php echo bsh_docs_shortcodes() ?>
		        	</section>
		        	<section class='helpSection'>
			        	<?php echo bsh_docs_get_support() ?>
			   		</section>

	        	</div>
	        	<div class='clear'></div>
	        </div>

        <?php
    }


}


/***********************************************/
/*       2. Instantiating The Options          */
/***********************************************/

if ( is_admin() ) {
    add_action( 'load-post.php', 'call_bshListingPageOptions' );
    add_action( 'load-post-new.php', 'call_bshListingPageOptions' );
}
function call_bshListingPageOptions() {
	global $bshPostOptions;
	if( $bshPostOptions->get_page_template() == 'template-bshListingPage.php' ) {
    	return new bshListingPageOptions();
    }

}




?>