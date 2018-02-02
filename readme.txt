=== Easy Sliser===
Contributors: ajjya
Tags: image gallery, image, galleries, simple, easy, slider
Requires at least: 3.5
Tested up to: 4.7.1
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily create an image gallery on your posts, pages or any custom post type

== Description ==

There comes a time when you need more flexibility than the standard WP gallery offers, That's when this plugin steps in. This plugin's goal is to make it easy to create a gallery and place it wherever you need. A perfect example would be to create a product gallery for an ecommerce website and then have the flexibility to position it where you wanted to match your theme's design.

This plugin allows you to easily create an image gallery on any post, page or custom post type. Images are can be added and previewed from the metabox. Images can be re-ordered by drag and drop.

Features:

1. Drag and drop re-ordering
1. Add gallery to any post, page or custom post type
1. If more than one image is added to the gallery, the images become grouped in the lightbox so you can easily view the next one
1. CSS and JS are only loaded on pages where needed
1. Fully Localized (translation ready) with .mo and .po files
1. Add multiple images to the gallery at once
1. Uses the thumbnail size specified in Settings -> Media
1. Custom webfont icon for hover effect
1. Uses the new WP 3.5+ media manager for a familiar and intuitive way to add your images
1. WordPress 4.7 Ready

= Usage =

Galleries are automatically appended to the bottom of your post/page unless you use the shortcode below. Using the shortcode will give you finer control over placement within the content area. Plugin settings are located under Slider option

= Shortcode Usage =

Use the following shortcode anywhere in the content area to display the gallery

	[easy_slider]

= Template Tag Usage =

The following template tag is available to display the gallery

	if( function_exists( 'easy_slider' ) ) {
		echo easy_slider();
	}

= PHP usage =

$slides = easy_slider_get_images();
        if(!count($slides)){
            $slides = array(
                0 => array(
                    'ID' => 6,
                    'post_id' => 5,
                    'image_id' => 10,
                    'image_order' => 0,
                    'image_title' => '',
                    'image_subtitle' => '',
                    'image_signature' => '',
                    'image_content' => '',
                    'image_src' => array (
                        'url' => 'img1.jpg',
                        'sizes' => array(
                            'thumbnail' => array
                            (
                                'file' => 'img1-150x150.jpg',
                                'width' => 150,
                                'height' => 150,
                            ),
                            'medium' => array
                            (
                                'file' => 'img1-300x200.jpg',
                                'width' => 300,
                                'height' => 200,
                                'mime-type' => 'image/jpeg',
                            ),
                            'medium_large' => array
                            (
                                'file' => 'img1-768x512.jpg',
                                'width' => 768,
                                'height' => 512,
                                'mime-type' => 'image/jpeg'
                            ),
                            'large' => array
                            (
                                'file' => 'img-1024x683.jpg',
                                'width' => 1024,
                                'height' => 683,
                                'mime-type' => 'image/jpeg'
                            )
                        )
                    )
                ),
                1 => array(
                    'ID' => 6,
                    'post_id' => 5,
                    'image_id' => 10,
                    'image_order' => 0,
                    'image_title' => '',
                    'image_subtitle' => '',
                    'image_signature' => '',
                    'image_content' => '',
                    'image_src' => array (
                        'url' => 'img2.jpg',
                        'sizes' => array(
                            'thumbnail' => array
                            (
                                'file' => 'img2-150x150.jpg',
                                'width' => 150,
                                'height' => 150,
                                'mime-type' => 'image/jpeg'
                            ),
                            'medium' => array
                            (
                                'file' => 'img2-300x200.jpg',
                                'width' => 300,
                                'height' => 200,
                                'mime-type' => 'image/jpeg',
                            ),
                            'medium_large' => array
                            (
                                'file' => 'img2-768x512.jpg',
                                'width' => 768,
                                'height' => 512,
                                'mime-type' => 'image/jpeg'
                            ),
                            'large' => array
                            (
                                'file' => 'img2-1024x683.jpg',
                                'width' => 1024,
                                'height' => 683,
                                'mime-type' => 'image/jpeg'
                            )
                        )
                    )
                )
           );
	   
= Developer Friendly =

1. Modify the gallery HTML using filters
1. Developed with WP Coding Standards
1. Easily add your preferred lightbox script via hooks and filters
1. Easily unhook CSS and add your own styling
1. Pass in a different image size for the thumbnails via filter
1. Minimalistic markup and styling


== Installation ==

1. Upload the entire `easy_slider` folder to the `/wp-content/plugins/` directory, or just upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
1. Activate Easy Slider from the 'Plugins' page in WordPress
1. Configure the plugin's settings from Settings -> Media
1. Create a gallery on any post or page from the added 'Image Slider' metabox.

