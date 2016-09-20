<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_gap_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_gap_theme_setup' );
	function invetex_sc_gap_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_gap_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('invetex_sc_gap')) {	
	function invetex_sc_gap($atts, $content = null) {
		if (invetex_in_shortcode_blogger()) return '';
		$output = invetex_gap_start() . do_shortcode($content) . invetex_gap_end();
		return apply_filters('invetex_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	invetex_require_shortcode("trx_gap", "invetex_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_gap_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_gap_reg_shortcodes');
	function invetex_sc_gap_reg_shortcodes() {
	
		invetex_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'invetex'),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'invetex') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'invetex'),
					"desc" => wp_kses_data( __("Gap inner content", 'invetex') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_gap_reg_shortcodes_vc');
	function invetex_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'invetex'),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", 'invetex') ),
			"category" => esc_html__('Structure', 'invetex'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends INVETEX_VC_ShortCodeCollection {}
	}
}
?>