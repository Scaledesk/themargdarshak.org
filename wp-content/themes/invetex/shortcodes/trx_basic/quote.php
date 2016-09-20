<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_quote_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_quote_theme_setup' );
	function invetex_sc_quote_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_quote_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('invetex_sc_quote')) {	
	function invetex_sc_quote($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"cite" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width);
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (invetex_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"' 
			. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	invetex_require_shortcode('trx_quote', 'invetex_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_quote_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_quote_reg_shortcodes');
	function invetex_sc_quote_reg_shortcodes() {
	
		invetex_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", 'invetex'),
			"desc" => wp_kses_data( __("Quote text", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", 'invetex'),
					"desc" => wp_kses_data( __("URL for quote cite", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title (author)", 'invetex'),
					"desc" => wp_kses_data( __("Quote title (author name)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Quote content", 'invetex'),
					"desc" => wp_kses_data( __("Quote content", 'invetex') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => invetex_shortcodes_width(),
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
if ( !function_exists( 'invetex_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_quote_reg_shortcodes_vc');
	function invetex_sc_quote_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", 'invetex'),
			"description" => wp_kses_data( __("Quote text", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", 'invetex'),
					"description" => wp_kses_data( __("URL for the quote cite link", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", 'invetex'),
					"description" => wp_kses_data( __("Quote title (author name)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", 'invetex'),
					"description" => wp_kses_data( __("Quote content", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_vc_width(),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Quote extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>