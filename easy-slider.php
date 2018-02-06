<?php
/*
Plugin Name: Easy Slider
Plugin URI: http://github.com/Ajjya
Description: Slider with drag & drop re-ordering
Version: 1.2.1
Author: Ajjya
Author URI: http://github.com/Ajjya
Text Domain: easy-slider
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Easy_Slider' ) ) {

	/**
	 * PHP5 constructor method.
	 *
	 * @since 1.0
	*/
	class Easy_Slider {

		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'plugins_loaded', array( $this, 'constants' ));
			add_action( 'plugins_loaded', array( $this, 'includes' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'easy_slider_plugin_action_links' );
		}


		/**
		 * Internationalization
		 *
		 * @since 1.0
		*/
		public function load_textdomain() {
			load_plugin_textdomain( 'easy-image-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Constants
		 *
		 * @since 1.0
		*/
		public function constants() {
			// if ( !defined( 'EASY_SLIDER_PLUGIN_BASENAME' ) )
			// 	define( 'EASY_SLIDER_PLUGIN_BASENAME', plugin_basename( EASY_SLIDER_PLUGIN ) );

			if ( !defined( 'EASY_SLIDER_DIR' ) )
				define( 'EASY_SLIDER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			if ( !defined( 'EASY_SLIDER_URL' ) )
			    define( 'EASY_SLIDER_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

			if ( ! defined( 'EASY_SLIDER_VERSION' ) )
			    define( 'EASY_SLIDER_VERSION', '1.0' );

			if ( ! defined( 'EASY_SLIDER_INCLUDES' ) )
			    define( 'EASY_SLIDER_INCLUDES', EASY_SLIDER_DIR . trailingslashit( 'includes' ) );

		}

		/**
		* Loads the initial files needed by the plugin.
		*
		* @since 1.0
		*/
		public function includes() {
			require_once( EASY_SLIDER_INCLUDES . 'DB.php' );
			require_once( EASY_SLIDER_INCLUDES . 'template-functions.php' );
			require_once( EASY_SLIDER_INCLUDES . 'scripts.php' );
			require_once( EASY_SLIDER_INCLUDES . 'sizes.php' );
			require_once( EASY_SLIDER_INCLUDES . 'metabox.php' );
			require_once( EASY_SLIDER_INCLUDES . 'admin-page.php' );
		}

	}

	/* Install and default settings */

}

$easy_slider = new Easy_Slider();

function easy_slider_activate() {
	global $wpdb;
	$table_name = $wpdb->prefix . "easy_slider"; 

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		post_id int(11) NOT NULL,
		image_id int(11) NOT NULL,
		image_order int(11) DEFAULT '100' NOT NULL,
		image_title text NOT NULL,
		image_subtitle text NOT NULL,
		image_signature text NOT NULL,
		image_content text NOT NULL,
		button_name text NOT NULL,
		button_link text NOT NULL,
		PRIMARY KEY (ID), 
		INDEX `post_id` (`post_id`), 
		INDEX `image_id` (`image_id`)
	) $charset_collate;";

	$wpdb->query( $sql );
}
register_activation_hook( __FILE__, 'easy_slider_activate' );


