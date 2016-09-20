<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_highlight_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_highlight_theme_setup' );
	function invetex_sc_highlight_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_highlight_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('invetex_sc_highlight')) {	
	function invetex_sc_highlight($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(invetex_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	invetex_require_shortcode('trx_highlight', 'invetex_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_highlight_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_highlight_reg_shortcodes');
	function invetex_sc_highlight_reg_shortcodes() {
	
		invetex_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'invetex'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'invetex'),
					"desc" => wp_kses_data( __("Highlight type", 'invetex') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'invetex'),
						1 => esc_html__('Type 1', 'invetex'),
						2 => esc_html__('Type 2', 'invetex'),
						3 => esc_html__('Type 3', 'invetex')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'invetex'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'invetex') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'invetex'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'invetex') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'invetex'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'invetex'),
					"desc" => wp_kses_data( __("Content for highlight", 'invetex') ),
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


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_highlight_reg_shortcodes_vc');
	function invetex_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'invetex'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'invetex'),
					"description" => wp_kses_data( __("Highlight type", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'invetex') => 0,
							esc_html__('Type 1', 'invetex') => 1,
							esc_html__('Type 2', 'invetex') => 2,
							esc_html__('Type 3', 'invetex') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'invetex'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'invetex'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'invetex'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'invetex'),
					"description" => wp_kses_data( __("Content for highlight", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>