<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'invetex_shortcodes_is_used' ) ) {
	function invetex_shortcodes_is_used() {
		return invetex_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && invetex_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('invetex_vc_is_frontend') && invetex_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'invetex_shortcodes_width' ) ) {
	function invetex_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'invetex'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'invetex_shortcodes_height' ) ) {
	function invetex_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'invetex'),
			"desc" => wp_kses_data( __("Width and height of the element", 'invetex') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'invetex_get_sc_param' ) ) {
	function invetex_get_sc_param($prm) {
		return invetex_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'invetex_set_sc_param' ) ) {
	function invetex_set_sc_param($prm, $val) {
		invetex_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'invetex_sc_map' ) ) {
	function invetex_sc_map($sc_name, $sc_settings) {
		invetex_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'invetex_sc_map_after' ) ) {
	function invetex_sc_map_after($after, $sc_name, $sc_settings='') {
		invetex_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'invetex_sc_map_before' ) ) {
	function invetex_sc_map_before($before, $sc_name, $sc_settings='') {
		invetex_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'invetex_compare_sc_title' ) ) {
	function invetex_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'invetex_shortcodes_settings_theme_setup' ) ) {
//	if ( invetex_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'invetex_action_before_init_theme', 'invetex_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'invetex_action_after_init_theme', 'invetex_shortcodes_settings_theme_setup' );
	function invetex_shortcodes_settings_theme_setup() {
		if (invetex_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = invetex_storage_get('registered_templates');
			ksort($tmp);
			invetex_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			invetex_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'invetex'),
					"desc" => wp_kses_data( __("ID for current element", 'invetex') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'invetex'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'invetex'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'invetex'),
					'ol'	=> esc_html__('Ordered', 'invetex'),
					'iconed'=> esc_html__('Iconed', 'invetex')
				),

				'yes_no'	=> invetex_get_list_yesno(),
				'on_off'	=> invetex_get_list_onoff(),
				'dir' 		=> invetex_get_list_directions(),
				'align'		=> invetex_get_list_alignments(),
				'float'		=> invetex_get_list_floats(),
				'hpos'		=> invetex_get_list_hpos(),
				'show_hide'	=> invetex_get_list_showhide(),
				'sorting' 	=> invetex_get_list_sortings(),
				'ordering' 	=> invetex_get_list_orderings(),
				'shapes'	=> invetex_get_list_shapes(),
				'sizes'		=> invetex_get_list_sizes(),
				'sliders'	=> invetex_get_list_sliders(),
				'controls'	=> invetex_get_list_controls(),
				'categories'=> invetex_get_list_categories(),
				'columns'	=> invetex_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), invetex_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), invetex_get_list_icons()),
				'locations'	=> invetex_get_list_dedicated_locations(),
				'filters'	=> invetex_get_list_portfolio_filters(),
				'formats'	=> invetex_get_list_post_formats_filters(),
				'hovers'	=> invetex_get_list_hovers(true),
				'hovers_dir'=> invetex_get_list_hovers_directions(true),
				'schemes'	=> invetex_get_list_color_schemes(true),
				'animations'		=> invetex_get_list_animations_in(),
				'margins' 			=> invetex_get_list_margins(true),
				'blogger_styles'	=> invetex_get_list_templates_blogger(),
				'forms'				=> invetex_get_list_templates_forms(),
				'posts_types'		=> invetex_get_list_posts_types(),
				'googlemap_styles'	=> invetex_get_list_googlemap_styles(),
				'field_types'		=> invetex_get_list_field_types(),
				'label_positions'	=> invetex_get_list_label_positions()
				)
			);

			// Common params
			invetex_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'invetex'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'invetex') ),
				"value" => "none",
				"type" => "select",
				"options" => invetex_get_sc_param('animations')
				)
			);
			invetex_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'invetex'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => invetex_get_sc_param('margins')
				)
			);
			invetex_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'invetex'),
				"value" => "inherit",
				"type" => "select",
				"options" => invetex_get_sc_param('margins')
				)
			);
			invetex_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'invetex'),
				"value" => "inherit",
				"type" => "select",
				"options" => invetex_get_sc_param('margins')
				)
			);
			invetex_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'invetex'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'invetex') ),
				"value" => "inherit",
				"type" => "select",
				"options" => invetex_get_sc_param('margins')
				)
			);

			invetex_storage_set('sc_params', apply_filters('invetex_filter_shortcodes_params', invetex_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			invetex_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('invetex_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = invetex_storage_get('shortcodes');
			uasort($tmp, 'invetex_compare_sc_title');
			invetex_storage_set('shortcodes', $tmp);
		}
	}
}
?>