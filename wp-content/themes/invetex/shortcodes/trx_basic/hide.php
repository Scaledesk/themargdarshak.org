<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_hide_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_hide_theme_setup' );
	function invetex_sc_hide_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('invetex_sc_hide')) {	
	function invetex_sc_hide($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		if (!empty($selector)) {
			invetex_storage_concat('js_code', '
				'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
				'.($delay>0 ? '},'.($delay).');' : '').'
			');
		}
		return apply_filters('invetex_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	invetex_require_shortcode('trx_hide', 'invetex_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_hide_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_hide_reg_shortcodes');
	function invetex_sc_hide_reg_shortcodes() {
	
		invetex_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'invetex'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'invetex'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'invetex'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'invetex') ),
					"value" => "yes",
					"size" => "small",
					"options" => invetex_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>