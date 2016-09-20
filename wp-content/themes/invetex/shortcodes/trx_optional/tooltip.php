<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_tooltip_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_tooltip_theme_setup' );
	function invetex_sc_tooltip_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('invetex_sc_tooltip')) {	
	function invetex_sc_tooltip($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	invetex_require_shortcode('trx_tooltip', 'invetex_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_tooltip_reg_shortcodes');
	function invetex_sc_tooltip_reg_shortcodes() {
	
		invetex_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'invetex'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'invetex'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'invetex'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'invetex') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => invetex_get_sc_param('id'),
				"class" => invetex_get_sc_param('class'),
				"css" => invetex_get_sc_param('css')
			)
		));
	}
}
?>