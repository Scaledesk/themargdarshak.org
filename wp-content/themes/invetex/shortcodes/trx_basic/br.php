<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_br_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_br_theme_setup' );
	function invetex_sc_br_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('invetex_sc_br')) {	
	function invetex_sc_br($atts, $content = null) {
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('invetex_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	invetex_require_shortcode("trx_br", "invetex_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_br_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_br_reg_shortcodes');
	function invetex_sc_br_reg_shortcodes() {
	
		invetex_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'invetex'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'invetex'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'invetex'),
						'left' => esc_html__('Left', 'invetex'),
						'right' => esc_html__('Right', 'invetex'),
						'both' => esc_html__('Both', 'invetex')
					)
				)
			)
		));
	}
}
?>