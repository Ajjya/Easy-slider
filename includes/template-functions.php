<?php
/**
 * Template functions
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Is gallery
 *
 * @since 1.0
 * @return boolean
 */
function easy_slider_is_has_images() {
	$DB = new DB();

	return $DB->is_has_images(get_the_ID());
}


/**
 * Check the current post for the existence of a short code
 *
 * @since 1.0
 * @return boolean
 */
function easy_slider_has_shortcode( $shortcode = '' ) {
	global $post;

	// false because we have to search through the post content first
	$found = false;

	// if no short code was provided, return false
	if ( !$shortcode ) {
		return $found;
	}

	if (  is_object( $post ) && stripos( $post->post_content, '[' . $shortcode ) !== false ) {
		// we have found the short code
		$found = true;
	}

	// return our final results
	return $found;
}


if ( !function_exists( 'easy_slider_get_showtype' ) ) :
	function easy_slider_get_showtype() {

		$settings = (array) get_option( 'easy-slider' );

		// set fancybox as default for when the settings page hasn't been saved
		$show_type = isset( $settings['show_types'] ) ? $settings['show_types'] : array('slider');
		//var_dump($show_type);

		return $show_type;

	}
endif;

/**
 * Get list of post types for populating the checkboxes on the admin page
 *
 * @since 1.0
 * @return array
 */
function easy_slider_get_post_types() {

	$args = array(
		'public' => true
	);

	$post_types = array_map( 'ucfirst', get_post_types( $args ) );

	// remove attachment
	unset( $post_types[ 'attachment' ] );

	return apply_filters( 'easy_slider_get_post_types', $post_types );

}

/**
 * Retrieve the allowed post types from the option row
 * Defaults to post and page when the settings have not been saved
 *
 * @return array
 * @since 1.0
*/
function easy_slider_allowed_post_types() {
	
	$defaults['post_types']['post'] = 'on';
	$defaults['post_types']['page'] = 'on';

	// get the allowed post type from the DB
	$settings = ( array ) get_option( 'easy-slider', $defaults );
	$post_types = isset( $settings['post_types'] ) ? $settings['post_types'] : '';

	// post types don't exist, bail
	if ( ! $post_types )
		return;

	return $post_types;

}


/**
 * Is the currently viewed post type allowed?
 * For use on the front-end when loading scripts etc
 *
 * @since 1.0
 * @return boolean
 */
function easy_slider_allowed_post_type() {

	// post and page defaults
	$defaults['post_types']['post'] = 'on';
	$defaults['post_types']['page'] = 'on';

	// get currently viewed post type
	$post_type = ( string ) get_post_type();

	//echo $post_type; exit; // download

	// get the allowed post type from the DB
	$settings = ( array ) get_option( 'easy-image-gallery', $defaults );
	$post_types = isset( $settings['post_types'] ) ? $settings['post_types'] : '';

	// post types don't exist, bail
	if ( ! $post_types )
		return;

	// check the two against each other
	if ( array_key_exists( $post_type, $post_types ) )
		return true;
}


/**
 * Retrieve attachment IDs
 *
 * @since 1.0
 * @return string
 */
function easy_slider_get_images() {
	global $post;
	$DB = new DB();

	if( ! isset( $post->ID) )
		return;

	$attachments = $DB->getAttachments($post->ID);
	return $attachments;
}


/**
 * Shortcode
 *
 * @since 1.0
 */

function easy_slider_shortcode($atts) {
	// return early if the post type is not allowed to have a gallery
	if ( ! easy_slider_allowed_post_type() )
		return;

	$atts = shortcode_atts(array(
      'type' => 'slider'
    ), $atts, 'easy_slider');


	return easy_slider($atts['type']);
}
add_shortcode( 'easy_slider', 'easy_slider_shortcode' );


/**
 * Count number of images in array
 *
 * @since 1.0
 * @return integer
 */
function easy_slider_count_images() {
	$DB = new BD();
	$number = $DB->getCountImages(get_the_ID());

	return $number;
}

function easy_slider_gallery($attachments) {
	
	$settings = (array) get_option( 'easy-slider' );
	if(!isset($settings['show_types_settings']) || !isset($settings['show_types_settings']['gallery']) || !isset($settings['show_types_settings']['gallery']['show_counter']) || $settings['show_types_settings']['gallery']['show_counter'] != 'on'):?>
		<style>
			.lb-data .lb-number{
				display: none !important;
			}
		</style>
	<?php endif;?>

	<section id="easy_slider_gallery">
		<div class="easy_slider_gallery">
			<ul>
				<?php
					foreach($attachments as $one_attachment):?>
						<li>
							<a href="<?php echo $one_attachment['image_src']['sizes']["large"]['file'];?>" <?php if(isset($one_attachment['image_signature'])):?>data-title="<?php echo $one_attachment['image_signature'];?>"<?php endif;?> data-lightbox="easy_slider">
								<div class="easy_slider_one_img bg" style="background-image:url(<?php echo $one_attachment['image_src']['sizes']["medium_large"]['file'];?>)"></div>
							</a>
						</li>
				<?php endforeach;
				?>
			</ul>
		</div>
	</section>

	<?php 
}

function easy_slider_slick($attachments) {
	$settings = (array) get_option( 'easy-slider' );
	?>
	<section id="easy_slider">
		<div class="easy_slider">
			
			<?php
				foreach($attachments as $one_attachment):
					$image_biggest_f = $one_attachment['image_src']['sizes']["slider-biggest"]['file'];
					$image_big_f = $one_attachment['image_src']['sizes']["slider-big"]['file'];
					$image_small_f = $one_attachment['image_src']['sizes']["large"]['file'];
					$image_smallest_f = $one_attachment['image_src']['sizes']["medium_large"]['file'];
					$url = $one_attachment['image_src']['url'];
			?>
					<div class="bg easy_slider_one_slide" data-img-sizes="<?php echo $url?>, <?php echo $image_biggest_f?> 1600, <?php echo $image_big_f?> 1300, <?php echo $image_small_f?> 1000, <?php echo $image_smallest_f?> 768">
						<div class="easy_slider_wrapper">
							<div class="easy_slider_container">
								<div class="easy_slider_signature">
									<?php if(isset($one_attachment['image_title'])):?>
										<div class="easy_slider_title"><?php echo $one_attachment['image_title'];?></div>
									<?php endif;?>
									<?php if(isset($one_attachment['image_subtitle'])):?>
										<div class="easy_slider_subtitle"><?php echo $one_attachment['image_subtitle'];?></div>
									<?php endif;?>
									<?php if(isset($one_attachment['image_content'])):?>
										<div class="easy_slider_content"><?php echo $one_attachment['image_content'];?></div>
									<?php endif;?>
									<?php if(isset($one_attachment['button_name']) && isset($one_attachment['button_link'])):?>
										<div class="easy_slider_button_wrap"><a href="<?php echo $one_attachment['button_link'];?>"><?php echo $one_attachment['button_name'];?></a></div>
									<?php endif;?>
								</div>
							</div>
						</div>
					</div>
			<?php
				endforeach;
			?>

		</div>
	</section>
	<script type="text/javascript">
		jQuery(function($){
			var options = {
				<?php if(isset($settings['show_types_settings']) && isset($settings['show_types_settings']['slider']) && isset($settings['show_types_settings']['slider']['show_arrows']) && $settings['show_types_settings']['slider']['show_arrows'] == 'on'):?>
					arrows:true,
				<?php else: ?>
					arrows:false,
				<?php endif;?>
				<?php if(isset($settings['show_types_settings']) && isset($settings['show_types_settings']['slider']) && isset($settings['show_types_settings']['slider']['show_dots']) && $settings['show_types_settings']['slider']['show_dots'] == 'on'):?>
					dots:true,
				<?php else: ?>
					dots:false,
				<?php endif;?>
			}

			$('.easy_slider').slick(options);
			/*place dots*/
			if(options.dots){
				
				var dots_w = $('.slick-dots').outerWidth();
				$('.slick-dots').css({'marginLeft':-dots_w/2});
			}
		})
	</script>
	<?php 


}

/**
 * Output gallery
 *
 * @since 1.0
 */
function easy_slider($type="slider") {
	$attachments = easy_slider_get_images();
	if(count($attachments)){
		$show_types = easy_slider_get_showtype();

		if(count($show_types) == 1){
			switch ($show_types[0]) {
				case 'slider':
					easy_slider_slick($attachments);
					break;
				
				default:
					easy_slider_gallery($attachments);
					break;
			}
		} else {
			if(in_array($type, $show_types)){
				switch ($type) {
					case 'slider':
						easy_slider_slick($attachments);
						break;
					
					default:
						easy_slider_gallery($attachments);
						break;
				}
			}
		}
	}
}
/**
 * Append gallery images to page automatically
 *
 * @since 1.0
 */

// function easy_slider_append_to_content( $content ) {

// 	if ( is_singular() && is_main_query() && easy_slider_allowed_post_type() ) {
// 		$new_content = easy_slider();
// 		$content .= $new_content;
// 	}

// 	return $content;

// }
// add_filter( 'the_content', 'easy_slider_append_to_content' );


// /**
//  * Remove the_content filter if shortcode is detected on page
//  *
//  * @since 1.0
//  */
// function easy_slider_template_redirect() {

// 	if ( easy_slider_has_shortcode( 'easy_slider' ) )
// 		remove_filter( 'the_content', 'easy_slider_append_to_content' );

// }
// add_action( 'template_redirect', 'easy_slider_template_redirect' );
