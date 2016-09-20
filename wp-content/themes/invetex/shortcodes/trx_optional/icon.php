<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_icon_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_icon_theme_setup' );
	function invetex_sc_icon_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_icon_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('invetex_sc_icon')) {	
	function invetex_sc_icon($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
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
		$css2 = ($font_weight != '' && !invetex_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(invetex_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || invetex_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !invetex_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('invetex_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	invetex_require_shortcode('trx_icon', 'invetex_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_icon_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_icon_reg_shortcodes');
	function invetex_sc_icon_reg_shortcodes() {
	
		invetex_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'invetex'),
			"desc" => wp_kses_data( __("Insert icon", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'invetex'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'invetex') ),
					"value" => "",
					"type" => "icons",
					"options" => invetex_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'invetex'),
					"desc" => wp_kses_data( __("Icon's color", 'invetex') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'invetex'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'invetex') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'invetex'),
						'round' => esc_html__('Round', 'invetex'),
						'square' => esc_html__('Square', 'invetex')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'invetex'),
					"desc" => wp_kses_data( __("Icon's background color", 'invetex') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'invetex'),
					"desc" => wp_kses_data( __("Icon's font size", 'invetex') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'invetex'),
					"desc" => wp_kses_data( __("Icon font weight", 'invetex') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'invetex'),
						'300' => esc_html__('Light (300)', 'invetex'),
						'400' => esc_html__('Normal (400)', 'invetex'),
						'700' => esc_html__('Bold (700)', 'invetex')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'invetex'),
					"desc" => wp_kses_data( __("Icon text alignment", 'invetex') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'invetex'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'invetex') ),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'invetex_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_icon_reg_shortcodes_vc');
	function invetex_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'invetex'),
			"description" => wp_kses_data( __("Insert the icon", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'invetex'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => invetex_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'invetex'),
					"description" => wp_kses_data( __("Icon's color", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'invetex'),
					"description" => wp_kses_data( __("Background color for the icon", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'invetex'),
					"description" => wp_kses_data( __("Shape of the icon background", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'invetex') => 'none',
						esc_html__('Round', 'invetex') => 'round',
						esc_html__('Square', 'invetex') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'invetex'),
					"description" => wp_kses_data( __("Icon's font size", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'invetex'),
					"description" => wp_kses_data( __("Icon's font weight", 'invetex') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'invetex') => 'inherit',
						esc_html__('Thin (100)', 'invetex') => '100',
						esc_html__('Light (300)', 'invetex') => '300',
						esc_html__('Normal (400)', 'invetex') => '400',
						esc_html__('Bold (700)', 'invetex') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'invetex'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'invetex'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('css'),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>