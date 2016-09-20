<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('invetex_instagram_widget_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_instagram_widget_theme_setup', 1 );
	function invetex_instagram_widget_theme_setup() {
		if (invetex_exists_instagram_widget()) {
			add_action( 'invetex_action_add_styles', 						'invetex_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'invetex_filter_importer_required_plugins',		'invetex_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'invetex_filter_required_plugins',					'invetex_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'invetex_exists_instagram_widget' ) ) {
	function invetex_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'invetex_instagram_widget_required_plugins' ) ) {
	//add_filter('invetex_filter_required_plugins',	'invetex_instagram_widget_required_plugins');
	function invetex_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', invetex_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'invetex_instagram_widget_frontend_scripts' ) ) {
	//add_action( 'invetex_action_add_styles', 'invetex_instagram_widget_frontend_scripts' );
	function invetex_instagram_widget_frontend_scripts() {
		if (file_exists(invetex_get_file_dir('css/plugin.instagram-widget.css')))
			invetex_enqueue_style( 'invetex-plugin.instagram-widget-style',  invetex_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'invetex_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'invetex_filter_importer_required_plugins',	'invetex_instagram_widget_importer_required_plugins', 10, 2 );
	function invetex_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		if (invetex_strpos($list, 'instagram_widget')!==false && !invetex_exists_instagram_widget() )
			$not_installed .= '<br>WP Instagram Widget';
		return $not_installed;
	}
}
?>