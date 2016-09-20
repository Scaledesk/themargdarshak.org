<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('invetex_vc_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_vc_theme_setup', 1 );
	function invetex_vc_theme_setup() {
		if (invetex_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'invetex_filter_importer_options',				'invetex_vc_importer_set_options' );
			}
			add_action('invetex_action_add_styles',		 				'invetex_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'invetex_filter_importer_required_plugins',		'invetex_vc_importer_required_plugins', 10, 2 );
			add_filter( 'invetex_filter_required_plugins',					'invetex_vc_required_plugins' );
		}
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'invetex_exists_visual_composer' ) ) {
	function invetex_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'invetex_vc_is_frontend' ) ) {
	function invetex_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'invetex_vc_required_plugins' ) ) {
	//add_filter('invetex_filter_required_plugins',	'invetex_vc_required_plugins');
	function invetex_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', invetex_storage_get('required_plugins'))) {
			$path = invetex_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Visual Composer',
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'invetex_vc_frontend_scripts' ) ) {
	//add_action( 'invetex_action_add_styles', 'invetex_vc_frontend_scripts' );
	function invetex_vc_frontend_scripts() {
		if (file_exists(invetex_get_file_dir('css/plugin.visual-composer.css')))
			invetex_enqueue_style( 'invetex-plugin.visual-composer-style',  invetex_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'invetex_vc_importer_required_plugins' ) ) {
	//add_filter( 'invetex_filter_importer_required_plugins',	'invetex_vc_importer_required_plugins', 10, 2 );
	function invetex_vc_importer_required_plugins($not_installed='', $list='') {
		if (!invetex_exists_visual_composer() )		// && invetex_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>Visual Composer';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'invetex_vc_importer_set_options' ) ) {
	//add_filter( 'invetex_filter_importer_options',	'invetex_vc_importer_set_options' );
	function invetex_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', invetex_storage_get('required_plugins')) && invetex_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>