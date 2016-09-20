<?php
/**
 * Invetex Framework: shortcodes manipulations
 *
 * @package	invetex
 * @since	invetex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('invetex_sc_theme_setup')) {
	add_action( 'invetex_action_init_theme', 'invetex_sc_theme_setup', 1 );
	function invetex_sc_theme_setup() {
		// Add sc stylesheets
		add_action('invetex_action_add_styles', 'invetex_sc_add_styles', 1);
	}
}

if (!function_exists('invetex_sc_theme_setup2')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_theme_setup2' );
	function invetex_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'invetex_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('invetex_sc_prepare_content')) invetex_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('invetex_shortcode_output', 'invetex_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'invetex_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'invetex_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'invetex_sc_selector_add_in_toolbar', 11);

	}
}


// Register shortcodes styles
if ( !function_exists( 'invetex_sc_add_styles' ) ) {
	//add_action('invetex_action_add_styles', 'invetex_sc_add_styles', 1);
	function invetex_sc_add_styles() {
		// Shortcodes
		invetex_enqueue_style( 'invetex-shortcodes-style',	invetex_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Register shortcodes init scripts
if ( !function_exists( 'invetex_sc_add_scripts' ) ) {
	//add_filter('invetex_shortcode_output', 'invetex_sc_add_scripts', 10, 4);
	function invetex_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		if (invetex_storage_empty('shortcodes_scripts_added')) {
			invetex_storage_set('shortcodes_scripts_added', true);
			invetex_enqueue_script( 'invetex-shortcodes-script', invetex_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );	
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('invetex_sc_prepare_content')) {
	function invetex_sc_prepare_content() {
		if (function_exists('invetex_sc_clear_around')) {
			$filters = array(
				array('invetex', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('invetex_exists_woocommerce') && invetex_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'invetex_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('invetex_sc_excerpt_shortcodes')) {
	function invetex_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('invetex_sc_clear_around')) {
	function invetex_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// Invetex shortcodes load scripts
if (!function_exists('invetex_sc_load_scripts')) {
	function invetex_sc_load_scripts() {
		static $loaded = false;
		if (!$loaded) {
			invetex_enqueue_script( 'invetex-shortcodes_admin-script', invetex_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
			invetex_enqueue_script( 'invetex-selection-script',  invetex_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
			wp_localize_script( 'invetex-shortcodes_admin-script', 'INVETEX_SHORTCODES_DATA', invetex_storage_get('shortcodes') );
			$loaded = true;
		}
	}
}

// Invetex shortcodes prepare scripts
if (!function_exists('invetex_sc_prepare_scripts')) {
	function invetex_sc_prepare_scripts() {
		static $prepared = false;
		if (!$prepared) {
			invetex_storage_set_array('js_vars', 'shortcodes_cp', is_admin() ? (!invetex_storage_empty('to_colorpicker') ? invetex_storage_get('to_colorpicker') : 'wp') : 'custom');	// wp | tiny | custom
			$prepared = true;
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('invetex_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','invetex_sc_selector_add_in_toolbar', 11);
	function invetex_sc_selector_add_in_toolbar(){

		if ( !invetex_options_is_used() ) return;

		invetex_sc_load_scripts();
		invetex_sc_prepare_scripts();

		$shortcodes = invetex_storage_get('shortcodes');
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'invetex').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo trim($shortcodes_list);
	}
}

// Invetex shortcodes builder settings
require_once trailingslashit( get_template_directory() ) . INVETEX_FW_DIR . '/core/core.shortcodes/shortcodes_settings.php';

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once trailingslashit( get_template_directory() ) . INVETEX_FW_DIR . '/core/core.shortcodes/shortcodes_vc.php';
}

// Invetex shortcodes implementation
invetex_autoload_folder( 'shortcodes/trx_basic' );
invetex_autoload_folder( 'shortcodes/trx_optional' );
?>