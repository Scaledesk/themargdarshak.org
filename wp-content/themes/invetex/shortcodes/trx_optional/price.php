<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_price_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_price_theme_setup' );
	function invetex_sc_price_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_price_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_price_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('invetex_sc_price')) {	
	function invetex_sc_price($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"title2" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		if (!empty($money)) {
			$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
			$m = explode('.', str_replace(',', '.', $money));
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. '>'
				. '<span class="sc_price_currency">'.($currency).'</span>'
				. '<span class="sc_price_money">'.($m[0]).'</span>'
				. (!empty($m[1]) ? '<span class="sc_price_info">' : '')
				. (!empty($m[1]) ? '<span class="sc_price_penny">'.($m[1]).'</span>' : '')
				. (!empty($period) ? '<span class="sc_price_period">'.($period).'</span>' : (!empty($m[1]) ? '<span class="sc_price_period_empty"></span>' : ''))
				. (!empty($m[1]) ? '</span>' : '')
				. '</div>'
				. (!empty($title2) ? '<div class="sc_price_des">'.$title2.'</div>' : '');
		}
		return apply_filters('invetex_shortcode_output', $output, 'trx_price', $atts, $content);
	}
	invetex_require_shortcode('trx_price', 'invetex_sc_price');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_price_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_price_reg_shortcodes');
	function invetex_sc_price_reg_shortcodes() {
	
		invetex_sc_map("trx_price", array(
			"title" => esc_html__("Price", 'invetex'),
			"desc" => wp_kses_data( __("Insert price with decoration", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"title2" => array(
					"title" => esc_html__("Description", 'invetex'),
					"desc" => wp_kses_data( __("Money Description", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"money" => array(
					"title" => esc_html__("Money", 'invetex'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'invetex'),
					"desc" => wp_kses_data( __("Currency character", 'invetex') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'invetex'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'invetex'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('float')
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
if ( !function_exists( 'invetex_sc_price_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_price_reg_shortcodes_vc');
	function invetex_sc_price_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price",
			"name" => esc_html__("Price", 'invetex'),
			"description" => wp_kses_data( __("Insert price with decoration", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_price',
			"class" => "trx_sc_single trx_sc_price",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title2",
					"heading" => esc_html__("Description", 'invetex'),
					"description" => wp_kses_data( __("Money Description", 'invetex') ),
					"admin_label" => false,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'invetex'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'invetex'),
					"description" => wp_kses_data( __("Currency character", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'invetex'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'invetex'),
					"description" => wp_kses_data( __("Align price to left or right side", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('float')),
					"type" => "dropdown"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('css'),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Price extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>