# Easy-slider
Wordpress plugin

= Shortcode Usage =

Use the following shortcode anywhere in the content area to display the gallery

	[easy_slider]

If you use 2 types - slider is defauls, you can specify what type do you need with attributes:

	[easy_slider type="gallery"]
	[easy_slider type="slider"]

= Template Tag Usage =

The following template tag is available to display the gallery

	if( function_exists( 'easy_slider' ) ) {
		echo easy_slider();
	}

If you use 2 types - slider is defauls, you can specify what type do you need with attributes:

	if( function_exists( 'easy_slider' ) ) {
		echo easy_slider('gallery');
	}

	if( function_exists( 'easy_slider' ) ) {
		echo easy_slider('slider');
	}

= Custom Usage =

The following function returns list of current page slides

	if( function_exists( 'easy_slider_get_images' ) ) {
		$images = easy_slider_get_images();
	}
