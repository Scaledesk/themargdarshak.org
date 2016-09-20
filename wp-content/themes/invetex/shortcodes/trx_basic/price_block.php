<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_price_block_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_price_block_theme_setup' );
	function invetex_sc_price_block_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_price_block_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('invetex_sc_price_block')) {	
	function invetex_sc_price_block($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"subtitle" => "",
			"title2" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price title2="'.esc_attr($title2).'" money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(invetex_sc_clear_around($content));
		$output = '<div class="sc_price_wrap"><div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
					. '>'
			.(!empty($title) || !empty($subtitle) ? '<div class="first">' : '')
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. (!empty($subtitle) ? '<div class="sc_price_block_subtitle"><span>'.($subtitle).'</span></div>' : '')
			.(!empty($title) || !empty($subtitle) ? '</div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button size="medium" link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div></div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	invetex_require_shortcode('trx_price_block', 'invetex_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_price_block_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_price_block_reg_shortcodes');
	function invetex_sc_price_block_reg_shortcodes() {
	
		invetex_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'invetex'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'invetex'),
					"desc" => wp_kses_data( __("Block title", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'invetex'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'invetex'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'invetex'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'invetex'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'invetex') ),
					"value" => "",
					"type" => "icons",
					"options" => invetex_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'invetex'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'invetex') ),
					"divider" => true,
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
				"title2" => array(
					"title" => esc_html__("Title", 'invetex'),
					"desc" => wp_kses_data( __("Money title", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'invetex'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"options" => invetex_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'invetex'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'invetex') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'invetex'),
					"desc" => wp_kses_data( __("Description for this price block", 'invetex') ),
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
if ( !function_exists( 'invetex_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_price_block_reg_shortcodes_vc');
	function invetex_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'invetex'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'invetex'),
					"description" => wp_kses_data( __("Block title", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'invetex'),
					"description" => wp_kses_data( __("Subtitle for the block", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'invetex'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'invetex'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'invetex'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'invetex') ),
					"class" => "",
					"value" => invetex_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'invetex'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'invetex') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'invetex'),
					"description" => wp_kses_data( __("Currency character", 'invetex') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'invetex'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'invetex'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'invetex') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title2",
					"heading" => esc_html__("Title", 'invetex'),
					"description" => wp_kses_data( __("Money title", 'invetex') ),
					"admin_label" => false,
					"group" => esc_html__('Money', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'invetex'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('schemes')),
					"type" => "dropdown"
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
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'invetex'),
					"description" => wp_kses_data( __("Description for this price block", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>