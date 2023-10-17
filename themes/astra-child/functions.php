<?php
/**
 * Astra child theme Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra child theme
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_THEME_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
add_action('wp_enqueue_scripts', 'child_enqueue_styles', 15);
function child_enqueue_styles() 
{
	wp_enqueue_style( 'astra-child-theme-theme-css', get_stylesheet_directory_uri() . '/assets/css/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_THEME_VERSION, 'all' );
}

add_action('wp_enqueue_scripts', 'child_enqueue_scripts', 15);

function child_enqueue_scripts()
{
	// Enqueue your JavaScript file
	wp_enqueue_script(
		'astra-child-theme-js', // A unique handle for your script
		get_stylesheet_directory_uri() . '/assets/js/script.js', // The path to your JavaScript file
		array('jquery'), // Dependencies (e.g., jQuery)
		CHILD_THEME_ASTRA_CHILD_THEME_VERSION, // Version number (you can define this constant)
		true // Whether to place the script in the footer (true) or the header (false)
	);
}







