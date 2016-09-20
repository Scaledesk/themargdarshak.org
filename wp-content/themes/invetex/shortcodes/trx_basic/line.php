<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_line_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_line_theme_setup' );
	function invetex_sc_line_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_line_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_line_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_line id="unique_id" style="none|solid|dashed|dotted|double|groove|ridge|inset|outset" top="margin_in_pixels" bottom="margin_in_pixels" width="width_in_pixels_or_percent" height="line_thickness_in_pixels" color="line_color's_name_or_#rrggbb"]
*/

if (!function_exists('invetex_sc_line')) {	
	function invetex_sc_line($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"color" => "",
			"title" => "",
			"position" => "",
			"image" => "",
			"repeat" => "no",
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
		if (empty($style)) $style = 'solid';
		if (empty($position)) $position = 'center center';
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$block_height = '';
		if ($style=='image' && !empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$attr = invetex_getimagesize($image);
			if (is_array($attr) && $attr[1] > 0)
				$block_height = $attr[1];
		} else if (!empty($title) && empty($height) && !in_array($position, array('left center', 'center center', 'right center'))) {
			$block_height = '1.5em';
		}
		$border_pos = in_array($position, array('left top', 'center top', 'right top')) ? 'bottom' : 'top';

		$css .= invetex_get_css_dimensions_from_values($width, $block_height)
			. ($style=='image' && !empty($image)
				? ( 'background-image: url(' . esc_url($image) . ');'
					. (invetex_param_is_on($repeat) ? 'background-repeat: repeat-x;' : '')
					)
				: ( ($height !='' ? 'border-'.esc_attr($border_pos).'-width:' . esc_attr(invetex_prepare_css_value($height)) . ';' : '')
					. ($style != '' ? 'border-'.esc_attr($border_pos).'-style:' . esc_attr($style) . ';' : '')
					. ($color != '' ? 'border-'.esc_attr($border_pos).'-color:' . esc_attr($color) . ';' : '')
					)
				);
		$output = '<div' . ($id ? ' id="'.esc_attr($id) . '"' : '') 
				. ' class="sc_line sc_line_position_'.esc_attr(str_replace(' ', '_', $position)) . ' sc_line_style_'.esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. (!empty($title) ? '<span class="sc_line_title">' . trim($title) . '</span>' : '')
				. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_line', $atts, $content);
	}
	invetex_require_shortcode('trx_line', 'invetex_sc_line');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_line_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_line_reg_shortcodes');
	function invetex_sc_line_reg_shortcodes() {
	
		invetex_sc_map("trx_line", array(
			"title" => esc_html__("Line", 'invetex'),
			"desc" => wp_kses_data( __("Insert Line into your post (page)", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'invetex'),
					"desc" => wp_kses_data( __("Line style", 'invetex') ),
					"value" => "solid",
					"dir" => "horizontal",
					"options" => invetex_get_list_line_styles(),
					"type" => "checklist"
				),
				"image" => array(
					"title" => esc_html__("Image as separator", 'invetex'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site to use it as separator", 'invetex') ),
					"readonly" => false,
					"dependency" => array(
						'style' => array('image')
					),
					"value" => "",
					"type" => "media"
				),
				"repeat" => array(
					"title" => esc_html__("Repeat image", 'invetex'),
					"desc" => wp_kses_data( __("To repeat an image or to show single picture", 'invetex') ),
					"dependency" => array(
						'style' => array('image')
					),
					"value" => "no",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"color" => array(
					"title" => esc_html__("Color", 'invetex'),
					"desc" => wp_kses_data( __("Line color", 'invetex') ),
					"dependency" => array(
						'style' => array('solid', 'dashed', 'dotted', 'double')
					),
					"value" => "",
					"type" => "color"
				),
				"title" => array(
					"title" => esc_html__("Title", 'invetex'),
					"desc" => wp_kses_data( __("Title that is going to be placed in the center of the line (if not empty)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"position" => array(
					"title" => esc_html__("Title position", 'invetex'),
					"desc" => wp_kses_data( __("Title position", 'invetex') ),
					"dependency" => array(
						'title' => array('not_empty')
					),
					"value" => "center center",
					"options" => invetex_get_list_bg_image_positions(),
					"type" => "select"
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
if ( !function_exists( 'invetex_sc_line_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_line_reg_shortcodes_vc');
	function invetex_sc_line_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_line",
			"name" => esc_html__("Line", 'invetex'),
			"description" => wp_kses_data( __("Insert line (delimiter)", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			"class" => "trx_sc_single trx_sc_line",
			'icon' => 'icon_trx_line',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'invetex'),
					"description" => wp_kses_data( __("Line style", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"std" => "solid",
					"value" => array_flip(invetex_get_list_line_styles()),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image as separator", 'invetex'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site to use it as separator", 'invetex') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('image')
					),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "repeat",
					"heading" => esc_html__("Repeat image", 'invetex'),
					"description" => wp_kses_data( __("To repeat an image or to show single picture", 'invetex') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('image')
					),
					"class" => "",
					"value" => array("Repeat image" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Line color", 'invetex'),
					"description" => wp_kses_data( __("Line color", 'invetex') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('solid','dotted','dashed','double')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'invetex'),
					"description" => wp_kses_data( __("Title that is going to be placed in the center of the line (if not empty)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Title position", 'invetex'),
					"description" => wp_kses_data( __("Title position", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"std" => "center center",
					"value" => array_flip(invetex_get_list_bg_image_positions()),
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
		
		class WPBakeryShortCode_Trx_Line extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>