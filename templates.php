<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/** Start code HERE **/

function departments_template_function( $template_path ) {
	if ( get_post_type() == 'department' ) {
	    if ( is_single() ) {
	        if ( $theme_file = locate_template( [ 'single-departments.php' ] ) ) {
	            $template_path = $theme_file;
	        } else {
	            $template_path = plugin_dir_path( __FILE__ ) . 'templates/single-departments.php';
	        }
	    }
	    elseif ( is_archive() ) {
	        if ( $theme_file = locate_template([ 'arhive-departments.php' ]) ) {
	            $template_path = $theme_file;
	        } else {
	            $template_path = plugin_dir_path( __FILE__ ) . 'templates/arhive-departments.php';
	        }
	    }
	}
	return $template_path;
}

add_filter( 'template_include', 'departments_template_function', 100 );