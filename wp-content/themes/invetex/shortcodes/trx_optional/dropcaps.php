<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_dropcaps_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_dropcaps_theme_setup' );
	function invetex_sc_dropcaps_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_dropcaps_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_dropcaps_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('invetex_sc_dropcaps')) {	
	function invetex_sc_dropcaps($atts, $content=null){
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width, $height);
		$style = min(4, max(1, $style));
		$content = do_shortcode(str_replace(array('[vc_column_text]', '[/vc_column_text]'), array('', ''), $content));
		$output = invetex_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(invetex_substr($content, 0, 1)) . '</span>' . trim(invetex_substr($content, 1))
			. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	invetex_require_shortcode('trx_dropcaps', 'invetex_sc_dropcaps');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_dropcaps_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_dropcaps_reg_shortcodes');
	function invetex_sc_dropcaps_reg_shortcodes() {
	
		invetex_sc_map("trx_dropcaps", array(
			"title" => esc_html__("Dropcaps", 'invetex'),
			"desc" => wp_kses_data( __("Make first letter as dropcaps", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'invetex'),
					"desc" => wp_kses_data( __("Dropcaps style", 'invetex') ),
					"value" => "1",
					"type" => "checklist",
					"options" => invetex_get_list_styles(1, 2)
				),
				"_content_" => array(
					"title" => esc_html__("Paragraph content", 'invetex'),
					"desc" => wp_kses_data( __("Paragraph with dropcaps content", 'invetex') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => invetex_shortcodes_width(),
				"height" => invetex_shortcodes_height(),
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
if ( !function_exists( 'invetex_sc_dropcaps_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_dropcaps_reg_shortcodes_vc');
	function invetex_sc_dropcaps_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_dropcaps",
			"name" => esc_html__("Dropcaps", 'invetex'),
			"description" => wp_kses_data( __("Make first letter of the text as dropcaps", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_dropcaps',
			"class" => "trx_sc_container trx_sc_dropcaps",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'invetex'),
					"description" => wp_kses_data( __("Dropcaps style", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_vc_width(),
				invetex_vc_height(),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_Dropcaps extends INVETEX_VC_ShortCodeContainer {}
	}
}
?>