<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_image_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_image_theme_setup' );
	function invetex_sc_image_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_image_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('invetex_sc_image')) {	
	function invetex_sc_image($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"src2" => "",
			"url" => "",
			"url2" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		$src2 = $src2!='' ? $src2 : $url2;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if ($src2 > 0) {
			$attach = wp_get_attachment_image_src( $src2, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src2 = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = invetex_get_resized_image_url($src, $w, $h);
			if ($w || $h) $src2 = invetex_get_resized_image_url($src2, $w, $h);
		}
		if (trim($link)) invetex_enqueue_popup();
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image '.(!empty($src2) ? ' style_img' : '').'' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img '.(!empty($src2) ? 'class="first" ' : '').'src="'.esc_url($src).'" alt="" />'
				. (!empty($src2) ? '<img class="second" src="'.esc_url($src2).'" alt="" />' : '')
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('invetex_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	invetex_require_shortcode('trx_image', 'invetex_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_image_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_image_reg_shortcodes');
	function invetex_sc_image_reg_shortcodes() {
	
		invetex_sc_map("trx_image", array(
			"title" => esc_html__("Image", 'invetex'),
			"desc" => wp_kses_data( __("Insert image into your post (page)", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", 'invetex'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'invetex') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"url2" => array(
					"title" => esc_html__("URL for back image file", 'invetex'),
					"desc" => wp_kses_data( __("Select or upload back image or write URL from other site", 'invetex') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true
					)
				),
				"title" => array(
					"title" => esc_html__("Title", 'invetex'),
					"desc" => wp_kses_data( __("Image title (if need)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'invetex'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'invetex') ),
					"value" => "",
					"type" => "icons",
					"options" => invetex_get_sc_param('icons')
				),
				"align" => array(
					"title" => esc_html__("Float image", 'invetex'),
					"desc" => wp_kses_data( __("Float image to left or right side", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('float')
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", 'invetex'),
					"desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", 'invetex') ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'invetex'),
						"round" => esc_html__('Round', 'invetex')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", 'invetex'),
					"desc" => wp_kses_data( __("The link URL from the image", 'invetex') ),
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
if ( !function_exists( 'invetex_sc_image_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_image_reg_shortcodes_vc');
	function invetex_sc_image_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", 'invetex'),
			"description" => wp_kses_data( __("Insert image", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", 'invetex'),
					"description" => wp_kses_data( __("Select image from library", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "url2",
					"heading" => esc_html__("Select back image", 'invetex'),
					"description" => wp_kses_data( __("Select back image from library", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", 'invetex'),
					"description" => wp_kses_data( __("Align image to left or right side", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", 'invetex'),
					"description" => wp_kses_data( __("Shape of the image: square or round", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'invetex') => 'square',
						esc_html__('Round', 'invetex') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'invetex'),
					"description" => wp_kses_data( __("Image's title", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", 'invetex'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'invetex') ),
					"class" => "",
					"value" => invetex_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'invetex'),
					"description" => wp_kses_data( __("The link URL from the image", 'invetex') ),
					"admin_label" => true,
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Image extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>