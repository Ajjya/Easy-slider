<?php
	add_action('admin_menu', 'easy_slider_create_menu');
	
	function easy_slider_create_menu() {
	    //create new top-level menu
	    add_menu_page('Easy slider options', 'Easy slider options', 'administrator', __FILE__, 'easy_slider_settings_page');

	    //call register settings function
	    add_action( 'admin_init', 'register_easy_slider_settings' );
	}


	function register_easy_slider_settings() {
	    //register our settings
	    register_setting( 'easy_slider_settings_group', 'easy-slider' );
	 	}

	function easy_slider_settings_page() {
	
		$defaults['post_types']['post'] = 'on';
		$defaults['post_types']['page'] = 'on';
		$defaults['show_types'] = 'slider';
		$settings = (array) get_option( 'easy-slider', $defaults );
	?>


		<style type="text/css">
			.easy_slider_options th{
				text-align: left;
				width: 110px;
				padding-left: 10px; 
				padding-right: 10px;
			}

			.show_type{
				width: 100px;
			}

			.easy_slider_options{
				border: 1px solid #d8d6d6;
				width: 100%;
				margin-bottom: 30px;
			}

			.easy_slider_options p{
				margin-top: 0;
			}

			.easy_slider_options input[type="text"],
			.easy_slider_options input[type="email"]{
				min-width: 250px;
			}

			.type_options{
				margin-bottom: 10px;
			}
		</style>

	    <div class="wrap">
	    <h2>Slider options</h2>

	    <form method="post" action="options.php" enctype="multipart/form-data">
	        <?php settings_fields( 'easy_slider_settings_group' ); ?>
	        <?php do_settings_sections( 'easy_slider_settings_group' ); ?>
	        <h2>General</h2>
	        <table class="easy_slider_options">
	            <tbody>
	                <tr valign="top">
	                    <th scope="row">Post types: </th>
	                    <td>
	                    	<?php easy_slider_post_types_callback($settings)?>
	                    </td>
	                </tr>
	            </tbody>
	        </table>
	        <table class="easy_slider_options">
	            <tbody>
	                <tr valign="top">
	                    <th scope="row">Show: </th>
	                    <td>
	                    	<?php easy_slider_show_type_callback($settings)?>
	                    </td>
	                </tr>
	            </tbody>
	        </table>
	      
	        <?php submit_button(); ?>

	    </form>
	</div>
	<?}

	/**
	 * Show Types callback
	 *
	 * @since 1.0
	 */
	function easy_slider_show_type_callback($settings){
		$show_types = ["slider", "gallery"];

		foreach($show_types as $one_show_type):
			if(isset($settings['show_types']) && is_array($settings['show_types'])){
				$is_checked = in_array($one_show_type, $settings['show_types']) ? true : false;
			} else {
				$is_checked = false;
			}
			
		?>
			<table class="type_options">
				<tr>
					<td class="show_type">
						<p>
							<input type="checkbox" id="<?php echo $one_show_type?>" name="easy-slider[show_types][]" value="<?php echo $one_show_type?>" <?php if($is_checked) echo 'checked' ?>/><label for="<?php echo $one_show_type?>"> <?php echo $one_show_type?></label>
						</p>
					</td>
					<td>
						<?php easy_slider_show_type_advances_callback($settings, $one_show_type);?>
					</td>
				</tr>
			</table>
		<?
		endforeach;
	}

	/**
	 * Show Types advanced
	 *
	 * @since 1.0
	 */

	function easy_slider_show_type_advances_callback($settings, $show_type){
		switch ($show_type) {
			case 'gallery':

				$is_checked_show_counter = isset( $settings['show_types_settings']) && isset( $settings['show_types_settings'][$show_type]) && isset( $settings['show_types_settings'][$show_type]['show_counter']) && $settings['show_types_settings'][$show_type]['show_counter'] == 'on' ? true : false;

				?>
					<p><input type="checkbox" id="show_counter" name="easy-slider[show_types_settings][<?php echo $show_type?>][show_counter]" <?php if($is_checked_show_counter) echo 'checked' ?>/><label for="show_counter">show counter</label></p>
				<?php 
				break;
			
			case 'slider':
				$is_checked_show_arrows = isset( $settings['show_types_settings']) && isset( $settings['show_types_settings'][$show_type])  && isset( $settings['show_types_settings'][$show_type]['show_arrows'] ) && $settings['show_types_settings'][$show_type]['show_arrows'] == 'on' ? true : false;

				$is_checked_show_dots = isset( $settings['show_types_settings']) && isset( $settings['show_types_settings'][$show_type]) && isset( $settings['show_types_settings'][$show_type]['show_dots']) && $settings['show_types_settings'][$show_type]['show_dots'] == 'on' ? true : false;

				?>
					<p><input type="checkbox" id="show_sign" name="easy-slider[show_types_settings][<?php echo $show_type?>][show_arrows]" <?php if($is_checked_show_arrows) echo 'checked' ?>/><label for="show_arrows">show arrows</label></p>
					<p><input type="checkbox" id="show_dots" name="easy-slider[show_types_settings][<?php echo $show_type?>][show_dots]" <?php if($is_checked_show_dots) echo 'checked' ?>/><label for="show_dots">show dots</label></p>
				<?php 
				break;
			default:
				# code...
				break;
		}
	}


		/**
	 * Post Types callback
	 *
	 * @since 1.0
	 */

	function easy_slider_post_types_callback($settings) {

	?>
			<?php foreach (  easy_slider_get_post_types() as $key => $label ) {

			$post_types = isset( $settings['post_types'][ $key ] ) ? esc_attr( $settings['post_types'][ $key ] ) : '';

	?>
			<p>
				<input type="checkbox" id="<?php echo $key; ?>" name="easy-slider[post_types][<?php echo $key; ?>]" <?php checked( $post_types, 'on' ); ?>/><label for="<?php echo $key; ?>"> <?php echo $label; ?></label>
			</p>
			<?php } ?>
		<?php

	}

	// /**
	//  * Sanitization
	//  *
	//  * @since 1.0
	//  */
	// function easy_slider_settings_sanitize( $input ) {

	// 	// Create our array for storing the validated options
	// 	$output = array();

	// 	// lightbox
	// 	// $valid = easy_slider_lightbox();

	// 	// if ( array_key_exists( $input['lightbox'], $valid ) )
	// 	// 	$output['lightbox'] = $input['lightbox'];

	// 	// post types
	// 	$post_types = isset( $input['post_types'] ) ? $input['post_types'] : '';

	// 	// only loop through if there are post types in the array
	// 	if ( $post_types ) {
	// 		foreach ( $post_types as $post_type => $value )
	// 			$output[ 'post_types' ][ $post_type ] = isset( $input[ 'post_types' ][ $post_type ] ) ? 'on' : '';	
	// 	}
		

	// 	return apply_filters( 'easy_slider_settings_sanitize', $output, $input );

	// }

	/**
	 * Action Links
	 *
	 * @since 1.0
	 */
	function  easy_slider_plugin_action_links( $links ) {

		$settings_link = '<a href="' . admin_url( '?page=easy-slider%2Fincludes%2Fadmin-page.php' ) . '">'. __( 'Settings', 'easy-slider' ) .'</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}
