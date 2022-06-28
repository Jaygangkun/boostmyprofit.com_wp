<?php
/**
 * Plugin Name: Workintry
 * Plugin URI: https://codesquare.co
 * Description: Workintry WordPress premium plugin for freelance and online jobs related business with all basic requirements built in.
 * Version: 1.0
 * Author: codesquare.co
 * Author URI: https://codesquare.co
 * Text Domain: workintry
 * Domain Path: /languages
 */
 
//Exit if directly accessed
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//Path Constants
if( !defined( 'CSC_WORKINTRY_PLUGIN' ) ){
	define( 'CSC_WORKINTRY_PLUGIN', plugin_dir_path( __FILE__ ) );
}
if( !defined( 'CSC_WORKINTRY_PLUGIN_URL' ) ){
	define( 'CSC_WORKINTRY_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
}

//Include template file from folder
add_filter( 'template_include', 'codesquare_workintry_load_relevant_templates' );
function codesquare_workintry_load_relevant_templates( $template ) {
	if( is_tax('gig_tags') || is_tax('gig_category') ){
		$new_template = codesquare_workintry_addon_template_page_template_exsits('workintry/templates/gigs-search.php');  
		if ( !empty( $new_template ) ) {
			return $new_template;
		}
	}
	return $template;
}

// Get template from plugin or theme. 
if( !function_exists( 'codesquare_workintry_addon_template_exsits' ) ){
	function codesquare_workintry_addon_template_exsits( $file, $param = array() ) {
		extract( $param );
		if ( is_dir( get_stylesheet_directory() . '/workintry/' ) ) {
			if ( file_exists( get_stylesheet_directory() . '/workintry/' . $file . '.php' ) ) {
				$template_load = get_stylesheet_directory() . '/workintry/' . $file . '.php';
			} else {
				$template_load = CSC_WORKINTRY_PLUGIN . '/' . $file . '.php';
			}
		} else {
			$template_load = CSC_WORKINTRY_PLUGIN . '/' . $file . '.php';
		}	
		return $template_load;
	}
}

// Get template from plugin or theme. 
if( !function_exists( 'codesquare_workintry_addon_template_page_template_exsits' ) ){
	function codesquare_workintry_addon_template_page_template_exsits( $file, $param = array() ) {
	    extract( $param );
	    if ( is_dir( get_stylesheet_directory() . '/workintry/' ) ) {
	        if ( file_exists( get_stylesheet_directory() . '/workintry/' . $file  ) ) {
	            $template_load = get_stylesheet_directory() . '/workintry/' . $file ;
	        } else {          
	            $template_load = CSC_WORKINTRY_PLUGIN . '/' . $file;
	        }
	    } else {
	        $template_load = CSC_WORKINTRY_PLUGIN . '/' . $file;
	    }   
	    return $template_load;
	}
}

//Getting Functions File
require_once codesquare_workintry_addon_template_exsits( '/functions' );