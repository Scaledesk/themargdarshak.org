<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_infobox_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_infobox_theme_setup' );
	function invetex_sc_infobox_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_infobox_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('invetex_sc_infobox')) {	
	function invetex_sc_infobox($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
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
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		} else if ($icon=='none')
			$icon = '';

		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (invetex_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !invetex_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	invetex_require_shortcode('trx_infobox', 'invetex_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_infobox_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_infobox_reg_shortcodes');
	function invetex_sc_infobox_reg_shortcodes() {
	
		invetex_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'invetex'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'invetex'),
					"desc" => wp_kses_data( __("Infobox style", 'invetex') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'invetex'),
						'info' => esc_html__('Info', 'invetex'),
						'success' => esc_html__('Success', 'invetex'),
						'error' => esc_html__('Error', 'invetex')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'invetex'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'invetex') ),
					"value" => "no",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'invetex'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'invetex') ),
					"value" => "",
					"type" => "icons",
					"options" => invetex_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'invetex'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'invetex') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'invetex'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'invetex') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'invetex'),
					"desc" => wp_kses_data( __("Content for infobox", 'invetex') ),
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
				"animation" => invetex_get_sc_param('animation'),
				"css" => invetex_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_infobox_reg_shortcodes_vc');
	function invetex_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'invetex'),
			"description" => wp_kses_data( __("Box with info or error message", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'invetex'),
					"description" => wp_kses_data( __("Infobox style", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'invetex') => 'regular',
							esc_html__('Info', 'invetex') => 'info',
							esc_html__('Success', 'invetex') => 'success',
							esc_html__('Error', 'invetex') => 'error',
							esc_html__('Result', 'invetex') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'invetex'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'invetex') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'invetex') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'invetex'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'invetex') ),
					"class" => "",
					"value" => invetex_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'invetex'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'invetex'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends INVETEX_VC_ShortCodeContainer {}
	}
}
?>