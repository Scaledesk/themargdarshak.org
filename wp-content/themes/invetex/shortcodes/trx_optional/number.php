<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_number_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_number_theme_setup' );
	function invetex_sc_number_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_number_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_number_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('invetex_sc_number')) {	
	function invetex_sc_number($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < invetex_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(invetex_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	invetex_require_shortcode('trx_number', 'invetex_sc_number');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_number_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_number_reg_shortcodes');
	function invetex_sc_number_reg_shortcodes() {
	
		invetex_sc_map("trx_number", array(
			"title" => esc_html__("Number", 'invetex'),
			"desc" => wp_kses_data( __("Insert number or any word as set separate characters", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"value" => array(
					"title" => esc_html__("Value", 'invetex'),
					"desc" => wp_kses_data( __("Number or any word", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'invetex'),
					"desc" => wp_kses_data( __("Select block alignment", 'invetex') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('align')
				),
				"top" => invetex_get_sc_param('top'),
				"bottom" => invetex_get_sc_param('bottom'),
				"left" => invetex_get_sc_param('left'),
				"right" => invetex_get_sc_param('right'),
				"id" => invetex_get_sc_param('id'),
				"class" => invetex_get_sc_param('class'),
				"animation" => invetex_get_sc_param('animation'),
				"css" => invetex_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_number_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_number_reg_shortcodes_vc');
	function invetex_sc_number_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_number",
			"name" => esc_html__("Number", 'invetex'),
			"description" => wp_kses_data( __("Insert number or any word as set of separated characters", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			"class" => "trx_sc_single trx_sc_number",
			'icon' => 'icon_trx_number',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'invetex'),
					"description" => wp_kses_data( __("Number or any word to separate", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'invetex'),
					"description" => wp_kses_data( __("Select block alignment", 'invetex') ),
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('align')),
					"type" => "dropdown"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Number extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>