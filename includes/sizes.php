<?php
	add_action( 'after_setup_theme', 'easy_slider_sizes_setup' );

	function easy_slider_sizes_setup() {
		add_image_size( 'slider-big', 1300 ); 
		add_image_size( 'slider-biggest', 1600 );
	}
