<?php

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

global $wpdb;
$table_name = $wpdb->prefix . "easy_slider"; 
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

delete_option( 'easy-slider' );