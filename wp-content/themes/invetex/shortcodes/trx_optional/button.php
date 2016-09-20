<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_button_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_button_theme_setup' );
	function invetex_sc_button_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_button_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('invetex_sc_button')) {	
	function invetex_sc_button($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
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
		$css .= invetex_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (invetex_param_is_on($popup)) invetex_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (invetex_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. ($style != 'icon' ? do_shortcode($content) : '')
			. '</a>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	invetex_require_shortcode('trx_button', 'invetex_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_button_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_button_reg_shortcodes');
	function invetex_sc_button_reg_shortcodes() {
	
		invetex_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'invetex'),
			"desc" => wp_kses_data( __("Button with link", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'invetex'),
					"desc" => wp_kses_data( __("Button caption", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Button's style", 'invetex'),
					"desc" => wp_kses_data( __("Select button's style", 'invetex') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'invetex'),
						'filled2' => esc_html__('Filled 2', 'invetex'),
						'border' => esc_html__('Border', 'invetex'),
						'icon' => esc_html__('Icon', 'invetex')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'invetex'),
					"desc" => wp_kses_data( __("Select button's size", 'invetex') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'invetex'),
						'medium' => esc_html__('Medium', 'invetex'),
						'large' => esc_html__('Large', 'invetex')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'invetex'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'invetex') ),
					"value" => "",
					"type" => "icons",
					"options" => invetex_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'invetex'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'invetex') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'invetex'),
					"desc" => wp_kses_data( __("Any color for button's background", 'invetex') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'invetex'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'invetex') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'invetex'),
					"desc" => wp_kses_data( __("URL for link on button click", 'invetex') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'invetex'),
					"desc" => wp_kses_data( __("Target for link on button click", 'invetex') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'invetex'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'invetex') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'invetex'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'invetex') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'invetex_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_button_reg_shortcodes_vc');
	function invetex_sc_button_reg_shortcodes_vc() {
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'invetex'),
			"description" => wp_kses_data( __("Button with link", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'invetex'),
					"description" => wp_kses_data( __("Button caption", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'invetex'),
					"description" => wp_kses_data( __("Select button's style", 'invetex') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'invetex') => 'filled',
						esc_html__('Filled 2', 'invetex') => 'filled2',
						esc_html__('Border', 'invetex') => 'border',
						esc_html__('Icon', 'invetex') => 'icon'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'invetex'),
					"description" => wp_kses_data( __("Select button's size", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'invetex') => 'small',
						esc_html__('Medium', 'invetex') => 'medium',
						esc_html__('Large', 'invetex') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'invetex'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'invetex') ),
					"class" => "",
					"value" => invetex_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'invetex'),
					"description" => wp_kses_data( __("Any color for button's caption", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'invetex'),
					"description" => wp_kses_data( __("Any color for button's background", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'invetex'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'invetex') ),
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'invetex'),
					"description" => wp_kses_data( __("URL for the link on button click", 'invetex') ),
					"class" => "",
					"group" => esc_html__('Link', 'invetex'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'invetex'),
					"description" => wp_kses_data( __("Target for the link on button click", 'invetex') ),
					"class" => "",
					"group" => esc_html__('Link', 'invetex'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'invetex'),
					"description" => wp_kses_data( __("Open link target in popup window", 'invetex') ),
					"class" => "",
					"group" => esc_html__('Link', 'invetex'),
					"value" => array(esc_html__('Open in popup', 'invetex') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'invetex'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'invetex') ),
					"class" => "",
					"group" => esc_html__('Link', 'invetex'),
					"value" => "",
					"type" => "textfield"
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
		
		class WPBakeryShortCode_Trx_Button extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>