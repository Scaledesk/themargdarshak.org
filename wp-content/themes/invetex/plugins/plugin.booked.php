<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('invetex_booked_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_booked_theme_setup', 1 );
	function invetex_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (invetex_exists_booked()) {
			add_action('invetex_action_add_styles', 					'invetex_booked_frontend_scripts');
			add_action('invetex_action_shortcodes_list',				'invetex_booked_reg_shortcodes');
			if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
				add_action('invetex_action_shortcodes_list_vc',		'invetex_booked_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'invetex_filter_importer_options',			'invetex_booked_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'invetex_filter_importer_required_plugins',	'invetex_booked_importer_required_plugins', 10, 2);
			add_filter( 'invetex_filter_required_plugins',				'invetex_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'invetex_exists_booked' ) ) {
	function invetex_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'invetex_booked_required_plugins' ) ) {
	//add_filter('invetex_filter_required_plugins',	'invetex_booked_required_plugins');
	function invetex_booked_required_plugins($list=array()) {
		if (in_array('booked', invetex_storage_get('required_plugins'))) {
			$path = invetex_get_file_dir('plugins/install/booked.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Booked',
					'slug' 		=> 'booked',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'invetex_booked_frontend_scripts' ) ) {
	//add_action( 'invetex_action_add_styles', 'invetex_booked_frontend_scripts' );
	function invetex_booked_frontend_scripts() {
		if (file_exists(invetex_get_file_dir('css/plugin.booked.css')))
			invetex_enqueue_style( 'invetex-plugin.booked-style',  invetex_get_file_url('css/plugin.booked.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'invetex_booked_importer_required_plugins' ) ) {
	//add_filter( 'invetex_filter_importer_required_plugins',	'invetex_booked_importer_required_plugins', 10, 2);
	function invetex_booked_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('booked', invetex_storage_get('required_plugins')) && !invetex_exists_booked() )
		if (invetex_strpos($list, 'booked')!==false && !invetex_exists_booked() )
			$not_installed .= '<br>Booked Appointments';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'invetex_booked_importer_set_options' ) ) {
	//add_filter( 'invetex_filter_importer_options',	'invetex_booked_importer_set_options', 10, 1 );
	function invetex_booked_importer_set_options($options=array()) {
		if (in_array('booked', invetex_storage_get('required_plugins')) && invetex_exists_booked()) {
			$options['additional_options'][] = 'booked_%';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}


// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'invetex_get_list_booked_calendars' ) ) {
	function invetex_get_list_booked_calendars($prepend_inherit=false) {
		return invetex_exists_booked() ? invetex_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('invetex_booked_reg_shortcodes')) {
	//add_filter('invetex_action_shortcodes_list',	'invetex_booked_reg_shortcodes');
	function invetex_booked_reg_shortcodes() {
		if (invetex_storage_isset('shortcodes')) {

			$booked_cals = invetex_get_list_booked_calendars();

			invetex_sc_map('booked-appointments', array(
				"title" => esc_html__("Booked Appointments", 'invetex'),
				"desc" => esc_html__("Display the currently logged in user's upcoming appointments", 'invetex'),
				"decorate" => true,
				"container" => false,
				"params" => array()
				)
			);

			invetex_sc_map('booked-calendar', array(
				"title" => esc_html__("Booked Calendar", 'invetex'),
				"desc" => esc_html__("Insert booked calendar", 'invetex'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"calendar" => array(
						"title" => esc_html__("Calendar", 'invetex'),
						"desc" => esc_html__("Select booked calendar to display", 'invetex'),
						"value" => "0",
						"type" => "select",
						"options" => invetex_array_merge(array(0 => esc_html__('- Select calendar -', 'invetex')), $booked_cals)
					),
					"year" => array(
						"title" => esc_html__("Year", 'invetex'),
						"desc" => esc_html__("Year to display on calendar by default", 'invetex'),
						"value" => date("Y"),
						"min" => date("Y"),
						"max" => date("Y")+10,
						"type" => "spinner"
					),
					"month" => array(
						"title" => esc_html__("Month", 'invetex'),
						"desc" => esc_html__("Month to display on calendar by default", 'invetex'),
						"value" => date("m"),
						"min" => 1,
						"max" => 12,
						"type" => "spinner"
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('invetex_booked_reg_shortcodes_vc')) {
	//add_filter('invetex_action_shortcodes_list_vc',	'invetex_booked_reg_shortcodes_vc');
	function invetex_booked_reg_shortcodes_vc() {

		$booked_cals = invetex_get_list_booked_calendars();

		// Booked Appointments
		vc_map( array(
				"base" => "booked-appointments",
				"name" => esc_html__("Booked Appointments", 'invetex'),
				"description" => esc_html__("Display the currently logged in user's upcoming appointments", 'invetex'),
				"category" => esc_html__('Content', 'invetex'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_appointments",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array()
			) );
			
		class WPBakeryShortCode_Booked_Appointments extends INVETEX_VC_ShortCodeSingle {}

		// Booked Calendar
		vc_map( array(
				"base" => "booked-calendar",
				"name" => esc_html__("Booked Calendar", 'invetex'),
				"description" => esc_html__("Insert booked calendar", 'invetex'),
				"category" => esc_html__('Content', 'invetex'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_calendar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "calendar",
						"heading" => esc_html__("Calendar", 'invetex'),
						"description" => esc_html__("Select booked calendar to display", 'invetex'),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array_flip(invetex_array_merge(array(0 => esc_html__('- Select calendar -', 'invetex')), $booked_cals)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "year",
						"heading" => esc_html__("Year", 'invetex'),
						"description" => esc_html__("Year to display on calendar by default", 'invetex'),
						"admin_label" => true,
						"class" => "",
						"std" => date("Y"),
						"value" => date("Y"),
						"type" => "textfield"
					),
					array(
						"param_name" => "month",
						"heading" => esc_html__("Month", 'invetex'),
						"description" => esc_html__("Month to display on calendar by default", 'invetex'),
						"admin_label" => true,
						"class" => "",
						"std" => date("m"),
						"value" => date("m"),
						"type" => "textfield"
					)
				)
			) );
			
		class WPBakeryShortCode_Booked_Calendar extends INVETEX_VC_ShortCodeSingle {}

	}
}
?>