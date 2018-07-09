<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Scripts
 *
 * @since 1.0
 */
function easy_slider_scripts() {

	global $post;

	// return if post object is not set
	if ( !isset( $post->ID ) )
		return;

	// JS
	wp_register_script( 'responsive-img', EASY_SLIDER_URL . 'includes/lib/responsiveImages.min.js', array( 'jquery' ), EASY_SLIDER_VERSION, false );
	wp_register_script( 'slick', EASY_SLIDER_URL . 'includes/lib/slick/slick.min.js', array( 'jquery', 'responsive-img' ), EASY_SLIDER_VERSION, false, true );
	wp_register_script( 'lightbox', EASY_SLIDER_URL . 'includes/lib/lightbox/js/lightbox.js', array( 'jquery' ), EASY_SLIDER_VERSION, false, true );

	// CSS
	wp_register_style( 'slick', EASY_SLIDER_URL . 'includes/lib/slick/slick.min.css', '', EASY_SLIDER_VERSION, 'screen' );
	wp_register_style( 'lightbox', EASY_SLIDER_URL . 'includes/lib/lightbox/css/lightbox.min.css', '', EASY_SLIDER_VERSION, 'screen' );


	// create a new 'css/easy-image-gallery.css' in your child theme to override CSS file completely
	if ( file_exists( get_stylesheet_directory() . '/css/easy-slider.min.css' ) )
		wp_register_style( 'easy-slider', get_stylesheet_directory_uri() . '/css/easy-slider.min.css', '', EASY_SLIDER_VERSION, 'screen' );
	else
		wp_register_style( 'easy-slider', EASY_SLIDER_URL . 'includes/css/easy-slider.min.css', '', EASY_SLIDER_VERSION, 'screen' );

	// post type is not allowed, return
	if ( ! easy_slider_allowed_post_types() )
		return;

	

	// only load the JS if gallery images are linked or the featured image is linked
	$show_types = easy_slider_get_showtype();

	foreach ($show_types as $one_show_type) {
		switch ( $one_show_type) {
			case 'slider':
				// CSS
				wp_enqueue_style( 'slick' );

				// JS
				wp_enqueue_script( 'responsive-img');
				wp_enqueue_script( 'slick' );

			break;
			case 'gallery':
				// CSS
				wp_enqueue_style( 'lightbox' );
				// JS
				wp_enqueue_script( 'lightbox' );

			break;
		}
	}
	
	wp_enqueue_style( 'easy-slider' );

	// allow developers to load their own scripts here
	do_action( 'easy_slider_scripts' );

}
add_action( 'wp_enqueue_scripts', 'easy_slider_scripts', 20 );

/**
 * CSS for admin
 *
 * @since 1.0
 */
function easy_slider_admin_css() { ?>

	<style>

		.attachment.details .check div {
			background-position: -60px 0;
		}

		.attachment.details .check:hover div {
			background-position: -60px 0;
		}

		.gallery_images .details.attachment {
			box-shadow: none;
			width: 100%;
		}

		.eig-metabox-sortable-placeholder {
			background: #DFDFDF;
		}

	/*	.gallery_images .attachment.details > div {
			
			box-shadow: none;
		}*/

		.gallery_images .attachment-preview .thumbnail {
			cursor: move;
			width: 150px;
			height: 150px;
		}

		.attachment.details div:hover .check {
			display:block;
		}

        .gallery_images:after,
        #gallery_images_container:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }

        .gallery_images > li {
            float: left;
            cursor: move;
            margin: 0 20px 20px 0;
        }

        .gallery_images li.image img {
            width: 150px;
            height: auto;
        }

/*        .wp-core-ui .attachment .thumbnail img {
		    left: 50%;
		    margin-left: -75px;
		}*/

		.slider_desc table{
			width: 100%;
		}

		.slider_desc input[type="text"],
		.slider_desc textarea
		{
			width: 100%;
		}


		.slider_desc{
			margin-left: 170px;
			text-align: left;
			position: relative;
		}

		.wp-core-ui #gallery_images_container .attachment .thumbnail{
		    width: 150px;
		    height: 150px;
		   	position: static;
		   	float: left;
		}

		.wp-core-ui #gallery_images_container .attachment .thumbnail img{
			position: static;
		}

		.wp-core-ui #gallery_images_container .attachment-preview:before{
			padding-top: 160px;
			display: none;
		}

		.wp-core-ui #gallery_images_container .attachment-preview{
			padding: 20px;
			overflow: hidden;
		}

    </style>

<?php }
add_action( 'admin_head', 'easy_slider_admin_css' );
