<?php
/**
 * Invetex Framework
 *
 * @package invetex
 * @since invetex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'INVETEX_FW_DIR' ) )			define( 'INVETEX_FW_DIR', 'fw' );

// Include theme variables storage
require_once trailingslashit( get_template_directory() ) . INVETEX_FW_DIR . '/core/core.storage.php';

// Theme variables storage
invetex_storage_set('options_prefix', 'invetex');	//.'_'.str_replace(' ', '_', trim(strtolower(get_stylesheet()))));	// Prefix for the theme options in the postmeta and wp options
invetex_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
invetex_storage_set('widgets_args', array(			// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget_title">',
		'after_title'   => '</h5>',
	)
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'invetex_loader_theme_setup', 20 );
	function invetex_loader_theme_setup() {

		// Before init theme
		do_action('invetex_action_before_init_theme');

		// Load current values for main theme options
		invetex_load_main_options();

		// Theme core init - only for admin side. In frontend it called from action 'wp'
		if ( is_admin() ) {
			invetex_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// Manual load important libraries before load all rest files
// core.strings must be first - we use invetex_str...() in the invetex_get_file_dir()
require_once trailingslashit( get_template_directory() ) . INVETEX_FW_DIR . '/core/core.strings.php';
// core.files must be first - we use invetex_get_file_dir() to include all rest parts
require_once trailingslashit( get_template_directory() ) . INVETEX_FW_DIR . '/core/core.files.php';

// Include debug utilities
require_once trailingslashit( get_template_directory() ) . INVETEX_FW_DIR . '/core/core.debug.php';

// Include custom theme files
invetex_autoload_folder( 'includes' );

// Include core files
invetex_autoload_folder( 'core' );

// Include theme-specific plugins and post types
invetex_autoload_folder( 'plugins' );

// Include theme templates
invetex_autoload_folder( 'templates' );

// Include theme widgets
invetex_autoload_folder( 'widgets' );
?>