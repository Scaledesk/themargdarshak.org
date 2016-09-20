<?php
/**
 * Invetex Framework: Theme options custom fields
 *
 * @package	invetex
 * @since	invetex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'invetex_options_custom_theme_setup' ) ) {
	add_action( 'invetex_action_before_init_theme', 'invetex_options_custom_theme_setup' );
	function invetex_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'invetex_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'invetex_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'invetex_options_custom_load_scripts');
	function invetex_options_custom_load_scripts() {
		invetex_enqueue_script( 'invetex-options-custom-script',	invetex_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'invetex_show_custom_field' ) ) {
	function invetex_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(invetex_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager invetex_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'invetex') : esc_html__( 'Choose Image', 'invetex')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'invetex') : esc_html__( 'Choose Image', 'invetex')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'invetex') : esc_html__( 'Choose Image', 'invetex')) . '</a>';
				break;
		}
		return apply_filters('invetex_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>