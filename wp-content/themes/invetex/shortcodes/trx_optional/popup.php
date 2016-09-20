<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_popup_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_popup_theme_setup' );
	function invetex_sc_popup_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_popup_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_popup_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('invetex_sc_popup')) {	
	function invetex_sc_popup($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		invetex_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	invetex_require_shortcode('trx_popup', 'invetex_sc_popup');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_popup_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_popup_reg_shortcodes');
	function invetex_sc_popup_reg_shortcodes() {
	
		invetex_sc_map("trx_popup", array(
			"title" => esc_html__("Popup window", 'invetex'),
			"desc" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'invetex') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Container content", 'invetex'),
					"desc" => wp_kses_data( __("Content for section container", 'invetex') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => invetex_get_sc_param('top'),
				"bottom" => invetex_get_sc_param('bottom'),
				"left" => invetex_get_sc_param('left'),
				"right" => invetex_get_sc_param('right'),
				"id" => invetex_get_sc_param('id'),
				"class" => invetex_get_sc_param('class'),
				"css" => invetex_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_popup_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_popup_reg_shortcodes_vc');
	function invetex_sc_popup_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_popup",
			"name" => esc_html__("Popup window", 'invetex'),
			"description" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_popup',
			"class" => "trx_sc_collection trx_sc_popup",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('css'),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Popup extends INVETEX_VC_ShortCodeCollection {}
	}
}
?>